<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Post;

/*
  $ vendor/bin/phpunit tests/Feature/ExampleTest.php
*/
class ExampleTest extends TestCase
{
    // Causes the database to be cleared after each test
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest() {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testBlogTitle() {
      $this->get('/')->assertSee('The Bootstrap Blog');
    }

    /*
      In order to not affect the development database, create a testing database

      mysql> create database blog_testing;

      Open phpunit.xml, go to the bottom and add this to override the values in
      .env.

      <env name="DB_DATABASE" value="blog_testing"/>

      Make this change in .env ...

      DB_DATABASE=blog_testing

      Run this:

      $ php artisan migrate

    */
    public function testPostArchives() {
      // Given that I have 2 records in the database which are posts,
      // and each one is posted a month apart
      // We will use Laravel's model factories to help here:
      // database/factories/UserFactory.php and add a function, to use
      // dummy data
      // factory('App\User')->make();
      //
      // To Persist use this instead:
      // factory('App\User')->create();
      //
      // Or this to create 50 users:
      // factory('App\User', 50)->create();
      //
      // Anyway, back to the task at hand ...
      $first = factory(Post::class)->create();
      $second = factory(Post::class)->create([
        'created_at' => \Carbon\Carbon::now()->subMonth()
      ]);

      // When I fetch the archives,
      $posts = Post::archives();

      // Then the response should be in the proper format (assertion)
      $this->assertCount(2, $posts);
      $this->assertEquals([
        [
          "year" => $first->created_at->format('Y'),
          "month" => $first->created_at->format('F'),
          "published" => 1
        ],
        [
          "year" => $second->created_at->format('Y'),
          "month" => $second->created_at->format('F'),
          "published" => 1
        ]
      ], $posts);

    }
}
