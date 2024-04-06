<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:contacts',
            'description' => 'required',
        ]);

        $contact = Contact::create($request->all());

        return response()->json(['message' => 'Contact saved successfully', 'contact' => $contact], 201);
    }
}
