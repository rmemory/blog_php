@extends ('layouts.master')

@section('content')

  <div class="col-sm-8 blog-main">
      <h1>Register</h1>

      <form class="" action="/register" method="post">
        {{ csrf_field() }}

        <div class="form-group">
          <label form="name">Name:</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label form="email">Email:</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label form="password">Password:</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
          <label form="password_confirmation">Password Confirmation:</label>
          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>

        <div class="form-group">
          @include ('layouts.errors')
        </div>
      </form>
  </div>

@endsection
