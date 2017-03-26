<?php namespace App\Module\Auth\Http\Controllers;

use App\Module\Base\Http\Controllers\BaseController;
use App\Module\Auth\Support\Traits\Auth;
use App\Module\Auth\Http\Requests\AuthRequest;
use App\Module\Users\Repositories\Contracts\UserRepositoryContract;

class AuthController extends BaseController
{
    use Auth;

    protected $module = 'auth';
    public $username = 'username';
    public $loginPath = 'login';
    public $redirectTo;
    public $redirectPath;
    public $redirectToLoginPage;
    public $assets;
    /**
     * AuthController constructor.
     * @param \App\Module\Users\Repositories\UserRepository $userRepository
     */
    public function __construct ( UserRepositoryContract $userRepository ) {
        $this->middleware('ace.guest-admin', ['except' => ['getLogout']]);
        parent::__construct();

        $this->repository = $userRepository;
        $this->redirectTo = route('dashboard.index.get');
        $this->redirectPath = route('dashboard.index.get');
        $this->redirectToLoginPage = route('auth.login.get');

        // $this->assets = \Assets::getAssetsFrom('admin');
        $this->assets
            ->addStylesheetsDirectly([
                'admin/theme/lte/css/AdminLTE.min.css',
                'admin/css/style.css',
            ])
            ->addJavascriptsDirectly([
                'admin/theme/lte/js/app.js',
                'admin/js/webed-core.js',
                'admin/js/script.js',
            ], 'bottom');
    }

    /**
     * Show login page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        $this->setBodyClass('login-page');
        $this->setPageTitle('Login');

        return do_filter('admin.login', $this)->view('login', [], 'admin');
    }

    /**
     * @param AuthRequest $authRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postLogin ( AuthRequest $authRequest ) {
        return $this->login($this->request);
    }

    /**
     * Logout and redirect to login page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        $this->guard()->logout();

        session()->flush();

        session()->regenerate();

        return redirect()->to($this->redirectToLoginPage);
    }
}
