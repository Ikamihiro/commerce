<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Jobs\ProcessStudentEnrollment;
use App\Mail\OrderApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckAndCompleteOrder
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentConfirmed $event): void
    {
        $order = $event->order;

        $order->load(['course']);

        Log::info('Handling PaymentConfirmed event', [
            'order_id' => $order->id,
            'student_id' => $order->student_id,
            'course_id' => $order->course_id,
            'status' => $order->status,
        ]);

        // Check if the order is already completed
        if ($order->status === 'completed') {
            Log::info('Order already completed', [
                'order_id' => $order->id,
            ]);
            return;
        }

        if ($order->course->modality === 'online') {
            // Dispatch job to process student enrollment
            ProcessStudentEnrollment::dispatch(
                $order->student,
                $order->course,
            );

            Log::info('Student enrollment processing dispatched', [
                'student_id' => $order->student_id,
                'course_id' => $order->course_id,
            ]);
        }

        // Send email notification to the student
        Mail::to($order->student->email)
            ->send(new OrderApproved(
                $order->course,
                $order->student,
            ));

        Log::info('Order approval email sent', [
            'student_email' => $order->student->email,
            'course_id' => $order->course_id,
        ]);

        $order->update([
            'status' => 'completed',
        ]);

        Log::info('Order status updated to completed', [
            'order_id' => $order->id,
        ]);
    }
}
