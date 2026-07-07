<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TrainingCourseResource;
use App\Models\TrainingCourse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TrainingCourseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TrainingCourseResource::collection(
            TrainingCourse::where('is_active', true)
                ->orderByRaw('start_date IS NULL, start_date')
                ->get()
        );
    }

    public function show(int $id): TrainingCourseResource
    {
        return new TrainingCourseResource(
            TrainingCourse::where('is_active', true)->findOrFail($id)
        );
    }
}
