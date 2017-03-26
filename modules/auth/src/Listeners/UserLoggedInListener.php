<?php namespace App\Module\Auth\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class UserLoggedInListener {
    private $event;
    
    public function handle ( Login $event ) {
        $this->event = $event;
        $this->_updateLastLoggedIn();
    }

    private function _updateLastLoggedIn () {
        $user = $this->event->user;
        session(['lastLoggedIn' => $user->last_logged_in]);
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}