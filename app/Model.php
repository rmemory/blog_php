<?php
namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent {
  // Allow all mass fills
  protected $guarded = [];
}

?>
