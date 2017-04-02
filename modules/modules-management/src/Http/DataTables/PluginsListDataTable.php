<?php namespace App\Module\ModulesManagement\Http\DataTables;

use App\Module\Base\Http\DataTables\AbstractDataTables;

class PluginsListDataTable extends AbstractDataTables
{
    protected $repository;

    public function __construct()
    {
        $this->repository = modules_management()->export('plugins')->values();

        parent::__construct();
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->setAjaxUrl(route('plugins.index.post'), 'POST');

        $this
            ->addHeading('name', 'Name', '20%')
            ->addHeading('description', 'Description', '50%')
            ->addHeading('actions', 'Actions', '30%');

        $this->setColumns([
            ['data' => 'name', 'name' => 'name', 'searchable' => false, 'orderable' => false],
            ['data' => 'description', 'name' => 'description', 'searchable' => false, 'orderable' => false],
            ['data' => 'actions', 'name' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);

        return $this->view();
    }

    /**
     * @return $this
     */
    protected function fetch()
    {
        $this->fetch = datatable()->of($this->repository)
            ->editColumn('description', function ($item) {
                return array_get($item, 'description') . '<br><br>'
                    . 'Author: ' . array_get($item, 'author') . '<br><br>'
                    . 'Version: <b>' . array_get($item, 'version', '...') . '</b>' . '<br>'
                    . 'Installed version: <b>' . array_get($item, 'installed_version', '...') . '</b>';
            })
            ->addColumn('actions', function ($item) {
                $activeBtn = (!array_get($item, 'enabled')) ? form()->button('Active', [
                    'title' => 'Active this plugin',
                    'data-ajax' => route('plugins.change-status.post', [
                        'module' => array_get($item, 'alias'),
                        'status' => 1,
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline green btn-sm ajax-link',
                ]) : '';

                $disableBtn = (array_get($item, 'enabled')) ? form()->button('Disable', [
                    'title' => 'Disable this plugin',
                    'data-ajax' => route('plugins.change-status.post', [
                        'module' => array_get($item, 'alias'),
                        'status' => 0,
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline yellow-lemon btn-sm ajax-link',
                ]) : '';

                $installBtn = (array_get($item, 'enabled') && !array_get($item, 'installed')) ? form()->button('Install', [
                    'title' => 'Install this plugin\'s dependencies',
                    'data-ajax' => route('plugins.install.post', [
                        'module' => array_get($item, 'alias'),
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline blue btn-sm ajax-link',
                ]) : '';

                $updateBtn = (
                    array_get($item, 'enabled') &&
                    array_get($item, 'installed') &&
                    version_compare(array_get($item, 'installed_version'), array_get($item, 'version'), '<')
                )
                    ? form()->button('Update', [
                        'title' => 'Update this plugin',
                        'data-ajax' => route('plugins.update.post', [
                            'module' => array_get($item, 'alias'),
                        ]),
                        'data-method' => 'POST',
                        'data-toggle' => 'confirmation',
                        'class' => 'btn btn-outline purple btn-sm ajax-link',
                    ])
                    : '';

                $uninstallBtn = (array_get($item, 'enabled') && array_get($item, 'installed')) ? form()->button('Uninstall', [
                    'title' => 'Uninstall this plugin\'s dependencies',
                    'data-ajax' => route('plugins.uninstall.post', [
                        'module' => array_get($item, 'alias'),
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline red-sunglo btn-sm ajax-link',
                ]) : '';

                return $activeBtn . $disableBtn . $installBtn . $updateBtn . $uninstallBtn;
            });

        return $this;
    }
}
