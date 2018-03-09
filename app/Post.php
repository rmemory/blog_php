<?php

namespace App;

use App\Comment;
use Carbon\Carbon;

/*
 Here is an example of a post SQL query to obtain the number of posts for a given
 year and month.

 select
	year(created_at) as year,
	monthname(created_at) as month,
	count(*) as published
from posts
group by year, month;

Here is what it looks like in Eloquent:

App\Post::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) as published')->groupBy('year', 'month')->get()->toArray();
*/

class Post extends Model {
  /*
    Allows us to get the comments associcated with this post

    $post->comments;

   $ php artisan tinker
   >>> $post = App\Post::find(6);
=> App\Post {#759
     id: 6,
     title: "a sixth post",
     body: "blah blah blah blah blah",
     created_at: "2018-03-07 18:16:05",
     updated_at: "2018-03-07 18:16:05",
   }
>>> $post->comments
=> Illuminate\Database\Eloquent\Collection {#753
     all: [
       App\Comment {#758
         id: 1,
         post_id: 6,
         body: "this is a great post",
         created_at: "2018-03-07 18:20:30",
         updated_at: "2018-03-07 18:20:30",
       },
       App\Comment {#755
         id: 2,
         post_id: 6,
         body: "I fully agree",
         created_at: "2018-03-07 18:20:54",
         updated_at: "2018-03-07 18:20:54",
       },
     ],
   }

  */
  public function comments() {
    return $this->hasMany(Comment::class);
  }

  // $comment->post->user
  public function user() {
    return $this->belongsTo(User::class);
  }

  public function addComment($body) {
    /*
      The long way
    Comment::create([
      'body' => request('body'),
      'post_id' => $this->id
    ]);
    */

    // Shorter way using eloquent
    $this->comments()->create(['body' => $body]);
  }

  public function scopeFilter($query, $filters) {
    if ($month = $filters['month']) {
      $query->whereMonth('created_at', Carbon::parse($month)->month); // Carbon used to translate from March -> 3, etc
    }

    if ($year = $filters['year']) {
      $query->whereYear('created_at', $year);
    }
  }
}
