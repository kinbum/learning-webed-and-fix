<?php namespace App\Themes\Blog\Http\Controllers\Pages;

use App\Module\Pages\Models\Contracts\PageModelContract;
use App\Module\Pages\Models\Page;
use App\Module\Pages\Repositories\Contracts\PageRepositoryContract;
use App\Module\Pages\Repositories\PageRepository;

use App\Themes\Blog\Http\Controllers\AbstractController;


use App\Plugins\Blog\Repositories\Contracts\PostRepositoryContract;
use App\Plugins\Blog\Models\Post;

class PageController extends AbstractController
{
    protected $module = 'theme';
    /**
     * @var Page
     */
    protected $page;
    protected $postRepository;


    /**
     * @param PageRepository $repository
     */
    public function __construct(PageRepositoryContract $repository, PostRepositoryContract $postRepository)
    {
        parent::__construct();

        $this->repository = $repository;
        $this->postRepository = $postRepository;
    }

    /**
     * @param Page $item
     * @param array $data
     */
    public function handle(PageModelContract $item, array $data)
    {
        $this->dis = $data;

        $this->page = $item;

        $this->getMenu('page', $item->id);

        $happyMethod = '_template_' . studly_case($item->page_template);

        if(method_exists($this, $happyMethod)) {
            return $this->$happyMethod();
        }

        return $this->defaultTemplate();
    }

    /**
     * @return mixed
     */
    protected function defaultTemplate()
    {
        $this->dis['posts'] = $this->postRepository->get();
        return $this->view('page-templates.default', $this->dis, 'front');
    }

    /**
     * @return mixed
     */
    protected function _template_Homepage()
    {
        return $this->view('theme::front.page-templates.homepage');
    }
}
