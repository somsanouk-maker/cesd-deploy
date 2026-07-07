<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'laboratory_id' => ['nullable', 'exists:laboratories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'sample_information' => ['nullable', 'string', 'max:2000'],
            'required_date' => ['nullable', 'date'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
        ]);

        $serviceRequest = DB::transaction(function () use ($data) {
            $data['request_no'] = $this->generateRequestNumber();
            $data['user_id'] = auth('sanctum')->id();

            return ServiceRequest::create($data);
        });

        return response()->json([
            'data' => ['request_no' => $serviceRequest->request_no],
        ], 201);
    }

    private function generateRequestNumber(): string
    {
        $year = now()->year;
        $count = ServiceRequest::whereYear('created_at', $year)->lockForUpdate()->count();

        return sprintf('SR-%d-%04d', $year, $count + 1);
    }
}
