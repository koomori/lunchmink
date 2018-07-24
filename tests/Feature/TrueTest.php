<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrueTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTrue()
    {
        $theTruth = true;

       $this->assertTrue($theTruth);

    }

    public function testGuestMessage()
    {
      $this->get('/')->assertSee('Welcome, Guest');
    }

     public function testArray()
    {
        $user = ['id' => '3','first_name' => 'Fred','last_name' => 'Barnes','password' => '92483204820'];
       $this->assertArrayHasKey('password', $user);
    }

       public function testFalse()
    {
        $user = factory(User::class)->create();

       $this->assertFalse($user->password == null);
    }

        public function testNameOfSite()
    {
       $siteName = 'Lunch Mink';

       $this->assertSame('Lunch Mink', $siteName);
    }

       public function testMessage()
    {
       $message = 'Lunch Mink';

       $this->assertContains('Lunch', $message);
    }
}
