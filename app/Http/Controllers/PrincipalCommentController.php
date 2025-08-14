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

        return $this->success($principal, "Principal comments");
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
        ->firstOrFail();

        if($user->designation_id !== '3'){
            return $this->error(null, "You cannot perform this action", 403);
        }

        $principal = PrincipalComment::create([
            'sch_id' => $school->sch_id,
            'campus' => $user->campus,
            'hos_id' => $user->id,
            'hos_fullname' => "{$user->surname} {$user->firstname}",
            'hos_comment' => $request->hos_comment,
            'signature' => $user->signature,
        ]);

        return $this->success($principal, "Principal comments");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PrincipalComment $principalComment)
    {
        $comment = new PrincipalCommentResource($principalComment);
        return $this->success($comment, "Principal comment");
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
        $request->validate([
            'hos_comment' => 'required|string|max:500',
        ]);

        $principalComment = PrincipalComment::findOrFail($id);

        $principalComment->update([
            'hos_comment' => $request->hos_comment,
        ]);

        return $this->success(null, "Principal comments updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $principalComment = PrincipalComment::findOrFail($id);

        $principalComment->delete();
        return $this->success(null, "Principal comments deleted successfully");
    }
}
