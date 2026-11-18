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
            "students" => $this->getStudentsByClass($user, $params["class"]),
            "subjects" => $this->getSubjects($user, $params),
        ];

        $assessmentTypes = $this->getAssessmentTypes($assessmentType);

        foreach ($assessmentTypes as $resultType) {
            $data[$resultType] = $this->getAssessmentResults(
                $user,
                $params,
                $resultType,
            );
        }

        $hasMatchingResults = collect($assessmentTypes)
            ->contains(fn ($resultType) => $data[$resultType]->isNotEmpty());

        if ($notice = $this->resultClassNotice($user, $params, $assessmentTypes, $hasMatchingResults)) {
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

        $endTermResults = Result::with([
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
            ->whereClass("class_name", $params["class"])
            ->when(!empty($params["status"]), function ($query) use ($params) {
                $query->where("status", $params["status"]);
            })
            ->get();

        $data = [
            "students" => $this->getStudentsByClass($user, $params["class"]),
            "subjects" => $this->getSubjects($user, $params),
            "grading" => $this->getGrading($user),
            "extra_curricular" => $this->getExtraCurricular($user),
            "results" => ResultResource::collection($endTermResults),
        ];

        $assessmentTypes = $this->getAssessmentTypes($assessmentType);

        foreach ($assessmentTypes as $resultType) {
            $data[$resultType] = $this->getAssessmentResults(
                $user,
                $params,
                $resultType,
            );
        }

        if ($notice = $this->resultClassNotice(
            $user,
            $params,
            [$params["result_type"]],
            $endTermResults->isNotEmpty(),
            true,
        )) {
            $data["result_class_notice"] = $notice;
        }

        return $this->success($data, "Retrieved successfully");
    }

    /**
     * Explain why a class-filtered result lookup returned nothing.
     */
    private function resultClassNotice(
        $user,
        array $params,
        array $resultTypes,
        bool $hasMatchingResults,
        bool $applyStatus = false,
    ): ?array {
        if ($hasMatchingResults) {
            return null;
        }

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
            "message" => "No result found for {$requestedClass} in {$params["session"]} {$params["term"]} ({$params["period"]}). The available result is for {$actualClass}.",
        ];
    }
}
