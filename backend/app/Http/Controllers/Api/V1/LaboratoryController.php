<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LaboratoryResource;
use App\Models\Laboratory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LaboratoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $laboratories = Laboratory::query()
            ->where('status', 'active')
            ->withCount(['equipment' => fn ($query) => $query->where('is_accessory', false)])
            ->with('responsibleUser')
            ->orderBy('name_en')
            ->get();

        return LaboratoryResource::collection($laboratories);
    }

    public function show(string $code): LaboratoryResource
    {
        $laboratory = Laboratory::where('code', $code)
            ->withCount(['equipment' => fn ($query) => $query->where('is_accessory', false)])
            ->with('responsibleUser')
            ->firstOrFail();

        return new LaboratoryResource($laboratory);
    }
}
