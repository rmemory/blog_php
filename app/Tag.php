<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function posts() {
      return $this->belongsToMany(Post::class);
      /*
        To get all posts with tags, do this:

        App\Post::with('tags')->get();
      */

      /*
        To create, or attach a new record in the pivot table, you can do this:

        $post->tags()->attach($tag)

        Also, there is a detach:

        $post->tags->detach($tag);
      */
    }
}
