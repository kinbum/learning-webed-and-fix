<?php namespace App\Module\Base\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    /**
    *** @var Request
    */
    public $request;

    /**
    *** @var AbstractModel
    */
    public $model;

    /**
     ** @var string
     */
    public $adminRoute;

    /**
     ** Specify all variables will be passed to view
     ** @var array
     */
    public $dis = [];
    public $assets;

    public function __construct () {
        $this->request = request();
        $this->adminRoute = config('ace.admin_prefix');
        $this->assets = assets_management()->getAssetsFrom('admin');
    }

    /**
    *** Set css class for body
    *** @param string $class
    */
    public function setBodyClass ( $class ) {
        view()->share([ 'bodyClass' => $class ]);
    }

    /**
    *** Set page title
    *** @param $title
    *** @param null $subTitle
    */
    public function setPageTitle ( $title, $subTitle = null ) {
        view()->share([
            'pageTitle' => $title,
            'subPageTitle' => $subTitle
        ]);
    }

    /**
    *** @param $viewName
    *** @param null $data
    *** @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
     protected function view ( $viewName, $data = null, $bind = null ) {
        if( $data === null || !is_array($data) ) {
             $data = $this->dis;
        }
        if( !empty($bind) ) $bind = $bind . '.';

        if( property_exists( $this, 'module' ) && $this->module ) {
            return view( $this->module . '::' . $bind . $viewName, $data );
        }

        return view( $bind . $viewName, $data );
     }
}
