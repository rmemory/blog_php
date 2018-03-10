<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Yes anyone is authorized to make a request because its a
        // registration in this case.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'name' => 'required',
          'email' => 'required|email',
          /*
            Note the "confirmed" validation requires an associated
            _confirmation input form
          */
          'password' => 'required|confirmed'
        ];
    }

    public function persist() {
      // request()->only([some array]) == request([some array])
      $name = $this->name;
      $email = $this->email;
      $password = $this->password;
      $password = Hash::make($password);

      // Create and save the user
      $user = User::create(compact('name', 'email', 'password'));

      // Sign the user in
      auth()->login($user);
    }
}
