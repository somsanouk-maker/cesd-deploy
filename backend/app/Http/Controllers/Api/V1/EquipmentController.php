<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EquipmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Equipment::query()
            ->with(['laboratory', 'category'])
            ->where('is_accessory', false);

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_lo', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($laboratoryId = $request->query('laboratory_id')) {
            $query->where('laboratory_id', $laboratoryId);
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($availability = $request->query('availability_status')) {
            $query->where('availability_status', $availability);
        }

        $equipment = $query->orderBy('name_en')->paginate(24)->withQueryString();

        return EquipmentResource::collection($equipment);
    }

    public function show(string $code): EquipmentResource
    {
        $equipment = Equipment::where('code', $code)
            ->with(['laboratory', 'category', 'accessories'])
            ->firstOrFail();

        return new EquipmentResource($equipment);
    }
}
