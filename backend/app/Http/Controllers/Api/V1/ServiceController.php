<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ServiceResource::collection(
            Service::where('is_active', true)->orderBy('name_en')->get()
        );
    }

    public function show(string $slug): ServiceResource
    {
        return new ServiceResource(
            Service::where('slug', $slug)->where('is_active', true)->firstOrFail()
        );
    }
}
