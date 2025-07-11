<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Student;
use App\Services\Payment\Asaas\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasCheckoutController extends Controller
{
    public function __invoke(Request $request, Course $course)
    {
        if (config('app.payment.gateway') !== 'asaas') {
            return redirect()->back();
        }

        $request->validate([
            'student.first_name' => 'required|string|max:255',
            'student.last_name' => 'required|string|max:255',
            'student.email' => 'required|email|max:255',
            'student.document_type' => 'required|string|max:14',
            'student.document_number' => 'required|string|max:14',
            'student.phone' => 'required|string|max:15',
            'student.address' => 'nullable|string|max:255',
            'student.address_number' => 'nullable|string|max:20',
            'student.address_complement' => 'nullable|string|max:50',
            'student.city' => 'nullable|string|max:100',
            'student.state' => 'nullable|string|max:2',
            'student.postal_code' => 'nullable|string|max:10',
        ]);

        DB::beginTransaction();

        try {
            /**
             * @var Student $student
             */
            $student = $this->retrieveOrCreateStudent($request->input('student'));

            $checkout = new Checkout($course, $student);
            $response = $checkout->create();

            /** @var Order $order */
            Order::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => 'pending',
                'checkout_id' => $response['id'] ?? null,
            ]);

            DB::commit();

            return redirect($response['url']);
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Checkout creation failed', [
                'course_id' => $course->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }

        return redirect()
            ->back()
            ->withErrors(['error' => 'Erro ao criar checkout.']);
    }

    private function retrieveOrCreateStudent(array $studentData): Student
    {
        $documentNumber = preg_replace('/\D/', '', $studentData['document_number']);
        $postalCode = preg_replace('/\D/', '', $studentData['postal_code'] ?? '');
        $phone = preg_replace('/\D/', '', $studentData['phone'] ?? '');

        /**
         * @var Student $student
         */
        $student = Student::updateOrCreate([
            'document' => $documentNumber,
        ], [
            'first_name' => $studentData['first_name'],
            'last_name' => $studentData['last_name'],
            'email' => $studentData['email'],
            'phone' => $phone,
            'password' => 'Mudar@12345',
            'address' => $studentData['address'] ?? null,
            'address_number' => $studentData['address_number'] ?? null,
            'address_complement' => $studentData['address_complement'] ?? null,
            'postal_code' => $postalCode ?: null,
            'city' => $studentData['city'] ?? null,
            'state' => $studentData['state'] ?? null,
        ]);

        if (!is_null($student->external_id)) {
            Log::info('Student already exists in Asaas', [
                'student_id' => $student->id,
                'external_id' => $student->external_id,
            ]);

            /**
             * @var \Illuminate\Http\Client\Response $response
             */
            $response = Http::asaas()
                ->put('customers/' . $student->external_id, [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'cpfCnpj' => $student->document,
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'externalReference' => $student->id,
                    'postalCode' => $student->postal_code,
                    'addressNumber' => $student->address_number,
                ]);

            if ($response->failed()) {
                Log::error('Failed to update student in Asaas', [
                    'student_id' => $student->id,
                    'error' => $response->json(),
                ]);
                throw new \Exception('Failed to update student in Asaas: ' . $response->json());
            }

            return $student;
        }

        /**
         * @var \Illuminate\Http\Client\Response $response
         */
        $response = Http::asaas()
            ->post('customers', [
                'name' => $student->first_name . ' ' . $student->last_name,
                'cpfCnpj' => $student->document,
                'email' => $student->email,
                'phone' => $student->phone,
                'externalReference' => $student->id,
                'postalCode' => $student->postal_code,
                'addressNumber' => $student->address_number,
            ]);

        if ($response->failed()) {
            Log::error('Failed to create or retrieve student in Asaas', [
                'student_id' => $student->id,
                'error' => $response->json(),
            ]);
            throw new \Exception('Failed to create or retrieve student in Asaas: ' . $response->json());
        }

        $asaasData = $response->json();
        $student->update([
            'external_id' => $asaasData['id'],
        ]);

        return $student;
    }
}
