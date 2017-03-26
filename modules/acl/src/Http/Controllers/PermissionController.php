<?php namespace App\Module\Acl\Http\Controllers;

use App\Module\Base\Http\Controllers\BaseAdminController;

use App\Module\Acl\Http\DataTables\PermissionsListDataTable;
use App\Module\Acl\Repositories\Contracts\PermissionRepositoryContract;

class PermissionController extends BaseAdminController
{
    protected $module = 'acl';

    protected $repository;

    public function __construct(PermissionRepositoryContract $repository)
    {
        parent::__construct();

        $this->repository = $repository;

        $this->getDashboardMenu($this->module . '-permissions');

        // $this->breadcrumbs->addLink('ACL')->addLink('Permissions', route('admin::acl-permissions.index.get'));;
    }

    public function getIndex(PermissionsListDataTable $permissionsListDataTable)
    {
        $this->setPageTitle('Permissions', 'All available permissions');

        $this->dis['dataTable'] = $permissionsListDataTable->run();
        return do_filter('acl-permissions.index.get', $this)->view('permissions.index', null, 'admin');
    }

    public function postListing(PermissionsListDataTable $permissionsListDataTable)
    {
        return do_filter('datatables.acl-permissions.index.post', $permissionsListDataTable, $this);
    }
}
