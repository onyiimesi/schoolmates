<?php

namespace App\Http\Resources;

use App\Services\ResultPresenter;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    protected $presenter;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->presenter = app(ResultPresenter::class);
    }

    public function toArray($request)
    {
        $meta = $this->presenter->getMetadata($this->resource, $this->class_name);

        return [
            'id' => (string)$this->id,
            'attributes' => array_merge(
                $this->studentInfo(),
                $this->scores(),
                $this->remarks(),
                $this->meta($meta['staff'], $meta['hods'], $meta['dos'])
            )
        ];
    }

    private function studentInfo(): array
    {
        return [
            'campus' => (string)$this->campus,
            'campus_type' => (string)$this->campus_type,
            'student_id' => (string)$this->student_id,
            'student_fullname' => (string)$this->student_fullname,
            'student_image' => (string)$this->student?->image,
            'admission_number' => (string)$this->admission_number,
            'gender' => (string)$this->student?->gender,
            'class_name' => (string)$this->class_name,
            'period' => (string)$this->period,
            'term' => (string)$this->term,
            'session' => (string)$this->session,
        ];
    }

    private function scores(): array
    {
        $gpaData = $this->presenter->getGpa($this->resource);
        $classStats = $this->presenter->getClassStats($this->resource);
        $subjectAverages = $this->presenter->getSubjectAverages($this->resource);
        $subjectPositions = $this->presenter->getSubjectPositions($this->resource);

        return [
            'school_opened' => (string)$this->school_opened,
            'times_present' => (string)$this->times_present,
            'times_absent' => (string)$this->times_absent,
            'number_in_class' => $classStats['class_count'],
            'class_total' => $classStats['class_total_score'],
            'results' => $this->studentScores?->filter(fn($s) => $s->score != 0)
            ->map(fn($s) => [
                "subject" => $s->subject,
                "score" => $s->score,
                "subject_average" => $subjectAverages[$s->subject] ?? null,
                "subject_position" => $subjectPositions[$s->subject] ?? null,
            ])->values() ?? [],
            'total_subjects' => $gpaData['total_subjects'],
            'total_score' => $gpaData['total_score'],
            'student_average' => $gpaData['student_average'],
            'class_average' => $classStats['class_average'],
            'class_grade' => $classStats['class_grade'],
            'gpa' => $gpaData['gpa'],
            'position' => $classStats['position'],
        ];
    }

    private function remarks(): array
    {
        return [
            'affective_disposition' => $this->affectiveDispositions?->map(fn($a) => ["name" => $a->name, "score" => $a->score])->values() ?? [],
            'psychomotor_skills' => $this->psychomotorskill?->map(fn($p) => ["name" => $p->name, "score" => $p->score])->values() ?? [],
            'extra_curricular_activities' => $this->resultExtraCurriculars?->map(fn($e) => ["name" => $e->name, "value" => $e->value])->values() ?? [],
            'abacus' => (object)["name" => $this->abacus?->name],
            'psychomotor_performance' => $this->psychomotorPerformances?->map(fn($p) => ["name" => $p->name, "score" => $p->score])->values() ?? [],
            'pupil_report' => $this->pupilReports?->map(fn($r) => ["name" => $r->name, "score" => $r->score])->values() ?? [],
            'teacher_comment' => $this->teacher_comment,
            'performance_remark' => (string)$this->performance_remark,
            'hos_comment' => (string)$this->hos_comment,
            'hos_fullname' => (string)$this->hos_fullname,
            'computed_endterm' => (string)$this->computed_endterm,
        ];
    }

    private function meta($staff, $hods, $dos): array
    {
        return [
            'teachers' => collect($staff)->filter(fn($t) => is_object($t))->map(fn($t) => [
                "name" => "$t->surname $t->firstname",
                "signature" => $t->signature
            ])->values(),
            'hos' => collect($hods)->map(fn($h) => ["name" => "$h->surname $h->firstname", "signature" => $h->signature])->values() ?? [],
            'dos' => $dos,
            'status' => (string)$this->status,
        ];
    }
}
