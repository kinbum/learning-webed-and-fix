<?php namespace App\Plugins\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use App\Plugins\CustomFields\Models\CustomField;
use App\Plugins\CustomFields\Models\FieldGroup;
use App\Plugins\CustomFields\Models\FieldItem;
use App\Plugins\CustomFields\Repositories\Contracts\CustomFieldRepositoryContract;
use App\Plugins\CustomFields\Repositories\Contracts\FieldGroupRepositoryContract;
use App\Plugins\CustomFields\Repositories\Contracts\FieldItemRepositoryContract;
use App\Plugins\CustomFields\Repositories\CustomFieldRepository;
use App\Plugins\CustomFields\Repositories\FieldGroupRepository;
use App\Plugins\CustomFields\Repositories\FieldItemRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'App\Plugins\CustomFields';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FieldGroupRepositoryContract::class, function () {
            $repository = new FieldGroupRepository(new FieldGroup);

            return $repository;
        });
        $this->app->bind(FieldItemRepositoryContract::class, function () {
            $repository = new FieldItemRepository(new FieldItem);

            return $repository;
        });
        $this->app->bind(CustomFieldRepositoryContract::class, function () {
            $repository = new CustomFieldRepository(new CustomField);

            return $repository;
        });
    }
}
