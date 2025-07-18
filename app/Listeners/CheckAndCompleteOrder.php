<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Jobs\ProcessStudentEnrollment;
use App\Mail\OrderApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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

        // Check if the order is already completed
        if ($order->status === 'completed') {
            return;
        }

        // Dispatch job to process student enrollment
        ProcessStudentEnrollment::dispatch(
            $order->student,
            $order->course,
        );

        // Send email notification to the student
        Mail::to($order->student->email)
            ->send(new OrderApproved(
                $order->course,
                $order->student,
            ));

        $order->update([
            'status' => 'completed',
        ]);
    }
}
