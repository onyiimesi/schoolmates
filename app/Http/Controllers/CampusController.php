<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\App;
use App\Http\Requests\CampusRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CampusResource;
use Illuminate\Http\JsonResponse;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class CampusController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     */
    public function index(): JsonResponse
    {
        /** @var Staff|Student $user */
        $user = Auth::user();

        $campus = CampusResource::collection(
            Campus::where('sch_id', $user->sch_id)->get()
        );

        return $this->success($campus, 'Campus List');
    }

    public function store(CampusRequest $request): JsonResponse
    {
        $request->validated($request->all());
        
        /** @var Staff|Student $user */
        $user = Auth::user();

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if($request->image) {
            $campusPath = uploadImage($request->image, 'campus', $cleanSchId);
        }

        $slug = Str::slug($request->name);
        if (Campus::where('slug', $slug)->exists()) {
            $slug .= '-'.uniqid();
        }

        Campus::create([
            'sch_id' => $user->sch_id,
            'name' => $request->name,
            'slug' => $slug,
            'email' => $request->email,
            'image' => $campusPath['url'] ?? null,
            'file_id' => $campusPath['file_id'] ?? null,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'campus_type' => $request->campus_type,
            'is_preschool' => $request->is_preschool,
            'status' => 'active',
            'created_by' => "{$user->surname} {$user->firstname} {$user->middlename}",
        ]);

        return $this->success(null, 'Campus Created Successfully', 201);
    }

    public function show(Campus $campus): JsonResponse
    {
        $campuss = new CampusResource($campus);

        return $this->success($campuss, 'Campus Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request, Campus $campus): JsonResponse
    {
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $campus->sch_id);

        if($request->image) {
            $fileId = $campus->file_id ?? null;
            $campusPath = uploadImage($request->image, 'campus', $cleanSchId, $fileId);
        }

        if ($request->filled('name')) {
            $slug = Str::slug($request->name);
            if (Campus::where('slug', $slug)->exists()) {
                $slug .= '-'.uniqid();
            }
        }

        $campus->update([
            'name' => $request->name,
            'slug' => $slug ?? $campus->slug,
            'email' => $request->email,
            'image' => $campusPath['url'] ?? $campus->image,
            'file_id' => $campusPath['file_id'] ?? $campus->file_id,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'campus_type' => $request->campus_type,
            'is_preschool' => $request->is_preschool,
        ]);

        return $this->success(null, 'Updated Successfully');
    }

    public function destroy(Campus $campus): Response|ResponseFactory
    {
        $campus->delete();

        return response(null, 204);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $path = null;

        if($request->hasFile('image')) {
            $name = $request->input('name');
            $path = $request->file('image')->store("campus/{$name}", 'public');

            if(App::environment(['production', 'staging'])) {
                $res = new \Illuminate\Http\JsonResponse(['image_path' => asset('public/storage/' . $path)], 200);
            } else {
                $res = new \Illuminate\Http\JsonResponse(['image_path' => asset('storage/' . $path)], 200);
            }

            return $res;
        }

        return new \Illuminate\Http\JsonResponse(['error' => 'No image uploaded'], 400);
    }
}
