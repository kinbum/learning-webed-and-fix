<?php namespace App\Module\Base\Http\Controllers;

class DashboardController extends BaseAdminController
{
    protected $module = 'base';

    public function __construct()
    {
        parent::__construct();
    }

    public function index () {
        return do_filter('dashboard.index.get', $this)->view('dashboard', null, 'admin');
    }

}
