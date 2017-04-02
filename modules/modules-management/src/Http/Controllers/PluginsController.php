<?php namespace App\Module\ModulesManagement\Http\Controllers;

use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Module\Base\Support\DataTable\DataTables;
use App\Module\ModulesManagement\Http\DataTables\PluginsListDataTable;
use App\Module\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract;
use App\Module\ModulesManagement\Repositories\PluginsRepository;
use Illuminate\Support\Facades\Artisan;

class PluginsController extends BaseAdminController
{
    protected $module = 'modules-management';

    protected $dashboardMenuId = 'plugins';

    /**
     * @param PluginsRepository $repository
     */
    public function __construct(PluginsRepositoryContract $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Get index page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(PluginsListDataTable $dataTable)
    {
        $this->breadcrumbs->addLink('Plugins');

        $this->setPageTitle('Plugins');

        $this->getDashboardMenu($this->dashboardMenuId);

        $this->dis['dataTable'] = $dataTable->run();
        return do_filter('webed-modules-plugin.index.get', $this)->view('plugins-list', null, 'admin');
    }

    /**
     * Set data for DataTable plugin
     * @param DataTables $dataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function postListing(PluginsListDataTable $dataTable)
    {
        return do_filter('datatables.webed-modules-plugin.index.post', $dataTable, $this);
    }

    public function postChangeStatus($module, $status)
    {
        switch ((bool)$status) {
            case true:
                return modules_management()->enableModule($module)->refreshComposerAutoload();
                break;
            default:
                return modules_management()->disableModule($module)->refreshComposerAutoload();
                break;
        }
    }

    public function postInstall($alias)
    {
        $module = get_module_information($alias);

        if(!$module) {
            return response_with_messages('Plugin not exists', true, \Constants::ERROR_CODE);
        }

        Artisan::call('module:install', [
            'alias' => $alias
        ]);

        return response_with_messages('Installed plugin dependencies');
    }

    public function postUpdate($alias)
    {
        $module = get_module_information($alias);

        if(!$module) {
            return response_with_messages('Plugin not exists', true, \Constants::ERROR_CODE);
        }

        Artisan::call('module:update', [
            'alias' => $alias
        ]);

        return response_with_messages('This plugin has been updated');
    }

    public function postUninstall($alias)
    {
        $module = get_module_information($alias);

        if(!$module) {
            return response_with_messages('Plugin not exists', true, \Constants::ERROR_CODE);
        }

        Artisan::call('module:uninstall', [
            'alias' => $alias
        ]);

        return response_with_messages('Uninstalled plugin dependencies');
    }
}
