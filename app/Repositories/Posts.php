<?php

namespace App\Repositories;

use App\Post;
use App\Redis;

/*
  This is a good place to put repetitive code found in the controllers.
  We can just pass one of these directly to any controller as an argument to
  a method and have access to it directly ... Make sure and use a "use" statment
  like This

  use App\Repositories\Posts;

  public function someControllerMethod(Posts $posts)
*/
class Posts {
  protected $redis;

  public function __construct(Redis $redis) {
    $this->redis = $redis;
  }

  public function all() {
    // return all relevant posts
    return Post::all();
  }

  public function find() {

  }

}

 ?>
