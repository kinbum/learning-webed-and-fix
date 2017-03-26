<?php namespace App\Module\Caching\Http\Controllers;

use App\Module\Base\Http\Controllers\BaseAdminController;

class CachingController extends BaseAdminController
{
    protected $module = 'caching';

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addLink('Caching', route('caching.index.get'));

        $this->getDashboardMenu($this->module);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $this->setPageTitle('Cache management', 'Manage all cms cache');

        $this->assets->addJavascripts('jquery-datatables');

        return do_filter('caching.index.get', $this)->viewAdmin('index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearCmsCache()
    {
        \Artisan::call('cache:clear');

        $this->flashMessagesHelper
            ->addMessages('Cache cleaned', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRefreshCompiledViews()
    {
        \Artisan::call('view:clear');

        $this->flashMessagesHelper
            ->addMessages('Views refreshed', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCreateConfigCache()
    {
        \Artisan::call('config:cache');

        $this->flashMessagesHelper
            ->addMessages('Config cache created', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearConfigCache()
    {
        \Artisan::call('config:clear');

        $this->flashMessagesHelper
            ->addMessages('Config cache cleared', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getOptimizeClass()
    {
        \Artisan::call('optimize');

        $this->flashMessagesHelper
            ->addMessages('Generated optimized class loader', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearCompiledClass()
    {
        \Artisan::call('clear-compiled');

        $this->flashMessagesHelper
            ->addMessages('Optimized class loader cleared', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCreateRouteCache()
    {
        \Artisan::call('route:cache');

        $this->flashMessagesHelper
            ->addMessages('Route cache created', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearRouteCache()
    {
        \Artisan::call('route:clear');

        $this->flashMessagesHelper
            ->addMessages('Route cache cleared', 'success')
            ->showMessagesOnSession();

        return redirect()->to(route('caching.index.get'));
    }
}
