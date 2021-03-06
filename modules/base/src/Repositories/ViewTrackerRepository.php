<?php namespace App\Module\Base\Repositories;

use App\Module\Base\Models\Contracts\ViewTrackerModelContract;
use App\Module\Base\Models\ViewTracker;
use App\Module\Base\Repositories\Contracts\ViewTrackerRepositoryContract;
use App\Module\Base\Repositories\Eloquent\EloquentBaseRepository;

class ViewTrackerRepository extends EloquentBaseRepository implements ViewTrackerRepositoryContract
{
    protected $rules = [

    ];

    protected $editableFields = [
        '*',
    ];

    /**
     * @param ViewTracker $viewTracker
     * @return array
     */
    public function increase(ViewTrackerModelContract $viewTracker)
    {
        return $this->editWithValidate($viewTracker, [
            'count' => $viewTracker->count + 1
        ]);
    }
}
