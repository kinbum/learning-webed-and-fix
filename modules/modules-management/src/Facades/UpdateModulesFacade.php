<?php namespace App\Module\ModulesManagement\Facades;

use Illuminate\Support\Facades\Facade;
use App\Module\ModulesManagement\Support\UpdateModulesSupport;

/**
 * @method static registerUpdateBatches($moduleAlias, array $batches)
 * @method static loadBatches($moduleAlias)
 */
class UpdateModulesFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return UpdateModulesSupport::class;
    }
}
