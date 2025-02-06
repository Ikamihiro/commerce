<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Student;
use App\Services\Payment\ProcessCardPayment;
use App\Services\Payment\ProcesserContract;
use App\Services\Payment\ProcessPixPayment;
use Illuminate\Http\Request;

class PaymentProcessController extends Controller
{
    public function __invoke(Request $request)
    {
        $course = Course::findOrFail($request->input('course_id'));

        $paymentMethod = $request->input('payment_method_id');

        $processer = match ($paymentMethod) {
            'pix' => ProcessPixPayment::class,
            default => ProcessCardPayment::class,
        };

        /** @var ProcesserContract $processer */
        $payment = (new $processer())->process([
            ...$request->all(),
            'amount' => $course->price,
            'description' => $course->name,
        ]);

        $student = Student::updateOrCreate([
            'document' => $request->input('document'),
        ], [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ]);

        /** @var Order $order */
        $order = Order::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        $order->payments()->create([
            'external_id' => $payment['id'],
            'status' => $payment['status'],
            'amount' => $course->price,
            'installments' => $payment['installments'] ?? 1,
            'data' => $payment,
        ]);

        $result = [
            'status' => $payment['status'],
            'transaction_amount' => $payment['amount'],
            'payer_name' => $student->first_name . ' ' . $student->last_name,
        ];

        if ($paymentMethod === 'pix') {
            $result['point_of_interaction'] = $payment['point_of_interaction'];
        }

        return response()->json([
            'message' => 'Payment processed successfully',
            'data' => $result,
        ], 200);
    }
}
