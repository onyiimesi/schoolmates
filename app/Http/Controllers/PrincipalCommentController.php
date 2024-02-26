<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrincipalCommentRequest;
use App\Http\Resources\PrincipalCommentResource;
use App\Models\PrincipalComment;
use App\Models\Schools;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalCommentController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $principal = PrincipalCommentResource::collection(
            PrincipalComment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Principal Comments',
            'data' => $principal
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrincipalCommentRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $school = Schools::where('sch_id', $user->sch_id)
        ->first();

        if($user->designation_id == '3'){
            $user_id = $user->id;
            $user_fullname = $user->surname . ' '. $user->firstname;

        }else {

            return $this->error('', "Can't perform this action", 401);
        }

        $principal = PrincipalComment::create([
            'sch_id' => $school->sch_id,
            'campus' => $user->campus,
            'hos_id' => $user_id,
            'hos_fullname' => $user_fullname,
            'hos_comment' => $request->hos_comment,
            'signature' => $user->signature,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $principal
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
