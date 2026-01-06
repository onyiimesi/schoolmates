<?php

namespace App\Http\Resources;

use App\Models\Result;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SchoolsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $baseQuery = Result::where('sch_id', $this->sch_id)
            ->where('term', $this->currentAcademicPeriod->term)
            ->where('session', $this->currentAcademicPeriod->session);

        $latestResultIds = $baseQuery
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('student_id')
            ->pluck('id');

        $totalStudents = $latestResultIds->count();
        $subAmount = (float) $this->amount_per_student * $totalStudents;
        $invoiceStatus = 'pending';

        if ($this->activeSubscription) {
            $invoiceStatus = $this->activeSubscription === 'expired' ? 'pending' : 'paid';
        }

        return [
            'id' => (string)$this->id,
            'attributes' => [
                'sch_id' => (string)$this->sch_id,
                'schname' => (string)$this->schname,
                'schaddr' => (string)$this->schaddr,
                'schphone' => (string)$this->schphone,
                'schemail' => (string)$this->schemail,
                'schmotto' => (string)$this->schmotto,
                'schwebsite' => (string)$this->schwebsite,
                'schlogo' => (string)$this->schlogo,
                'subscriptions' => $this->subscriptions ? $this->subscriptions->map(fn ($subscription) => [
                    'id' => $subscription->id,
                    'term' => $subscription->term,
                    'session' => $subscription->session,
                    'starts_at' => $subscription->starts_at->toDateString(),
                    'ends_at' => $subscription->ends_at->toDateString(),
                    'amount' => $subscription->amount,
                    'status' => $subscription->status,
                    'created_at' => $subscription->created_at->toDateString()
                ])->toArray() : [],
                'current_subscription' => (object) [
                    'starts_at' => $this->activeSubscription ? $this->activeSubscription->starts_at->toDateString() : null,
                    'ends_at' => $this->activeSubscription ? $this->activeSubscription->ends_at->toDateString() : null,
                    'amount' => (string) $this->activeSubscription ? $this->activeSubscription->amount : $subAmount,
                    'status' => (string) $this->activeSubscription ? $this->activeSubscription->status : 'expired',
                ],
                'invoice' => (object) [
                    'term' => $this->currentAcademicPeriod?->term,
                    'session' => $this->currentAcademicPeriod?->session,
                    'total_students' => $totalStudents,
                    'amount_per_student' => (float) $this->amount_per_student,
                    'total_amount' => $subAmount,
                    'status' => $invoiceStatus,
                ],
            ]
        ];
    }
}
