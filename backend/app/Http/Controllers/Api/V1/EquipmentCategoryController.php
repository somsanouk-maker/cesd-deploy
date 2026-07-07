<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EquipmentCategoryResource;
use App\Models\EquipmentCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EquipmentCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EquipmentCategoryResource::collection(
            EquipmentCategory::orderBy('name_en')->get()
        );
    }
}
