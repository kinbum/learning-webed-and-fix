<?php namespace App\Module\Base\Http\ViewComposers;

use Illuminate\View\View;

class AdminBreadcrumbsViewComposer {

    public function compose(View $view) {
        $view->with('pageBreadcrumbs', \Breadcrumbs::render());
    }

}