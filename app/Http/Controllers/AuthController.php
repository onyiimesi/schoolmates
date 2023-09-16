<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\StudentLoginResource;
use App\Http\Resources\StudentResource;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request) {

        $request->validated($request->all());

        $staffGuard = Auth::guard('staffs');
        $studGuard = Auth::guard('studs');

        if ($staffGuard->attempt($request->only(['username', 'password']))) {
            $user = Staff::where('username', $request->username)->first();
            $users = new LoginResource($user);
            $token = $user->createToken('API Token of '. $user->username);

            return $this->success([
                'user' => $users,
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at
            ]);
        } elseif ($studGuard->attempt($request->only(['username', 'password']))) {
            $stud = Student::where('username', $request->username)->first();
            $studs = new StudentLoginResource($stud);
            $token = $stud->createToken('API Token of '. $stud->username);

            return $this->success([
                'user' => $studs,
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at
            ]);
        }

        return $this->error('', 'Credentials do not match', 401);


        // if(Auth::guard('staffs')->attempt($request->only(['username', 'password']))){
        //     $user = Staff::where('username', $request->username)->first();

        //     $users = new LoginResource($user);

        //     $token = $user->createToken('API Token of '. $user->username);

        //     return $this->success([
        //         'user' => $users,
        //         'token' => $token->plainTextToken,
        //         'expires_at' => $token->accessToken->expires_at
        //     ]);
        // }

        // if(Auth::guard('studs')->attempt($request->only(['username', 'password']))){
        //     $stud = Student::where('username', $request->username)->first();

        //     $studs = new StudentLoginResource($stud);

        //     $token = $stud->createToken('API Token of '. $stud->username);

        //     return $this->success([
        //         'user' => $studs,
        //         'token' => $token->plainTextToken,
        //         'expires_at' => $token->accessToken->expires_at
        //     ]);
        // }

        // if(!Auth::guard('staffs')->attempt($request->only(['username', 'password']))){
        //     return $this->error('', 'Credentials do not match', 401);
        // }

        // if(!Auth::guard('studs')->attempt($request->only(['username', 'password']))){
        //     return $this->error('', 'Credentials do not match', 401);
        // }

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
