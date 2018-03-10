<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SessionsController extends Controller
{

  public function __construct() {
    /*
      The following means only non-logged in users (guests) will use the
      functionality in this class, except the destroy method
    */
    $this->middleware('guest', ['except' => 'destroy']);

  }

  public function create() {
    return view('sessions.create');
  }

  public function store() {
    // Attempt to authenticate user
    $email = request('email');
    $password = request('password');

    if (!auth()->attempt(compact('email', 'password'))) {
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
