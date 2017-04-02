<?php namespace App\Module\Base\Http\Controllers;

use Illuminate\Http\Request;
use App\Module\Users\Repositories\Contracts\UserRepositoryContract;
use App\Module\Users\Repositories\UserRepository;

abstract class BaseAdminController extends BaseController
{
    /**
     * @var $breadcrumbs
     */
     public $breadcrumbs;

    /**
     * @var $loggedInUser
     */
     public $loggedInUser;

    /**
     * @var $assets
     */
     public $assets;

    /**
     * @var $flashMessagesHelper
     */
     public $flashMessagesHelper;

    /**
     * @var $userRepository
     */
     public $userRepository;

     public function __construct () {
         parent::__construct();
        $this->breadcrumbs = \Breadcrumbs::setBreadcrumbClass('breadcrumb')
            ->setContainerTag('ol');

         $this->middleware(function (Request $request, $next) {
            $this->loggedInUser = $request->user();
            view()->share([
                'loggedInUser' => $this->loggedInUser
            ]);
            \DashboardMenu::setUser($this->loggedInUser);
            return $next($request);
         });
         $this->assets
            ->addStylesheetsDirectly([
                asset('admin/theme/lte/css/AdminLTE.min.css'),
                asset('admin/theme/lte/css/skins/_all-skins.min.css'),
                asset('admin/css/style.css'),
            ])
            ->addJavascriptsDirectly([
                asset('admin/theme/lte/js/app.js'),
                asset('admin/js/webed-core.js'),
                asset('admin/theme/lte/js/demo.js'),
                asset('admin/js/script.js'),
            ], 'bottom');

         $this->flashMessagesHelper = flash_messages();

        $this->userRepository = app(UserRepositoryContract::class);
     }

    /**
     * @param null $activeId
     */
     protected function getDashboardMenu ( $activeId = null ) {
        \DashboardMenu::setActiveItem($activeId);
     }

}
