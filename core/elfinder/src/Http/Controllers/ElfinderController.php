<?php namespace App\Module\Elfinder\Http\Controllers;

use App\Module\Base\Http\Controllers\BaseAdminController;
use App\Module\Elfinder\Support\Connector;

class ElfinderController extends BaseAdminController
{
    protected $module = 'elfinder';

    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex()
    {
        $this->breadcrumbs->addLink('All files');

        $this->getDashboardMenu($this->module);
        
        $this->setPageTitle('All files');
        return do_filter($this->module . '.index.get', $this)->view('index', null, 'admin');
    }

    public function getStandAlone()
    {
        return do_filter($this->module . '.stand-alone.get', $this)->view('stand-alone', null, 'admin');
    }

    public function getElfinderView()
    {
        return do_filter($this->module . '.elfinder-view.get', $this)->view('elfinder-view', null, 'admin');
    }

    public function anyConnector()
    {
        $roots = config($this->module . '.roots', []);

        if (empty($roots)) {
            /**
            **@ Thiết lập thư mục cho mỗi thành viên khi upload
            ** Nếu là isSupperAdmin(Là người có toàn quyên) thì sẽ có thể truy cập được tất
            ** cả file upload kể cả của thành viên khác
            */
            $directUser = '';
            $viewAccess = $crudAccess = false;
            
            if($this->loggedInUser && !$this->loggedInUser->isSuperAdmin()) {
                $directUser = $this->loggedInUser->username;
                /**
                **@ Kiểm tra các quyền của thành viên: xem, upload, edit, delete
                */
                $viewAccess = $this->loggedInUser->hasPermission('view-files'); 
                $crudAccess = $this->loggedInUser->hasPermission('crud-files'); 
            }
            $dirs = (array)config($this->module . '.dir', ['uploads/'.$directUser]);
            if (!is_dir($dirs[0])) {
                mkdir($dirs[0], 0777, true);
            }

            /**
            **@ Kiểm tra quyền được xem của thành viên
            **@ TRUE thì được xem và ko thực hiện làm gì cả!
            **@ FALSE thì check Middleware để xem lại quyền có được phép truy cập trang ko/
            **@ Là isSupperAdmin thì ko sét viewÁccess nên return false! và check middleware xem lại có được tiếp tục ko!
            */
            if($viewAccess && !$crudAccess) {
                $accessControl = 'App\Module\Elfinder\Http\Controllers\ElfinderController::roaccess';
            } else {
                $this->middleware('has-permission:view-files,crud-files');
                $accessControl = 'App\Module\Elfinder\Http\Controllers\ElfinderController::checkAccess';
            }

            foreach ($dirs as $dir) {
                $path = $dir;
                $url = $dir;
                $roots[] = [
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path($path), // path to files (REQUIRED)
                    'tmpPath' => public_path($path),
                    'URL' => url($url), // URL to files (REQUIRED)
                    'accessControl' => $accessControl, // filter callback (OPTIONAL),
                    'autoload' => true,
                    'uploadDeny' => ['text/x-php', 'application/x-shockwave-flash'],
                    'uploadAllow' => [],
                    'uploadOrder' => ['deny', 'allow'],
                    'uploadOverwrite' => false,
                    'attributes' => [
                        [
                            'pattern' => '/\.(txt|html|php|py|pl|sh|xml|php|sh)$/i',
                            'read' => true,
                            'write' => false,
                            'locked' => false,
                            'hidden' => false
                        ]
                    ]
                ];
            }

            $disks = (array)config($this->module . '.disks', []);
            foreach ($disks as $key => $root) {
                if (is_string($root)) {
                    $key = $root;
                    $root = [];
                }
                $disk = app('filesystem')->disk($key);
                if ($disk instanceof \Illuminate\Filesystem\FilesystemAdapter) {
                    $defaults = [
                        'driver' => 'Flysystem',
                        'filesystem' => $disk->getDriver(),
                        'alias' => $key,
                    ];
                    $roots[] = array_merge($defaults, $root);
                }
            }
        }

        $opts = ['roots' => $roots, 'debug' => true];

        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    public function checkAccess($attr, $path, $data, $volume)
    {
        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            : null;                                    // else elFinder decide it itself
    }

    public static function roaccess($attr, $path, $data, $volume) {
        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
            ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
            : ($attr == 'read' || $attr == 'locked');   // else read only
    }

}
