<?php namespace App\Plugins\Blog\Http\Controllers;

use App\Plugins\Blog\Http\Requests\CreateCategoryRequest;
use App\Plugins\Blog\Http\Requests\UpdateCategoryRequest;
use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Plugins\Blog\Repositories\Contracts\CategoryRepositoryContract;
use App\Plugins\Blog\Http\DataTables\CategoriesListDataTable;

class CategoryController extends BaseAdminController
{
    protected $module = 'blog';

    public function __construct(CategoryRepositoryContract $repository)
    {
        parent::__construct();
        
        $this->repository = $repository;

        $this->breadcrumbs->addLink('Blog')
            ->addLink('Categories', route('blog.categories.index.get'));

        $this->getDashboardMenu('ace-blog-categories');
    }
    public function getIndex(CategoriesListDataTable $categoriesListDataTable) {
        $this->setPageTitle('Categories', 'All available blog categories');
        $this->dis['dataTable'] = $categoriesListDataTable->run();
        return do_filter('blog.categories.index.get', $this)->view('categories.index', null, 'admin');
    }

    /**
     * Get data for DataTable
     * @param CategoriesListDataTable|BaseEngine $categoriesListDataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function postListing(CategoriesListDataTable $categoriesListDataTable)
    {
        $data = $categoriesListDataTable->with($this->groupAction());

        return do_filter('datatables.blog.categories.index.post', $data, $this)
            ->make(true);
    }

    /**
     * Handle group actions
     * @return array
     */
    private function groupAction()
    {
        $data = [];
        if ($this->request->get('customActionType', null) === 'group_action') {
            if (!$this->userRepository->hasPermission($this->loggedInUser, ['edit-categories'])) {
                return [
                    'customActionMessage' => 'You do not have permission',
                    'customActionStatus' => 'danger',
                ];
            }

            $ids = (array)$this->request->get('id', []);
            $actionValue = $this->request->get('customActionValue');

            switch ($actionValue) {
                case 'deleted':
                    if (!$this->userRepository->hasPermission($this->loggedInUser, ['delete-categories'])) {
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

    /**
     * Update page status
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateStatus($id, $status)
    {
        $data = [
            'status' => $status
        ];
        $result = $this->repository->updateCategory($id, $data);
        return response()->json($result, $result['response_code']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $allCategories = get_categories();

        $selectArr = ['' => 'Select...'];
        foreach ($allCategories as $category) {
            $selectArr[$category->id] = $category->indent_text . $category->title;
        }
        $this->dis['categories'] = $selectArr;

        $this->assets
            ->addJavascripts([
                'jquery-ckeditor'
            ]);

        $this->setPageTitle('Create category');
        $this->breadcrumbs->addLink('Create category');

        $this->dis['object'] = $this->repository->getModel();

        $oldInputs = old();
        if ($oldInputs) {
            foreach ($oldInputs as $key => $row) {
                $this->dis['object']->$key = $row;
            }
        }

        return do_filter('blog.categories.create.get', $this)->view('categories.create', null, 'admin');
    }

    public function postCreate(CreateCategoryRequest $request)
    {
        $parentId = $request->get('parent_id') ?: null;
        $data = $this->parseInputData();
        $data['parent_id'] = $parentId;

        $data['created_by'] = $this->loggedInUser->id;

        $result = $this->repository->createCategory($data);

        // do_action('blog.categories.after-create.post', null, $result, $this);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back()->withInput();
        }

        if ($request->has('_continue_edit')) {
            return redirect()->to(route('blog.categories.edit.get', ['id' => $result['data']->id]));
        }

        return redirect()->to(route('blog.categories.index.get'));
    }

    public function getEdit($id)
    {
        $item = $this->repository->find($id);
        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('This category not exists', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $this->assets
            ->addJavascripts([
                'jquery-ckeditor'
            ]);

        $categories = get_categories();

        $selectArr = ['' => 'Select...'];
        $childCategories = array_merge($this->repository->getAllRelatedChildrenIds($item), [$id]);
        foreach ($categories as $category) {
            if (!in_array($category->id, $childCategories)) {
                $selectArr[$category->id] = $category->indent_text . $category->title;
            }
        }
        $this->dis['categories'] = $selectArr;

        $this->setPageTitle('Edit category', $item->title);
        $this->breadcrumbs->addLink('Edit category');

        $this->dis['object'] = $item;
        return $this->view('categories.edit', null, 'admin');
    }

    public function postEdit(UpdateCategoryRequest $request, $id)
    {
        $item = $this->repository->find($id);
        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('This category not exists', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $parentId = (int)$request->get('parent_id') === (int)$id ? null : $request->get('parent_id') ?: null;
        $data = $this->parseInputData();
        $data['parent_id'] = $parentId;

        $result = $this->repository->updateCategory($item, $data);


        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($request->has('_continue_edit')) {
            return redirect()->back();
        }

        return redirect()->to(route('blog.categories.index.get'));
    }

    public function deleteDelete($id)
    {
        $id = do_filter('blog.categories.before-delete.delete', $id);

        $result = $this->repository->delete($id);

        return response()->json($result, $result['response_code']);
    }


    protected function parseInputData()
    {
        return [
            'page_template' => $this->request->get('page_template', null),
            'status' => $this->request->get('status'),
            'title' => $this->request->get('title'),
            'slug' => ($this->request->get('slug') ? str_slug($this->request->get('slug')) : str_slug($this->request->get('title'))),
            'keywords' => $this->request->get('keywords'),
            'description' => $this->request->get('description'),
            'content' => $this->request->get('content'),
            'thumbnail' => $this->request->get('thumbnail'),
            'order' => $this->request->get('order'),
            'updated_by' => $this->loggedInUser->id,
        ];
    }
}
