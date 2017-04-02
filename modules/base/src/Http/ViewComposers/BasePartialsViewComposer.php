<?php namespace App\Module\Base\Http\ViewComposers;

use Illuminate\View\View;

class BasePartialsViewComposer {

    public function compose(View $view) {
        $view->with('loggedInUser', request()->user());
    }

}