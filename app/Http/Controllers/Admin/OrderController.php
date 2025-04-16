<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['course', 'student', 'payments'])
            ->withSum('payments', 'amount')
            ->when($request->input('search'), function ($query) use ($request) {
                $query->whereHas('course', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('description', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('external_id', 'like', '%' . $request->input('search') . '%');
                })->orWhereHas('student', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('last_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('email', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('document', 'like', '%' . $request->input('search') . '%');
                });
            })
            ->when($request->input('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->input('sort_by'), function ($query) use ($request) {
                $query->orderBy($request->input('sort_by'), $request->input('sort_direction', 'asc'));
            }, function ($query) {
                $query->latest();
            })
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }
}
