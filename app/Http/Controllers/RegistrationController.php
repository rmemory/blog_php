<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    public function create() {
      if (auth()->check()) {
        return redirect()->home();
      } else {
        return view('registration.create');
      }
    }

    public function store(RegistrationRequest $request) {
      // Validate incoming data
      /*
      Move this to the Requests/RegistrationRequest.php, rules() file and method
      */
      // $this->validate(request(), [
      //   'name' => 'required',
      //   'email' => 'required|email',
      //   /*
      //     Note the "confirmed" validation requires an associated
      //     _confirmation input form
      //   */
      //   'password' => 'required|confirmed'
      // ]);

      /*
        Nothing below this point will execute unless the validation
        in the RegistrationRequest.rules() passes
      */

      // Move to RegistrationRequest's persist function
      // $name = request('name');
      // $email = request('email');
      // $password = request('password');
      // $password = Hash::make($password);
      //
      // // Create and save the user
      // $user = User::create(compact('name', 'email', 'password'));
      //
      // // Sign the user in
      // auth()->login($user);

      $request->persist();

      // Send a welcome email
      /*
        First create the email class by running php artisan make:mail Welcome

        In config/mail.php, you can see all of the available mail drivers such
        as sendmail, mailgun, sparkpost, etc. Also see the .env file, where
        the MAIL_* vars are declared.

        To create a markdown email, instead do this:

        php artisan make:mail WelcomeAgain --markdown="emails.welcome-again",
        which not only creates the App/Mail/WelcomeAgain.php file, but also the
        resources/views/emails/welcom-again.blade.php file as well.

        To test, you can use Tinker:

        php artisan tinker

        Mail::to($user = App\User::first()->send(new App\Mail\WelcomeAgain($user)));
      */
      // \Mail::ti($user)->send(new \App\Mail\Welcome($user));

      // Redirect back to home page
      return redirect()->home();

    }
}
