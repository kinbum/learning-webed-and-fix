<?php namespace App\Module\Pages\Http\Controllers;

use Illuminate\Http\Request;

use App\Module\Pages\Http\DataTables\PagesListDataTable;
use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Module\Pages\Http\Requests\CreatePageRequest;
use App\Module\Pages\Http\Requests\UpdatePageRequest;
use App\Module\Pages\Repositories\Contracts\PageRepositoryContract;

class PageController extends BaseAdminController
{
    protected $module = 'pages';

    public function __construct(PageRepositoryContract $pageRepository)
    {
        parent::__construct();
        $this->repository = $pageRepository;
        $this->breadcrumbs->addLink('Pages', route('pages.index.get'));
        $this->getDashboardMenu($this->module);
    }
    public function getIndex(PagesListDataTable $pagesListDataTable) {
        $this->setPageTitle('CMS pages', 'All available cms pages');
        $this->dis['dataTable'] = $pagesListDataTable->run();
        return do_filter('pages.index.get', $this)->view('index', null, 'admin');
    }

    public function postListing (PagesListDataTable $pagesListDataTable) {
        $data = $pagesListDataTable->with($this->groupAction());

        return do_filter('datatables.pages.index.post', $data, $this);
    }
    private function groupAction()
    {
        $data = [];
        if ($this->request->get('customActionType', null) === 'group_action') {
            if (!$this->userRepository->hasPermission($this->loggedInUser, ['edit-pages'])) {
                return [
                    'customActionMessage' => 'You do not have permission',
                    'customActionStatus' => 'danger',
                ];
            }

            $ids = (array)$this->request->get('id', []);
            $actionValue = $this->request->get('customActionValue');

            switch ($actionValue) {
                case 'deleted':
                    if (!$this->userRepository->hasPermission($this->loggedInUser, ['delete-pages'])) {
                        return [
                            'customActionMessage' => 'You do not have permission',
                            'customActionStatus' => 'danger',
                        ];
                    }
                    /**
                     * Delete pages
                     */
                    $result = $this->repository->delete($ids);
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
        $result = $this->repository->updatePage($id, $data);
        return response()->json($result, $result['response_code']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $this->assets
            ->addJavascripts([
                'jquery-ckeditor'
            ]);

        $this->setPageTitle('Create page');
        $this->breadcrumbs->addLink('Create page');

        $this->dis['object'] = $this->repository->getModel();

        $oldInputs = old();
        if ($oldInputs) {
            foreach ($oldInputs as $key => $row) {
                $this->dis['object']->$key = $row;
            }
        }

        return do_filter('pages.create.get', $this)->view('create', null, 'admin');
    }

    public function postCreate(CreatePageRequest $request)
    {
        $data = $this->parseDataUpdate($request);

        $data['created_by'] = $this->loggedInUser->id;

        $result = $this->repository->createPage($data);

        // do_action('pages.after-create.post', $result, $this);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back()->withInput();
        }

        if ($this->request->has('_continue_edit')) {
            return redirect()->to(route('pages.edit.get', ['id' => $result['data']->id]));
        }

        return redirect()->to(route('pages.index.get'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('This page not exists', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $item = do_filter('pages.before-edit.get', $item);

        $this->assets
            ->addJavascripts([
                'jquery-ckeditor'
            ]);

        $this->setPageTitle('Edit page', '#' . $item->id);
        $this->breadcrumbs->addLink('Edit page');

        $this->dis['object'] = $item;

        return do_filter('pages.edit.get', $this, $id)->view('edit', null, 'admin');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(UpdatePageRequest $request, $id)
    {
        $id = do_filter('pages.before-edit.post', $id);

        $item = $this->repository->find($id);

        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('This page not exists', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $data = $this->parseDataUpdate($request);

        $result = $this->repository->updatePage($id, $data);

        do_action('pages.after-edit.post', $id, $result, $this);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($this->request->has('_continue_edit')) {
            return redirect()->back();
        }

        return redirect()->to(route('pages.index.get'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDelete($id)
    {
        $id = do_filter('pages.before-delete.delete', $id);

        $result = $this->repository->deletePage($id);

        do_action('pages.after-delete.delete', $id, $result, $this);

        return response()->json($result, $result['response_code']);
    }

    protected function parseDataUpdate(Request $request)
    {
        return [
            'page_template' => $request->get('page_template', null),
            'status' => $request->get('status'),
            'title' => $request->get('title'),
            'slug' => ($request->get('slug') ? str_slug($request->get('slug')) : str_slug($request->get('title'))),
            'keywords' => $request->get('keywords'),
            'description' => $request->get('description'),
            'content' => $request->get('content'),
            'thumbnail' => $request->get('thumbnail'),
            'updated_by' => $this->loggedInUser->id,
            'order' => $request->get('order'),
        ];
    }
}
