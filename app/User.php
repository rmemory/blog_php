<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts() {
      return $this->hasMany(Post::class);
    }

    public function publish($post) {
      $this->posts()->save($post);

      /*
        The above does this and can do it due to posts() method above
        and its hasMany association.

        Post::create(
          'title' => request('title'),
          'body' => request('body'),
          'user_id' => auth()->user()->id);

        Note that "save" operation used 
        above also automatically sets the user_id, and it is accordingly
        called from the PostsController, like this:

        auth()->user()->publish(
          new Post(request('title', 'body'))
        );
      */
    }
}
