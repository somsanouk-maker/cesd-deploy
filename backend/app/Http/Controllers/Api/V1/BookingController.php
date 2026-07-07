<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BookingResource;
use App\Models\Booking;
use App\Models\Equipment;
use App\Models\Laboratory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function mine(Request $request): AnonymousResourceCollection
    {
        return BookingResource::collection(
            $request->user()->bookings()->latest('start_at')->get()
        );
    }

    public function availability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'bookable_type' => ['required', Rule::in(['equipment', 'laboratory'])],
            'bookable_id' => ['required', 'integer'],
        ]);

        $slots = Booking::query()
            ->where('bookable_type', $data['bookable_type'])
            ->where('bookable_id', $data['bookable_id'])
            ->whereIn('status', ['pending_advisor', 'pending_staff', 'approved'])
            ->get(['start_at', 'end_at'])
            ->map(fn (Booking $booking) => [
                'start_at' => $booking->start_at->toIso8601String(),
                'end_at' => $booking->end_at->toIso8601String(),
            ]);

        return response()->json(['data' => $slots]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'bookable_type' => ['required', Rule::in(['equipment', 'laboratory'])],
            'bookable_id' => ['required', 'integer'],
            'purpose' => ['required', 'string', 'max:2000'],
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['required', 'date', 'after:start_at'],
        ]);

        $model = $data['bookable_type'] === 'equipment' ? Equipment::class : Laboratory::class;
        $bookable = $model::find($data['bookable_id']);

        if (! $bookable) {
            throw ValidationException::withMessages(['bookable_id' => ['The selected item was not found.']]);
        }

        $user = $request->user();
        $overlap = Booking::query()
            ->where('bookable_type', $data['bookable_type'])
            ->where('bookable_id', $data['bookable_id'])
            ->whereIn('status', ['pending_advisor', 'pending_staff', 'approved'])
            ->where('start_at', '<', $data['end_at'])
            ->where('end_at', '>', $data['start_at'])
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages(['start_at' => ['This time slot is already booked or pending approval.']]);
        }

        $requiresAdvisorApproval = $user->hasRole('student');

        $booking = DB::transaction(function () use ($data, $user, $requiresAdvisorApproval) {
            $year = now()->year;
            $count = Booking::whereYear('created_at', $year)->lockForUpdate()->count();

            return Booking::create([
                'booking_no' => sprintf('BK-%d-%04d', $year, $count + 1),
                'user_id' => $user->id,
                'bookable_type' => $data['bookable_type'],
                'bookable_id' => $data['bookable_id'],
                'purpose' => $data['purpose'],
                'start_at' => $data['start_at'],
                'end_at' => $data['end_at'],
                'status' => $requiresAdvisorApproval ? 'pending_advisor' : 'pending_staff',
                'requires_advisor_approval' => $requiresAdvisorApproval,
            ]);
        });

        return response()->json(['data' => new BookingResource($booking)], 201);
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            abort(403);
        }

        if (in_array($booking->status, ['rejected', 'cancelled'], true)) {
            throw ValidationException::withMessages(['status' => ['This booking can no longer be cancelled.']]);
        }

        $booking->cancel();

        return response()->json(['data' => new BookingResource($booking)]);
    }
}
