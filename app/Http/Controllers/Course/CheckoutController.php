<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CheckoutController extends Controller
{
    public function index(Course $course)
    {
        if (config('app.payment.gateway') !== 'asaas') {
            return view('checkout.mercadopago', compact('course'));
        }

        return view('checkout.asaas', compact('course'));
    }

    public function cancel(Course $course)
    {
        return view('checkout.cancel', compact('course'));
    }

    public function expired(Course $course)
    {
        return view('checkout.expired', compact('course'));
    }

    public function success(Course $course)
    {
        return view('checkout.success', compact('course'));
    }
}
