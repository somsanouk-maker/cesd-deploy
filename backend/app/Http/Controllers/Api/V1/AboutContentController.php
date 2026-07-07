<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AboutContentResource;
use App\Models\AboutContent;

class AboutContentController extends Controller
{
    public function show(): AboutContentResource
    {
        return new AboutContentResource(AboutContent::current());
    }
}
