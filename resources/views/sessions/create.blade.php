@extends ('layouts.master')
@section('content')
<div class="col-sm-8 blog-main">
    <h1>Sign in</h1>

    <form class="" action="/login" method="post">
      {{ csrf_field() }}

      <div class="form-group">
        <label form="email">Email Address:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label form="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">Sign In</button>
      </div>

      <div class="form-group">
        @include ('layouts.errors')
      </div>
    </form>
</div>
@endsection
