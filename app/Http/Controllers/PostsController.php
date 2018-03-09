<?php

namespace App\Http\Controllers;


use App\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{

  public function __construct() {
    // You must be signed in to create a post
    $this->middleware('auth')->except(['index', 'show']);
  }

  /*
    Show all of the Posts

    The index method of the PostsController class might look like this,
    which in this case is using Eloquent to access the database.

    use App\Post;

    public function index() {
      $posts = Post::all();
      return view('posts.index', compact('posts'));
    }

    In that controller function, we need to handle the request (GET in this case),
    gets whatever data from the database via the model and Eloquent, and performs
    a save (migration)
  */
    public function index() {
      // ordered newest to oldest
      // $posts = Post::latest()->get();
      // Here is the long way
      // $posts = Post::orderBy('created_at', 'desc')->get();
      $posts = Post::orderBy('created_at', 'desc');

      // Example: http://blog.local/?month=March&year=2018
      /*
        Note at the bottom of app/config/app.php I added this:

        'Carbon' => 'Carbon\Carbon',
      */
      if ($month = request('month')) {
        $posts->whereMonth('created_at', \Carbon::parse($month)->month); // Carbon used to translate from March -> 3, etc
      }

      if ($year = request('year')) {
        $posts->whereYear('created_at', $year);
      }

      $posts = $posts->get();

      /*
        Instead of the above, I could use a query scope.

        $posts = Post::latest()->filter(request(['month', 'year']))->get();

        See Posts model class for the scopeFilter query scope method.
      */

      // do the query for archives
      /*
      // Temporary
      $archives = Post::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) as published')
        ->groupBy('year', 'month')
        ->orderByRaw('min(created_at) desc')
        ->get()
        ->toArray();
      */
      $archives = Post::archives();

      return view('posts.index', compact('posts', 'archives'));
    }

    /*
      Display the page used to create a post
    */
    public function create() {
      return view('posts.create');
    }

    /*
      Show one particular Post

      use App\Post;

      public function show($id) {
        // The query builder way
        // $post = DB::table('posts')->find($id);

        // The eloquent way (The "active record" implementation)
        $post = App\Post::find($id);

        // This looks for a view called tasks/show.blade.php
        return view('posts.show', compact('post'));
      }

      Note that in the wildcard spot of the path, it matters that we call it
      {post}. This allows "route-model" binding. And it matters the
      argument to the "show method" is also Post $post, and not something
      like Post $thePost. And if you have a Post in your database with
      the key of {post} (for example, a simple integer id like /posts/3,
      the laravel will pass that Post object to the show method. Basically,
      laravel does a Post::find(whatever the wildcard is), and it passes it
      to the show method.
    */
    public function show(Post $post) {
      // return dd(var_dump($post));
      return view('posts.show', compact('post'));
      // return view("posts.show");
    }

    public function store() {

      /*
        request()->all() contains all of the request data. For example,

        array:3 [▼
          "_token" => "uRPe7sgtKhqLUrJUkC7VpuIs4pO5UCG4p8N5BorP"
          "title" => "My title"
          "body" => "The body"
        ]

        The token above comes from csrf_field()

        request('title') gives just the title
        request('body') gives just the body

        request(['title', 'body']) gives both title and body
      */

      // validate the incoming data
      $this->validate(request(), [
        'title' => 'required|max:30',
        'body' => 'required'
      ]);

      // Create a new post using the request data
      $post = new Post;

      $post->title = request('title');
      $post->body = request('body');
      $post->user_id = auth()->user()->id;
        /*
        Because each model object is extended from Eloquent,
        it also provides a create method, which will do the
        same thing as above. Like this:

        Post::create({
          'title' => request('title'),
          'body' => request('body')
        });

        This would allow us to not need to new a Post object as above.
        */

        /*
        Note that Laravel protects against passing request()->all() to create()
        without any validation, and will throw a MassAssignmentException
        where we haven't validated any information, allowing a
        malicious user to submit dangerous data through our form.

        Users in a browser can change inputs or add inputs (which allows
        them to pass unintended, potentially dangerous data). Stated
        differently, just because your web page says "these are the forms
        you can submit", does not imply the user can't modify the form
        on his end, changing or adding inputs. Thus, laravel protects against
        this by mandating requests be validated first.

        For example, we don't want users to change an email address when
        you never want to allow that. Or changing a billing id,  or a
        membership status is changed.

        One quick way to avoid the MassAssignmentException is to modify the
        model class (not the controller!!!) to have the following field:

        protected $fillable = ['title', 'body'];

        The fillable fields indicate only those can be mass filled. This is a
        white list.

        The inverse is the "guarded" field, which won't allow those ids

        protected $guarded = [ 'user_id'];

        This is a black list.

        Note that the guarded array may be empty, meaning allow everything,
        which is risky unless the data is validated, but nevertheless this is
        allowed. Meaning, you could pass request()->all(), which could include
        literally anything, including database fields you don't want to allow
        to be changed.

        Post::create([
          'title' => request('title'),
          'body' => request('body')
        ]);

        or just this:

        Post::create([request('title', 'body')]);

        Note that using HTML 5 validation when possible is also good. For example,

        <input type="text" class="form-control" id="title" name="title" required>
      */

      // Save it to the Database
      $post->save();

      // And then redirect to a web page, for example home page
      return redirect('/');
    }
}
