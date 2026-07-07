<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TrainingRegistrationResource;
use App\Models\TrainingCourse;
use App\Models\TrainingRegistration;
use App\Notifications\TrainingRegistrationConfirmed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Validation\ValidationException;

class TrainingRegistrationController extends Controller
{
    public function mine(Request $request): AnonymousResourceCollection
    {
        return TrainingRegistrationResource::collection(
            $request->user()->trainingRegistrations()->with('trainingCourse')->latest('registered_at')->get()
        );
    }

    public function store(Request $request, TrainingCourse $trainingCourse): JsonResponse
    {
        $user = auth('sanctum')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
        ]);

        if (TrainingRegistration::where('training_course_id', $trainingCourse->id)->where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages(['email' => ['This email is already registered for this course.']]);
        }

        $registration = TrainingRegistration::create([
            ...$data,
            'training_course_id' => $trainingCourse->id,
            'user_id' => $user?->id,
            'status' => $trainingCourse->hasCapacity() ? 'registered' : 'waitlisted',
            'registered_at' => now(),
        ]);

        (new AnonymousNotifiable)->route('mail', $registration->email)->notify(new TrainingRegistrationConfirmed($registration));

        return response()->json(['data' => new TrainingRegistrationResource($registration->load('trainingCourse'))], 201);
    }
}
