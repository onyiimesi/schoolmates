<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $reports = ReportResource::collection(
            Report::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)->get()
        );
        return [
            'status' => 'true',
            'message' => 'Reports',
            'data' => $reports
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => ['required', 'string'],
            'attribute' => ['required', 'array']
        ]);

        $user = Auth::user();

        $report = Report::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('report_type', $request->report_type)->first();

        if(empty($report)){
            $reports = Report::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'report_type' => $request->report_type,
                'attribute' => $request->attribute
            ]);

            return [
                'status' => 'true',
                'message' => 'Added Successfully',
                'data' => $reports
            ];

        }else if(!empty($report)){

            $report->update([
                'attribute' => $request->attribute
            ]);

            return [
                'status' => 'true',
                'message' => 'Added Successfully'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        $reports = new ReportResource($report);
        return [
            'status' => 'true',
            'message' => '',
            'data' => $reports
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $report->update($request->all());

        new ReportResource($report);
        return [
            "status" => 'true',
            "message" => 'Updated Successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return response(null, 204);
    }
}
