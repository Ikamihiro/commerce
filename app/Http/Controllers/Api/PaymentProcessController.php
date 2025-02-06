<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Student;
use App\Services\Payment\ProcessCardPayment;
use App\Services\Payment\ProcesserContract;
use App\Services\Payment\ProcessPixPayment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentProcessController extends Controller
{
    public function __invoke(Request $request)
    {
        DB::beginTransaction();

        try {
            $course = Course::findOrFail($request->input('course_id'));

            $paymentMethod = $request->input('payment_method_id');

            $processer = match ($paymentMethod) {
                'pix' => ProcessPixPayment::class,
                default => ProcessCardPayment::class,
            };

            throw new \Exception('Invalid payment method');

            /** @var ProcesserContract $processer */
            $payment = (new $processer())->process([
                ...$request->all(),
                'amount' => $course->price,
                'description' => $course->name,
            ]);

            $student = Student::updateOrCreate([
                'document' => $request->input('payer.identification.number'),
            ], [
                'first_name' => $request->input('payer.first_name'),
                'last_name' => $request->input('payer.last_name'),
                'email' => $request->input('payer.email'),
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
                'type' => $payment['type'],
            ]);

            $result = [
                'status' => $payment['status'],
                'transaction_amount' => $payment['transaction_amount'],
                'payer_name' => $student->first_name . ' ' . $student->last_name,
            ];

            if ($paymentMethod === 'pix') {
                $result['point_of_interaction'] = $payment['point_of_interaction'];
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment processed successfully',
                'data' => $result,
            ], 200);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'data' => [
                    'status' => 'failed',
                ]
            ], 500);
        }
    }
}
