<?php

namespace App\Http\Controllers;

use App\User;

class RegistrationController extends Controller
{
    public function create() {
      return view('registration.create');
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

      // Send a welcome email
      /*
        First create the email class by running php artisan make:mail Welcome

        In config/mail.php, you can see all of the available mail drivers such
        as sendmail, mailgun, sparkpost, etc. Also see the .env file, where
        the MAIL_* vars are declared
      */
      \Mail::ti($user)->send(new \App\Mail\Welcome($user));

      // Redirect back to home page
      return redirect()->home();

    }
}
