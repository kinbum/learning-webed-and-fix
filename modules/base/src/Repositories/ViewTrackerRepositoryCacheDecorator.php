<?php namespace App\Module\Base\Repositories;

use App\Module\Caching\Repositories\Eloquent\EloquentBaseRepositoryCacheDecorator;

use App\Module\Base\Models\Contracts\ViewTrackerModelContract;
use App\Module\Base\Repositories\Contracts\ViewTrackerRepositoryContract;

class ViewTrackerRepositoryCacheDecorator extends EloquentBaseRepositoryCacheDecorator  implements ViewTrackerRepositoryContract
{
    /**
     * @param ViewTrackerModelContract $viewTracker
     * @return array
     */
    public function increase(ViewTrackerModelContract $viewTracker)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }
}
