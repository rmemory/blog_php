<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;

class CommentsController extends Controller
{
    public function store(Post $post) {
      // Add a comment to a post
      $this->validate(request(), ['body' => 'required|min:2']);
      /* This is the long form
      Comment::create([
        'body' => request('body'),
        'post_id' => $post->id
      ]);
      */

      /*
        Here is the shorter form, which requires the addition of the
        addComment method to the Post class
      */
      $post->addComment(request('body'));
      return back();
    }
}
