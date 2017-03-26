<?php namespace App\Module\Auth\Listeners;

use Illuminate\Auth\Events\Logout;

class UserLoggedOutListener {
    private $event;
    
    public function handle ( Logout $event ) {
        $this->event = $event;
        session(['lastLoggedIn' => null]);
    }

}