<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Mail::to(config('mail.from.address'))
            ->send(new \App\Mail\SomeoneContacted(
                name: $request->input('name'),
                email: $request->input('email'),
                messageSubject: $request->input('subject'),
                contactMessage: $request->input('message')
            ));

        return redirect()->route('contact.index');
    }
}
