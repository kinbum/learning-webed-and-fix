<?php namespace App\Module\Base\Support;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Models\EloquentBase;
use App\Module\Base\Repositories\Contracts\ViewTrackerRepositoryContract;
use App\Module\Base\Repositories\ViewTrackerRepository;

class ViewCount
{
    /**
     * @var ViewTrackerRepositoryContract|ViewTrackerRepository
     */
    protected $repository;

    /**
     * ViewCount constructor.
     * @param ViewTrackerRepository $repository
     */
    public function __construct(ViewTrackerRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param EloquentBase|string $entity
     * @param $entityId
     */
    public function increase($entity, $entityId)
    {
        if ($entity instanceof BaseModelContract) {
            $entity = get_class($entity);
        }
        $viewTracker = $this->repository->findByFieldsOrCreate([
            'entity' => $entity,
            'entity_id' => $entityId,
        ]);

        return $this->repository->increase($viewTracker);
    }
}
