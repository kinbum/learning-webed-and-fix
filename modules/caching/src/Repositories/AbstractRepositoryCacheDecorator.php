<?php namespace App\Module\Caching\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Module\Caching\Repositories\Traits\RepositoryValidatableCache;
use App\Module\Base\Criterias\AbstractCriteria;
use App\Module\Base\Criterias\Contracts\CriteriaContract;
use App\Module\Base\Exceptions\Repositories\WrongCriteria;
use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Repositories\AbstractBaseRepository;
use App\Module\Caching\Services\Contracts\CacheableContract;
use App\Module\Caching\Services\Traits\Cacheable;
use App\Module\Base\Repositories\Contracts\AbstractRepositoryContract;
use App\Module\Base\Repositories\Contracts\RepositoryValidatorContract;

abstract class AbstractRepositoryCacheDecorator implements AbstractRepositoryContract, CacheableContract, RepositoryValidatorContract
{
    use RepositoryValidatableCache;

    /**
     * @var AbstractBaseRepository|Cacheable
     */
    protected $repository;

    /**
     * @var \App\Module\Caching\Services\CacheService
     */
    protected $cache;

    /**
     * @param CacheableContract $repository
     */
    public function __construct(CacheableContract $repository)
    {
        $this->repository = $repository;

        $this->cache = app(\App\Module\Caching\Services\Contracts\CacheServiceContract::class);

        $this->cache
            ->setCacheObject($this->repository)
            ->setCacheLifetime(config('ace-caching.repository.lifetime'))
            ->setCacheFile(config('ace-caching.repository.store_keys'));
    }

    /**
     * @return bool
     */
    public function isUseCache()
    {
        return call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function withCache($bool = true)
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }

    /**
     * @return AbstractBaseRepository|Cacheable
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \App\Module\Caching\Services\CacheService
     */
    public function getCacheInstance()
    {
        return $this->cache;
    }

    /**
     * @param $lifetime
     * @return $this
     */
    public function setCacheLifetime($lifetime)
    {
        $this->cache->setCacheLifetime($lifetime);

        return $this;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function beforeGet($method, $parameters)
    {
        $repository = clone $this->repository;

        $this->cache->setCacheKey($method, $parameters);

        /**
         * Clear params
         */
        $this->repository->resetModel();

        return $this->cache->retrieveFromCache(function () use ($repository, $method, $parameters) {
            return call_user_func_array([$repository, $method], $parameters);
        });
    }

    /**
     * @param $method
     * @param $parameters
     * @param bool $flushCache
     * @return mixed
     */
    public function afterUpdate($method, $parameters, $flushCache = true, $forceFlush = false)
    {
        $result = call_user_func_array([$this->repository, $method], $parameters);

        if ($flushCache === true && ($forceFlush === true || (is_array($result) && isset($result['error']) && !$result['error']))) {
            $this->cache->flushCache();
        }

        return $result;
    }

    /**
     * @return BaseModelContract
     */
    public function getModel()
    {
        return call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
    }

    /**
     * Get model table
     * @return string
     */
    public function getTable()
    {
        return call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
    }

    /**
     * Get primary key
     * @return string
     */
    public function getPrimaryKey()
    {
        return call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function select(array $fields)
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }

    /**
     * @return array
     */
    public function getCriteria()
    {
        return call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
    }

    /**
     * @param AbstractCriteria $criteria
     * @param array $crossData
     * @return $this
     * @throws WrongCriteria
     */
    public function pushCriteria(CriteriaContract $criteria)
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }

    /**
     * @param $criteria
     * @return $this
     */
    public function dropCriteria($criteria)
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function skipCriteria($bool = true)
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }

    /**
     * @param AbstractCriteria|string $criteria
     * @return Collection|BaseModelContract|LengthAwarePaginator|null|mixed
     */
    public function getByCriteria(CriteriaContract $criteria)
    {
        return $this->beforeGet(__FUNCTION__, func_get_args());
    }

    /**
     * @return $this
     */
    public function resetModel()
    {
        call_user_func_array([$this->repository, __FUNCTION__], func_get_args());
        return $this;
    }
}
