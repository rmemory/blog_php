<?php

namespace App;

class Comment extends Model {

    /*
      Allows us to get the post that these comments are
      associated with.

      $comment->post;
    */
    public function post() {
      return $this->belongsTo(Post::class);
    }

    // $comment->user->name
    public function user() {
      return $this->belongsTo(User::class);
    }
}
