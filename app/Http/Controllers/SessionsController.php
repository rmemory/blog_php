<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{

  public function __construct() {
    /*
      This means only non-logged in users (guests) will use the
      functionality in this class.
    */
    $this->middleware('guest', ['except' => 'destroy']);

  }

  public function create() {
    return view('sessions.create');
  }

  public function store() {
    // Attempt to authenticate user
    if (!auth()->attempt(request(['email', 'password']))) {
      return back()->withErrors([
        'message' => 'Please check your credentials and try again'
      ]);
    }

    // Redirect to home or back to login page
    return redirect()->home();
  }

  public function destroy() {
    auth()->logout();

    return redirect()->home();
  }
}
