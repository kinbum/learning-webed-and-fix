<?php namespace App\Module\Users\Http\Controllers;

use App\Module\Acl\Repositories\Contracts\RoleRepositoryContract;
use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Module\Users\Http\DataTables\UsersListDataTable;
use App\Module\Users\Http\Requests\CreateUserRequest;
use App\Module\Users\Http\Requests\UpdateUserPasswordRequest;
use App\Module\Users\Http\Requests\UpdateUserRequest;
use App\Module\Users\Repositories\Contracts\UserRepositoryContract;
use App\Module\Users\Repositories\UserRepository;

class UserController extends BaseAdminController
{
    protected $module = 'users';

    /**
     * @var \App\Module\Users\Repositories\UserRepository
     */
    protected $repository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepositoryContract $userRepository)
    {
        parent::__construct();
        $this->repository = $userRepository;
        // $this->breadcrumbs->addLink('Users', route('users.index.get'));

        $this->getDashboardMenu($this->module);
    }

    public function getIndex(UsersListDataTable $usersListDataTable)
    {
        $this->setPageTitle('All users');
        $this->dis['dataTable'] = $usersListDataTable->run();
        return do_filter('users.index.get', $this)->view('index', null, 'admin');
    }

    /**
     * Get data for DataTable
     * @param UsersListDataTable|BaseEngine $usersListDataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function postListing(UsersListDataTable $usersListDataTable)
    {
        $data = $usersListDataTable->with($this->groupAction());

        return do_filter('datatables.users.index.post', $data, $this);
    }

    /**
     * Handle group actions
     * @return array
     */
    private function groupAction()
    {
        $data = [];
        if ($this->request->get('customActionType', null) == 'group_action') {
            $actionValue = $this->request->get('customActionValue', 'activated');

            if (!$this->repository->hasPermission($this->loggedInUser, ['edit-other-users'])) {
                return [
                    'customActionMessage' => 'You do not have permission',
                    'customActionStatus' => 'danger',
                ];
            }

            $ids = collect($this->request->get('id', []))->filter(function ($value, $index) {
                return (int)$value !== (int)$this->loggedInUser->id;
            })->toArray();

            switch ($actionValue) {
                case 'deleted':
                    if (!$this->repository->hasPermission($this->loggedInUser, ['delete-users'])) {
                        $data['customActionMessage'] = 'You do not have permission';
                        $data['customActionStatus'] = 'danger';
                        return $data;
                    }
                    $result = $this->repository->delete($ids);
                    break;
                default:
                    $result = $this->repository->updateMultiple($ids, [
                        'status' => $actionValue,
                    ], true);
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

        if ($this->loggedInUser->id == $id) {
            $result = response_with_messages('You cannot update status of yourself', true, \Constants::ERROR_CODE);
        } else {
            $result = $this->repository->updateUser($id, $data);
        }
        return response()->json($result, $result['response_code']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $this->setPageTitle('Create user');
        // $this->breadcrumbs->addLink('Create user');

        $this->dis['isLoggedInUser'] = false;
        $this->dis['isSuperAdmin'] = $this->loggedInUser->isSuperAdmin();

        $this->dis['object'] = $this->repository->getModel();

        $oldInputs = old();
        if ($oldInputs) {
            foreach ($oldInputs as $key => $row) {
                $this->dis['object']->$key = $row;
            }
        }

        $this->assets
            ->addStylesheets('bootstrap-datepicker')
            ->addJavascripts('bootstrap-datepicker')
            ->addJavascriptsDirectly('admin/modules/users/user-profiles/user-profiles.js')
            ->addStylesheetsDirectly('admin/modules/users/user-profiles/user-profiles.css');

        return do_filter('users.create.get', $this)->view('create', [], 'admin');
    }

    public function postCreate(CreateUserRequest $request)
    {
        $data = $request->except([
            '_token', '_continue_edit', '_tab', 'roles',
        ]);

        if ($request->exists('birthday') && !$request->get('birthday')) {
            $data['birthday'] = null;
        }

        $data['created_by'] = $this->loggedInUser->id;
        $data['updated_by'] = $this->loggedInUser->id;

        $result = $this->repository->createUser($data);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back()->withInput();
        }

        do_action('users.after-create.post', $result['data']->id, $result, $this);

        if ($request->has('_continue_edit')) {
            return redirect()->to(route('users.edit.get', ['id' => $result['data']->id]));
        }

        return redirect()->to(route('users.index.get'));
    }

    public function getEdit(RoleRepositoryContract $roleRepository, $id)
    {
        $this->dis['isLoggedInUser'] = (int)$this->loggedInUser->id === (int)$id ? true : false;
        $this->dis['isSuperAdmin'] = $this->loggedInUser->isSuperAdmin();

        if ((int)$this->loggedInUser->id !== (int)$id) {
            if (!$this->repository->hasPermission($this->loggedInUser, ['edit-other-users'])) {
                abort(\Constants::FORBIDDEN_CODE);
            }
        }

        $item = $this->repository->find($id);

        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('User not found', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $this->setPageTitle('Edit user', '#' . $id);
        // $this->breadcrumbs->addLink('Edit user');

        $this->dis['object'] = $item;

        if (!$this->dis['isLoggedInUser'] && ($this->dis['isSuperAdmin'] || $this->loggedInUser->hasPermission(['assign-roles']))) {
            $roles = $roleRepository->get();

            $checkedRoles = $item->roles()->allRelatedIds()->toArray();

            $resolvedRoles = [];
            foreach ($roles as $role) {
                $resolvedRoles[] = [
                    'roles[]', $role->id, $role->name, (in_array($role->id, $checkedRoles))
                ];
            }
            $this->dis['roles'] = $resolvedRoles;
        }

        $this->assets
            ->addStylesheets('bootstrap-datepicker')
            ->addJavascripts('bootstrap-datepicker')
            ->addJavascriptsDirectly('admin/modules/users/user-profiles/user-profiles.js')
            ->addStylesheetsDirectly('admin/modules/users/user-profiles/user-profiles.css');

        return do_filter('users.edit.get', $this, $id)->view('edit', null, 'admin');
    }

    public function postEdit(UpdateUserRequest $request, $id)
    {
        $user = $this->repository->find($id);

        if (!$user) {
            $this->flashMessagesHelper
                ->addMessages('User not found', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        if ((int)$this->loggedInUser->id !== (int)$id) {
            if (!$this->loggedInUser->hasPermission('edit-other-users')) {
                abort(\Constants::FORBIDDEN_CODE);
            }
        }
        if ($this->request->exists('roles')) {
            if (!$this->loggedInUser->hasPermission('assign-roles')) {
                abort(\Constants::FORBIDDEN_CODE);
            }
        }

        $data = $this->request->except([
            '_token', '_continue_edit', '_tab', 'username', 'email', 'roles'
        ]);

        if ($request->requestHasRoles()) {
            $data['roles'] = $request->getResolvedRoles();
        } else {
            if ($this->request->get('_tab') === 'roles') {
                $data['roles'] = [];
            }
        }
        if ($this->request->exists('birthday') && !$this->request->get('birthday')) {
            $data['birthday'] = null;
        }

        /**
         * Prevent current users edit their roles
         */
        $isLoggedInUser = (int)$this->loggedInUser->id === (int)$id ? true : false;
        if ($isLoggedInUser) {
            if ($this->request->exists('roles')) {
                unset($data['roles']);
            }
        }

        $data['updated_by'] = $this->loggedInUser->id;

        return $this->updateUser($user, $data);
    }

    public function postUpdatePassword(UpdateUserPasswordRequest $request, $id)
    {
        $user = $this->repository->find($id);

        return $this->updateUser($user, [
            'password' => $request->get('password'),
        ]);
    }

    protected function updateUser($user, $data)
    {
        if (!$user) {
            $this->flashMessagesHelper
                ->addMessages('User not found', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $result = $this->repository->updateUser($user, $data);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back();
        }

        do_action('users.after-edit.post', $user->id, $result, $this);

        if ($this->request->has('_continue_edit')) {
            return redirect()->back();
        }

        return redirect()->to(route('users.index.get'));
    }

    public function deleteDelete($id)
    {
        if ($this->loggedInUser->id == $id) {
            $result = response_with_messages('You cannot delete yourself', true, \Constants::ERROR_CODE);
        } else {
            $result = $this->repository->delete($id);
        }
        return response()->json($result, $result['response_code']);
    }

    public function deleteForceDelete($id)
    {
        if ($this->loggedInUser->id == $id) {
            $result = response_with_messages('You cannot delete yourself', true, \Constants::ERROR_CODE);
        } else {
            $result = $this->repository->forceDelete($id);
        }
        return response()->json($result, $result['response_code']);
    }

    public function postRestore($id)
    {
        $result = $this->repository->restore($id);
        return response()->json($result, $result['response_code']);
    }
}
