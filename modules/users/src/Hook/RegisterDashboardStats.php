<?php namespace App\Module\Users\Hook;

use App\Module\Users\Repositories\Contracts\UserRepositoryContract;
use App\Module\Users\Repositories\UserRepository;

class RegisterDashboardStats
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function handle()
    {
        echo view('users::admin.dashboard-stats.stat-box', [
            'count' => $this->repository->count(),
        ]);
    }
}
