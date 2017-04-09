<?php namespace App\Plugins\Blog\Http\Controllers;

use App\Plugins\Blog\Http\Requests\CreateBlogTagRequest;
use App\Plugins\Blog\Http\Requests\UpdateBlogTagRequest;

use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Plugins\Blog\Http\DataTables\BlogTagsListDataTable;
use App\Plugins\Blog\Repositories\Contracts\BlogTagRepositoryContract;

class BlogTagController extends BaseAdminController
{
    protected $module = 'blog';

    protected $repository;

    public function __construct(BlogTagRepositoryContract $repository)
    {
        parent::__construct();
        $this->repository = $repository;

        $this->breadcrumbs->addLink('Blog')
            ->addLink('Tags', route('blog.tags.index.get'));

        $this->getDashboardMenu('ace-blog-tags');
    }

    public function getIndex (BlogTagsListDataTable $blogTagsListDataTable) {
        $this->setPageTitle('Tags', 'All available blog tags');
        $this->dis['dataTable'] = $blogTagsListDataTable->run();
        return do_filter('tag.get.index', $this)->view('tags.index', null, 'admin');
    }

    public function postListing(BlogTagsListDataTable $blogTagsListDataTable) {
        $data = $blogTagsListDataTable->with($this->groupAction());
        return do_filter('datatables.blog.tags.index.post', $data, $this);
    }

    public function groupAction () {
        $data = [];
        if($this->request->get('customActionType', null) === 'group_action') {
            if(!$this->userRepository->hasPermission($this->loggedInUser, ['edit-tags'])) {
                return [
                    'customActionMessage' => 'You do not have permission',
                    'customActionStatus' => 'danger',
                ];
            }

            $ids = (array)$this->request->get('id', []);
            $actionValue = $this->request->get('customActionValue');

            switch ($actionValue) {
                case 'deleted':
                    if (!$this->userRepository->hasPermission($this->loggedInUser, ['delete-tags'])) {
                        return [
                            'customActionMessage' => 'You do not have permission',
                            'customActionStatus' => 'danger',
                        ];
                    }
                    $result = $this->deleteDelete($ids);
                    break;
                case 'activated':
                case 'disabled':
                    $result = $this->repository->updateMultiple($ids, [
                        'status' => $actionValue,
                    ], true);
                    break;
                default:
                    $result = [
                        'messages' => 'Method not allowed',
                        'error' => true
                    ];
                    break;
            }
            $data['customActionMessage'] = $result['messages'];
            $data['customActionStatus'] = $result['error'] ? 'danger' : 'success';
        }
        return $data;
    }
    
    public function postUpdateStatus($id, $status)
    {
        $data = [
            'status' => $status
        ];
        $result = $this->repository->updateTag($id, $data);
        return response()->json($result, $result['response_code']);
    }

    public function getCreate()
    {
        $this->setPageTitle('Create tag');
        $this->breadcrumbs->addLink('Create tag');

        $this->dis['object'] = $this->repository->getModel();

        $oldInputs = old();
        if ($oldInputs) {
            foreach ($oldInputs as $key => $row) {
                $this->dis['object']->$key = $row;
            }
        }

        return do_filter('blog.tags.create.get', $this)->view('tags.create', null, 'admin');
    }

    public function postCreate(CreateBlogTagRequest $request)
    {
        $data = $this->parseInputData();

        $data['created_by'] = $this->loggedInUser->id;

        $result = $this->repository->createTag($data);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back()->withInput();
        }

        if ($request->has('_continue_edit')) {
            if (!$result['error']) {
                return redirect()->to(route('blog.tags.edit.get', ['id' => $result['data']->id]));
            }
        }

        return redirect()->to(route('blog.tags.index.get'));
    }

    public function getEdit($id)
    {
        $item = $this->repository->find($id);
        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('This tag not exists', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $item = do_filter('blog.tags.before-edit.get', $item);

        $this->assets
            ->addJavascripts([
                'jquery-ckeditor'
            ]);

        $this->setPageTitle('Edit tag', $item->title);
        $this->breadcrumbs->addLink('Edit tag');

        $this->dis['object'] = $item;

        return do_filter('blog.tags.edit.get', $this, $id)->view('tags.edit', null, 'admin');
    }

    public function postEdit(UpdateBlogTagRequest $request, $id)
    {
        $item = $this->repository->find($id);
        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('This tag not exists', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $data = $this->parseInputData();

        $result = $this->repository->updateTag($item, $data);

        do_action('blog.tags.after-edit.post', $id, $result, $this);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($request->has('_continue_edit')) {
            return redirect()->back();
        }

        return redirect()->to(route('blog.tags.index.get'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDelete($id)
    {
        $id = do_filter('blog.tags.before-delete.delete', $id);

        $result = $this->repository->delete($id);

        do_action('blog.tags.after-delete.delete', $id, $result, $this);

        return response()->json($result, $result['response_code']);
    }

    protected function parseInputData()
    {
        $data = [
            'status' => $this->request->get('status'),
            'title' => $this->request->get('title'),
            'slug' => ($this->request->get('slug') ? str_slug($this->request->get('slug')) : str_slug($this->request->get('title'))),
            'description' => $this->request->get('description'),
            'order' => $this->request->get('order'),
            'updated_by' => $this->loggedInUser->id,
        ];
        return $data;
    }
}
