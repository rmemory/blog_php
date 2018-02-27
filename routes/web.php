<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where web routes (paths) for your application are registered.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
| Note that views can be returned here, but typically we delegate to
| controllers. Here are some examples of return a view to welcome.blade.php.

The following examples all use anonymous functions called "route closures" to
directly return a view, but as we shall see below, a better idea is to
use controllers to return the view.

Route::get('/', function() {
  return view('welcome');
});

Route::get('/', function() {
  return view('welcome', [
    'name' => 'Some Name'
  ]);
});

Route::get('/', function() {
  return view('welcome', [
    'name' => 'Some Name',
    'age' => 30
  ]);
});

Route::get('/', function() {
  return view('welcome')->with('name', 'World');
});

Route::get('/', function() {
  $name = 'MyName';
  $age = 31;

  return view('welcome', compact('name', 'age'));
});

Route::get('/', function() {
  $tasks = [
    'Make bed',
    'Brush teeth',
    'Shower'
];

  return view('welcome', compact('tasks'));
});

In the view, the non-blade way of doing a loop (or conditional) looks like
This

<ul>
  <?php foreach ($tasks as $task) : ?>
    <li><?=$task; ?></li>
  <?php endforeach; ?>
</ul>

The blade way of doing the same is:

<ul>
  @foreach ($tasks as $task)
    <li>{{$task}}</li>
  @endforeach
</ul>

@if ()
@endif

@while()
@endwhile

Now its time to introduce databases to access the data rather than
hardcoding it.

Create the database manually

$ mysql -u root -p
mysql> create database myproject;
mysql> use myproject;

Then, edit the project's .env file:

DB_CONNECTION=mysql,
DB_DATABASE=myproject,
DB_USERNAME=root,
DB_PASSWORD=

You can then use these commands to create the migration file and table in
the database:

$ php artisan make:migration create_tasks_table --create=tasks\
$ php artisan migrate

You can add fields to the table in the migration table like this:

$table->increments('id');
$table->text('body');
$table->boolean('complete')->default(false);

And create those fields with this:

$ php artisan migrate:refresh

Sometimes you'll need to use $composer dump-autoload to get around
missing file exceptions.

We can manually enter a couple of tasks like this:

mysql> insert into tasks (body, created_at, updated_at) values ("go to the store", NOW(), NOW());
mysql> insert into tasks (body, created_at, updated_at) values ("finish project", NOW(), NOW());

And here is the way to use laravel's query builder to return all of the
tasks in the tasks table in the database:

Route::get('/', function () {
    // Get all data from the "tasks" table.
    $tasks = DB::table('tasks')->get();

    // If you return a var from a route, laravel just casts it to json
    return $tasks;
});

Which looks like this in the browser
[
{
"id": 1,
"body": "go to the store",
"created_at": "2018-02-21 16:43:44",
"updated_at": "2018-02-21 16:43:44"
},
{
"id": 2,
"body": "finish project",
"created_at": "2018-02-21 16:44:09",
"updated_at": "2018-02-21 16:44:09"
}
]

Or, we can pass the tasks to the welcome view, and display it, assuming the
welcome.blade.php has the proper loop:

Route::get('/', function () {
  // I can also insert conditions before the call to get()
  // like where('created_at', ">=")
  $tasks = DB::table('tasks')->where('created_at', ">=", 'some value')->get();

  // Or this one returns latest
  $tasks = DB::table('tasks')->latest()->get();

  return view('welcome', compact('tasks'));
});

Here is a slight adjustment in the loop to look for the 'body'

<ul>
  @foreach ($tasks as $task)
    <li>{{$task->body}}</li>
  @endforeach
</ul>

Here we look for an ID in the path, and look for that ID in the database,

Route::get('/tasks/{id}', function ($id) {
    // The query builder way
    //$task = DB::table('tasks')->find($id);

    // The eloquent way (The "active record" implementation)
    $task = App\Task::find($id);

    // This looks for a view called tasks/show.blade.php
    return view('tasks.show', compact('task'));
});

In the above example, the HTML associated regular GET ''/tasks' route could create
simple links to indivdual tasks like this:

<ul>
  @foreach ($tasks as $task)
    <li>
      <a href="tasks/{{$task->id}}">{{$task->body}}</a>
    </li>
  @endforeach
</ul>

In order to use Eloquent, we first have to create model classes, like this:

$ php artisan make:model Task

Or we cold even do this to also create the migration with it:

$ php artisan make:model Task -m

Or to make both the migration and controller we could do this:

$ php artisan make:model Task -mc

But, it might be best to make the migration seperately, in order to
create all of the default "resourceful" controller APIs like this:

$ php artisan make:controller TasksController -r

In any case, we can interact directly with classes derived from the Eloquent
model using php's tinker shell:

$ php artisan tinker
>>> App\Task::all()
=> Illuminate\Database\Eloquent\Collection {#756
     all: [
       App\Task {#757
         id: 1,
         body: "go to the store",
         created_at: "2018-02-21 16:43:44",
         updated_at: "2018-02-21 16:43:44",
       },
       App\Task {#758
         id: 2,
         body: "finish project",
         created_at: "2018-02-21 16:44:09",
         updated_at: "2018-02-21 16:44:09",
       },
     ],
   }

   or

   App\Task::where('id', '>', 2)->get();
   App\Task::pluck('body');
   App\Task::pluck('body')->first();

  Inside of our model classes (e.g. php artisan make:model Task), we can
  create our "business logic" and even helper functions.

  such as the following:

   public function isComplete() {
    //return whether a Task is complete
   }

  The above method (of a class Task) could only be called if a Task class
  had already been created with new.

  We could instead declare it as static, where it would have access to
  static members of the Model parent:

   public static function incomplete() {
    //return Task::where('completed', 0)->get();
    return static::where('completed', 0)->get();
   }

  It allows this: Task::incomplete(), but when a static model method is used,
  it couldn't be chained like this:
    Task::incomplete()->first() is not allowed.

  Alternatively, we can even use what are called "query scopes", like this:

    public function scopeIncomplete($query, $optionalArg1, $optionArg2, etc) {
      return $query->where('completed', 0);
    }

  That can be chained, like this: App\Task::incomplete()->get()->first()

  By the way, when you have a Task object, it can be "saved" which pushes
  all values to the database.

    $task->save();

  But, we really need to do queries in the controllers. It receives a request,
  such as a GET, POST, etc, and asks the model to get the data it needs, and
  then delegates to the view to present it.

   Controllers are found in the App/Http/Controllers directory.

   $ php artisan make:controller TasksController is the basic way to do it. But,
   recall that using the -r command also generates the default REST APIs.

   The controller repaces all of the anonymous route closure functions used
   above.
*/

// In the following, PostsController is the class, index is the method in that
// class."index" is the naming standard for controllers and views responsible
// for displaying all of a particular topic.

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
Route::get('/', 'PostsController@index');

/*
  Display the page to create a post (hence the get and not a post)
*/
Route::get('/posts/create', 'PostsController@create');

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

  Note that in the wildcard spot, it matters that we call it
  {post}. This allows "route-model" binding. And it matters the
  argument to the "show method" is also Post $post, and not something
  like Post $thePost. And if you have a Post in your database with
  the key of {post} (for example, a simple integer id like /posts/3,
  the laravel will pass that Post object to the show method. Basically,
  laravel does a Post::find(whatever the wildcard is), and it passes it
  to the show method.
*/

Route::get('/posts/{post}', 'PostsController@show');

/*
  Store a post in the database
*/
Route::post('/posts', 'PostsController@store');

  /*
  Basic Resourceful Controller. The combination of the HTTP request
  method and url path are how we decide which controller is used,
  which in turn uses that information to determine what is shown.

  The stubs can be created by using:

  $ php artisan make:controller TasksController -r

  GET /posts is a method and path used to get (display) all Posts

  GET /posts/create is a method and path used to display a view which allows
  a user to create a post.

  POST /posts is a method and path used to when the user presses the "publish"
  button to store a post in the database.

  GET /posts/{id}/edit is a method and url which displays a view for a user to
  edit a particular post

  GET /posts/{id} is a method and url which displays a view to allow the user
  to view a particular post

  PATCH /posts/{id} is a method and url which is passed back to the server after
  the user has edited a particular post

  DELETE /posts/{id} is used to delete a post.

  The combination of request type (method) and path determines what action
  to take.
  */
