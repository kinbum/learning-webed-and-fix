<?php namespace App\Plugins\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Plugins\CustomFields';

    protected $moduleAlias = 'custom-fields';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function booted()
    {
        acl_permission()
            ->registerPermission('View custom fields', 'view-custom-fields', $this->module)
            ->registerPermission('Create field group', 'create-field-groups', $this->module)
            ->registerPermission('Edit field group', 'edit-field-groups', $this->module)
            ->registerPermission('Delete field group', 'delete-field-groups', $this->module);
    }

    private function createSchema()
    {
        //Schema::create('field_groups', function (Blueprint $table) {
        //    $table->engine = 'InnoDB';
        //    $table->increments('id');
        //});
    }
}
