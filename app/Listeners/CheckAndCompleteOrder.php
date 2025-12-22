<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Jobs\ProcessStudentEnrollment;
use App\Mail\OrderApproved;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
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
        $payment = $event->payment;

        // Refresh the payment to get the latest state
        $payment->refresh();

        Log::info('Handling PaymentConfirmed event', [
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'student_id' => $order->student_id,
            'course_id' => $order->course_id,
            'order_status' => $order->status,
            'payment_status' => $payment->status,
            'payment_processed_at' => $payment->processed_at,
        ]);

        // Idempotency check: if payment is already processed, skip
        if ($payment->processed_at !== null) {
            Log::info('Payment already processed, skipping', [
                'payment_id' => $payment->id,
                'processed_at' => $payment->processed_at,
            ]);
            return;
        }

        // Use database transaction with pessimistic locking to prevent race conditions
        DB::transaction(function () use ($order, $payment) {
            // Lock the order for update to prevent concurrent processing
            $lockedOrder = Order::where('id', $order->id)
                ->lockForUpdate()
                ->with(['course', 'student'])
                ->first();

            // Lock the payment for update
            $lockedPayment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->first();

            // Double-check after acquiring lock
            if ($lockedPayment->processed_at !== null) {
                Log::info('Payment already processed (after lock), skipping', [
                    'payment_id' => $lockedPayment->id,
                    'processed_at' => $lockedPayment->processed_at,
                ]);
                return;
            }

            // Check if the order is already completed
            if ($lockedOrder->status === 'completed') {
                Log::info('Order already completed, marking payment as processed', [
                    'order_id' => $lockedOrder->id,
                ]);

                // Mark payment as processed even if order was already completed
                $lockedPayment->update([
                    'processed_at' => now(),
                ]);

                return;
            }

            // Mark payment as processed FIRST to prevent any retry/duplicate
            $lockedPayment->update([
                'processed_at' => now(),
            ]);

            Log::info('Payment marked as processed', [
                'payment_id' => $lockedPayment->id,
                'processed_at' => $lockedPayment->processed_at,
            ]);

            if ($lockedOrder->course->modality === 'online') {
                // Dispatch job to process student enrollment
                ProcessStudentEnrollment::dispatch(
                    $lockedOrder->student,
                    $lockedOrder->course,
                );

                Log::info('Student enrollment processing dispatched', [
                    'student_id' => $lockedOrder->student_id,
                    'course_id' => $lockedOrder->course_id,
                ]);
            }

            // Send email notification to the student
            Mail::to($lockedOrder->student->email)
                ->send(new OrderApproved(
                    $lockedOrder->course,
                    $lockedOrder->student,
                ));

            Log::info('Order approval email sent', [
                'student_email' => $lockedOrder->student->email,
                'course_id' => $lockedOrder->course_id,
            ]);

            $lockedOrder->update([
                'status' => 'completed',
            ]);

            Log::info('Order status updated to completed', [
                'order_id' => $lockedOrder->id,
            ]);
        });
    }
}
