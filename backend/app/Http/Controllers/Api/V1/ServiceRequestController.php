<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ServiceRequestResource;
use App\Models\ServiceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ServiceRequestController extends Controller
{
    public function mine(Request $request): AnonymousResourceCollection
    {
        return ServiceRequestResource::collection(
            $request->user()->serviceRequests()->with(['service', 'laboratory'])->latest()->get()
        );
    }

    public function show(Request $request, ServiceRequest $serviceRequest): ServiceRequestResource
    {
        if ($serviceRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        return new ServiceRequestResource($serviceRequest->load(['service', 'laboratory']));
    }

    public function respondToQuotation(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        if ($serviceRequest->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($serviceRequest->quotation_status !== 'quoted') {
            throw ValidationException::withMessages(['quotation_status' => ['There is no pending quotation to respond to.']]);
        }

        $data = $request->validate([
            'response' => ['required', Rule::in(['accepted', 'declined'])],
        ]);

        $serviceRequest->respondToQuotation($data['response'] === 'accepted', $request->user());

        return response()->json(['data' => new ServiceRequestResource($serviceRequest->fresh())]);
    }

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
