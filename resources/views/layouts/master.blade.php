

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Blog Example using Laravel and PHP</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="/css/app.css" rel="stylesheet">

  </head>

  <body>
    @include ('layouts.nav')

    @if ($flash = session('message'))
    <div id="flash-message" class="alert alert-success" role="alert">
      {{ $flash }}
    </div>
    @endif

    <div class="container">

      <div class="blog-header">
        <h1 class="blog-title">A blog example using Laravel and PHP</h1>
        <p class="lead blog-description">An example of creating a blog using Laravel, PHP, and Bootstrap</p>
      </div>

      <div class="row">
        @yield ('content')

        @include ('layouts.sidebar')

      </div><!-- /.row -->

    </div><!-- /.container -->

    @include ('layouts.footer')
</body>>
</html>
