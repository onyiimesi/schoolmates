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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request): JsonResponse
    {
        $request->validated($request->all());

        $staffGuard = Auth::guard('staffs');
        $studGuard = Auth::guard('studs');

        if ($staffGuard->attempt($request->only(['username', 'password']))) {
            /** @var Staff $auth */
            $auth = Auth::guard('staffs')->user();

            if (!in_array($auth->status, haystack: ['active'])) {
                return $this->error(null, 'Account is inactive, contact support', 400);
            }

            $user = Staff::with(['school.activeSubscription', 'school.currentAcademicPeriod', 'subjectTeachers'])
                ->where('sch_id', $auth->sch_id)
                ->where('username', $auth->username)
                ->first();

            $users = new LoginResource($user);
            $token = $user->createToken("API Token of {$user->username}");

            return $this->success([
                'user' => $users,
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at
            ], 'Login successful');
        } elseif ($studGuard->attempt($request->only(['username', 'password']))) {
            /** @var Student $auth */
            $auth = Auth::guard('studs')->user();

            if (!in_array($auth->status, haystack: ['active'])) {
                return $this->error(null, 'Account is inactive, contact support', 400);
            }

            $stud = Student::with(['school.activeSubscription', 'school.currentAcademicPeriod'])
                ->where('sch_id', $auth->sch_id)
                ->where('username', $auth->username)
                ->first();

            $studs = new StudentLoginResource($stud);
            $token = $stud->createToken("API Token of {$stud->username}");

            return $this->success([
                'user' => $studs,
                'token' => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at
            ]);
        }

        return $this->error(null, 'Credentials do not match', 401);
    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'data' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var Staff|Student $user */
        $user = $request->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return $this->success([
            'message' => 'You have successfully logged out and your token has been deleted'
        ]);
    }

    public function change(ChangePassRequest $request): JsonResponse
    {
        $request->validated($request->all());

        /** @var Staff|Student $user */
        $user = $request->user();

        if (Hash::check($request->old_password, $user->password)) {

            $user->update([
                'password' => Hash::make($request->new_password),
                'pass_word' => $request->new_password,
            ]);

            return $this->success(null, 'Password changed successfully');
        } else {
            return $this->error(null, 'Old Password did not match', 422);
        }
    }
}
