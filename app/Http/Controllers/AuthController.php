<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\StudentLoginResource;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        // $this->middleware('throttle:3,5')->only(['token', 'verify', 'loginVerify']);
    }

    public function login(LoginUserRequest $request) {

        $request->validated($request->all());

        $staffGuard = Auth::guard('staffs');
        $studGuard = Auth::guard('studs');

        if ($staffGuard->attempt($request->only(['username', 'password']))) {
            $auth = Auth::guard('staffs')->user();

            if($auth->status === "inactive"){
                return $this->error('', 'Account is inactive, contact support', 400);
            }

            $user = Staff::with(['school', 'subjectteacher'])
                ->where('sch_id', $auth->sch_id)
                ->where('username', $auth->username)
                ->first();

            $users = new LoginResource($user);

            $user->tokens()->delete();
            $token = $user->createToken('API Token of '. $user->username);

            return $this->success([
                'user' => $users,
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at
            ]);

        } elseif ($studGuard->attempt($request->only(['username', 'password']))) {
            $auth = Auth::guard('studs')->user();

            if($auth->status === "inactive"){
                return $this->error('', 'Account is inactive, contact support', 400);
            }

            $stud = Student::where('sch_id', $auth->sch_id)
                ->where('username', $auth->username)
                ->first();

            $studs = new StudentLoginResource($stud);

            $stud->tokens()->delete();
            $token = $stud->createToken('API Token of '. $stud->username);

            return $this->success([
                'user' => $studs,
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at
            ]);
        }

        return $this->error('', 'Credentials do not match', 401);
    }

    public function register(StoreUserRequest $request) {

        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'data' => $user,
            'token' =>$user->createToken('API Token of '. $user->name)->plainTextToken
        ]);
    }

    public function logout() {

        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        // Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully logged out and your token has been deleted'
        ]);
    }

    public function change(ChangePassRequest $request){

        $request->validated($request->all());

        $user = $request->user();

        if (Hash::check($request->old_password, $user->password)) {

            $user->update([

                'password' => Hash::make($request->new_password),
                'pass_word' => $request->new_password,

            ]);

             return [
                "status" => 'true',
                "message" => 'Password Successfully Updated',
            ];

        }else
        {
            return $this->error('', 'Old Password did not match', 422);
        }
    }
}
