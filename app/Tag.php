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

    public function getRouteKeyName() {
      /* This allows the URL wildcard to be the string associated with the
         name column in the tag database, such as myblog/posts/tags/personal
         instead of myblog/posts/tags/1
         */
      return 'name';
    }
}
