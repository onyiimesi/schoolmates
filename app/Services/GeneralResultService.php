<?php

namespace App\Services;

use App\Enum\PeriodicName;
use App\Http\Resources\MidTermResultResource;
use App\Http\Resources\ResultResource;
use App\Models\Result;
use App\Traits\HttpResponses;
use App\Traits\ResultTrait;

class GeneralResultService
{
    use HttpResponses, ResultTrait;

    public function firstHalf($user, array $params)
    {
        if ($params["period"] !== PeriodicName::FIRSTHALF) {
            return $this->error(null, "Invalid Period", 400);
        }

        $scoreSetting = $this->getScoreSetting($user);

        if (!$scoreSetting || !$scoreSetting->scoreOption) {
            return $this->error(null, "Score setting not found", 400);
        }

        $assessmentType = (int) $scoreSetting->scoreOption->assessment_type;

        $data = [
            "students" => $this->getStudentsByClass(
                $user,
                $params["class"],
                $params["session"] ?? null,
                $params["term"] ?? null,
            ),
            "subjects" => $this->getSubjects($user, $params),
        ];

        $assessmentTypes = $this->getAssessmentTypes($assessmentType);

        foreach ($assessmentTypes as $resultType) {
            $data[$resultType] = $this->getAssessmentResultsForRequest(
                $user,
                $params,
                $resultType,
            );
        }

        if ($notice = $this->resultClassNotice($user, $params, $assessmentTypes)) {
            $data["result_class_notice"] = $notice;
        }

        return $this->success($data, "Mid term result");
    }

    public function secondHalf($user, array $params)
    {
        if ($params["period"] !== PeriodicName::SECONDHALF) {
            return $this->error(null, "Invalid Period", 400);
        }

        $scoreSetting = $this->getScoreSetting($user);

        if (!$scoreSetting || !$scoreSetting->scoreOption) {
            return $this->error(null, "Score setting not found", 400);
        }

        $assessmentType = (int) $scoreSetting->scoreOption->assessment_type;

        $endTermQuery = Result::with([
            "student",
            "studentScores",
            "affectiveDispositions",
            "psychomotorskill",
            "resultExtraCurriculars",
            "abacus",
            "psychomotorPerformances",
            "pupilReports",
        ])
            ->where([
                "sch_id" => $user->sch_id,
                "campus" => $user->campus,
                "student_id" => $params["student_id"],
                "period" => $params["period"],
                "term" => $params["term"],
                "session" => $params["session"],
                "result_type" => $params["result_type"],
            ])
            ->when(!empty($params["status"]), function ($query) use ($params) {
                $query->where("status", $params["status"]);
            });

        // Prefer the requested class, but fall back to the class the result
        // was actually recorded under so promoted students still see it.
        $endTermResults = (clone $endTermQuery)
            ->whereClass("class_name", $params["class"])
            ->get();

        if ($endTermResults->isEmpty()) {
            $endTermResults = $endTermQuery->get();
        }

        $data = [
            "students" => $this->getStudentsByClass(
                $user,
                $params["class"],
                $params["session"] ?? null,
                $params["term"] ?? null,
            ),
            "subjects" => $this->getSubjects($user, $params),
            "grading" => $this->getGrading($user),
            "extra_curricular" => $this->getExtraCurricular($user),
            "results" => ResultResource::collection($endTermResults),
        ];

        $assessmentTypes = $this->getAssessmentTypes($assessmentType);

        foreach ($assessmentTypes as $resultType) {
            $data[$resultType] = $this->getAssessmentResultsForRequest(
                $user,
                $params,
                $resultType,
            );
        }

        if ($notice = $this->resultClassNotice(
            $user,
            $params,
            [$params["result_type"]],
            true,
        )) {
            $data["result_class_notice"] = $notice;
        }

        return $this->success($data, "Retrieved successfully");
    }

    /**
     * Return the result for the requested class when it exists, otherwise fall
     * back to the class the result was actually recorded under.
     */
    private function getAssessmentResultsForRequest($user, array $params, string $resultType)
    {
        $matching = $this->getAssessmentResults($user, $params, $resultType, true);

        if ($matching->isNotEmpty()) {
            return $matching;
        }

        return $this->getAssessmentResults($user, $params, $resultType, false);
    }

    /**
     * Explain when the returned result was recorded under a different class.
     */
    private function resultClassNotice(
        $user,
        array $params,
        array $resultTypes,
        bool $applyStatus = false,
    ): ?array {
        $query = Result::where([
            "sch_id" => $user->sch_id,
            "campus" => $user->campus,
            "student_id" => $params["student_id"],
            "period" => $params["period"],
            "term" => $params["term"],
            "session" => $params["session"],
        ])->whereIn("result_type", $resultTypes);

        if ($applyStatus && !empty($params["status"])) {
            $query->where("status", $params["status"]);
        }

        $result = $query->get()->first(function (Result $result) use ($params) {
            return classNameLookupKey($result->class_name) !== classNameLookupKey($params["class"]);
        });

        if (!$result) {
            return null;
        }

        $requestedClass = normalizeClassName($params["class"]);
        $actualClass = normalizeClassName($result->class_name);

        return [
            "requested_class" => $requestedClass,
            "actual_class" => $actualClass,
            "result_type" => $result->result_type,
            "message" => "The requested class is {$requestedClass}, but the result for {$params["session"]} {$params["term"]} ({$params["period"]}) is recorded under {$actualClass}.",
        ];
    }
}
