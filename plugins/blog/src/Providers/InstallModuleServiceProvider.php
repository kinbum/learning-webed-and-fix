<?php namespace App\Plugins\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'App\Plugins\Blog';

    protected $moduleAlias = 'blog';

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
        $this->createSchema();

        acl_permission()
            ->registerPermission('View posts', 'view-posts', $this->module)
            ->registerPermission('Create posts', 'create-posts', $this->module)
            ->registerPermission('Update posts', 'update-posts', $this->module)
            ->registerPermission('Delete posts', 'delete-posts', $this->module)
            ->registerPermission('View categories', 'view-categories', $this->module)
            ->registerPermission('Create categories', 'create-categories', $this->module)
            ->registerPermission('Update categories', 'update-categories', $this->module)
            ->registerPermission('Delete categories', 'delete-categories', $this->module);
    }

    private function createSchema()
    {
        //Schema::create('field_groups', function (Blueprint $table) {
        //    $table->engine = 'InnoDB';
        //    $table->increments('id');
        //});
    }
}
