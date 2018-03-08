<?php

namespace App\Http\Controllers;

use App\User;

class RegistrationController extends Controller
{
    public function create() {
      return view('registrations.create');
    }

    public function store() {
      // Validate incoming data
      $this->validate(request(), [
        'name' => 'required',
        'email' => 'required|email',
        /*
          Note the "confirmed" validation requires an associated
          _confirmation input form
        */
        'password' => 'required|confirmed'
      ]);

      // Create and save the user
      $user = User::create(request(['name', 'email', 'password']));

      // Sign the user in
      auth()->login($user);

      // Redirect back to home page
      return redirect()->home();

    }
}
