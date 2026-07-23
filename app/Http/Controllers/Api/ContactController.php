<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;  
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data'    => $contacts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:20'],
            'message' => ['required', 'string'],
        ]);

        Contact::create($validated);
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. We will contact you soon.',
            ]);
        }
        return redirect()->route('home')->with('success', 'Your message has been sent successfully.');
    }


    /**
     * Display the specified resource.
     */
  public function show(string $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['success' => false, 'message' => 'Contact not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $contact]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
