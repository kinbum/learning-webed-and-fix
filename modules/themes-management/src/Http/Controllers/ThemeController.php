<?php namespace App\Module\ThemesManagement\Http\Controllers;

use App\Module\Base\Http\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Artisan;
use App\Module\ThemesManagement\Http\DataTables\ThemesListDataTable;

class ThemeController extends BaseAdminController
{
    protected $module = 'themes-management';

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addLink('Themes');

        $this->setPageTitle('Themes');

        $this->getDashboardMenu($this->module);
    }

    /**
     * Get index page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(ThemesListDataTable $themesListDataTable)
    {
        $this->dis['dataTable'] = $themesListDataTable->run();

        return do_filter('themes-management.index.get', $this)->view('list', null, 'admin');
    }

    /**
     * Set data for DataTable plugin
     * @param ThemesListDataTable $themesListDataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function postListing(ThemesListDataTable $themesListDataTable)
    {
        return do_filter('datatables.themes-management.index.post', $themesListDataTable, $this);
    }

    public function postChangeStatus($alias, $status)
    {
        $theme = get_theme_information($alias);

        if (!$theme) {
            return response_with_messages('Theme not exists', true, \Constants::ERROR_CODE);
        }

        switch ((bool)$status) {
            case true:
                $check = check_module_require($theme);
                if ($check['error']) {
                    return $check;
                }

                return themes_management()->enableTheme($alias)->refreshComposerAutoload();
                break;
            default:
                return themes_management()->disableTheme($alias)->refreshComposerAutoload();
                break;
        }
    }

    public function postInstall($alias)
    {
        $theme = get_theme_information($alias);

        if (!$theme) {
            return response_with_messages('Theme not exists', true, \Constants::ERROR_CODE);
        }

        $check = check_module_require($theme);
        if ($check['error']) {
            return $check;
        }

        Artisan::call('theme:install', [
            'alias' => $alias
        ]);

        return response_with_messages('Installed theme dependencies');
    }

    public function postUpdate($alias)
    {
        $theme = get_theme_information($alias);

        if (!$theme) {
            return response_with_messages('Theme not exists', true, \Constants::ERROR_CODE);
        }

        $check = check_module_require($theme);
        if ($check['error']) {
            return $check;
        }

        Artisan::call('theme:update', [
            'alias' => $alias
        ]);

        return response_with_messages('Your theme has been updated');
    }

    public function postUninstall($alias)
    {
        $theme = get_theme_information($alias);

        if (!$theme) {
            return response_with_messages('Theme not exists', true, \Constants::ERROR_CODE);
        }

        $check = check_module_require($theme);
        if ($check['error']) {
            return $check;
        }

        Artisan::call('theme:uninstall', [
            'alias' => $alias
        ]);

        return response_with_messages('Uninstalled theme dependencies');
    }
}
