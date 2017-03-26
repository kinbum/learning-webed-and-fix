<?php namespace App\Module\AssetManagement;

class Assets {
    /*
    ** @var array
    */
    protected $js = [];
    
    /*
    ** @var array
    */
    protected $css = [];
    
    /*
    ** @var array
    */
    protected $fonts = [];
    
    /**
     * @var int|mixed
     */
    protected $version;

    /*
    ** @var boolean
    ** Trả lại assets với phiên bản của ứng dụng
    */
    protected $withVersion = false;

    /*
    ** @var string
    */
    protected $getFrom;

    /*
    ** appends js
    */
    protected $appendedJs = [
        'top' => [],
        'bottom' => [],
    ];
    /*
    ** appends css
    */
    protected $appendedCss = [];

    public function __construct () {
        if(config('app.env') == 'production') {
            $version = config('ace.version', 1.0);
        } else {
            $version = time();
        }
        
        $this->withVersion = config('ace-assets.with_version');
        $this->version = $version;
    }

    /**
     * @param string $environment
     * @return App\Module\AssetManagement\Assets
     */
     public function getAssetsFrom ($environment = 'admin') {
         $this->getFrom = $environment;
         $this->js = array_merge( config('ace-assets.default.'. $environment . '.js'), $this->js );
         $this->css = array_merge( config('ace-assets.default.'. $environment . '.css'), $this->css);
         $this->fonts = array_merge( config('ace-assets.default.'. $environment . '.fonts'), $this->fonts);
         return $this;
     }

    /**
     * @param bool $bool
     * @return \App\module\AssetsManagement\Assets
     */
     public function withVersion ($bool = true) {
         $this->withVersion = (bool)$bool;
         return $this;
     }

    /**
     * Add javascript vào module hiện tại
     *
     * @param array|string $assets
     * @return \App\module\AssetsManagement\Assets
     */
     public function addJavascripts ($assets) {
         $this->js = array_unique( array_merge($this->js, (array)$assets) );
         return $this;
     }

    /**
     * Add stylesheets vào module hiện tại
     *
     * @param array|string $assets
     * @return \App\module\AssetsManagement\Assets
     */
     public function addStylesheets ($assets) {
         $this->css = array_unique( array_merge($this->css, (array)$assets) );
         return $this;
     }

    /**
     * Add fonts vào module hiện tại
     *
     * @param array|string $assets
     * @return \App\module\AssetsManagement\Assets
     */
     public function addFonts ($assets) {
         $this->fonts = array_unique( array_merge($this->fonts, (array)$assets) );
         return $this;
     }

     /**
     ** Add stylesheets trực tiếp
     * @param array|string $assets
     * @return \App\module\AssetsManagement\Assets
     */
     public function addStylesheetsDirectly ($assets) {
         if ( ! is_array($assets) ) {
             $assets = func_get_args();
         }
         $this->appendedCss = array_merge($this->appendedCss, $assets);
         return $this;
     }

     /**
     ** Add stylesheets trực tiếp
     * @param array|string $assets
     * @param string $location
     * @return \App\module\AssetsManagement\Assets
     */
     public function addJavascriptsDirectly ($assets, $location = 'bottom') {
         $js = array_merge((array)$assets, $this->appendedJs[$location]);
         $this->appendedJs[$location] = $js;
         return $this;
     }

    /**
     * Remove stylesheets module
     *
     * @param array|string $assets
     * @return \App\module\AssetsManagement\Assets
     */

     public function removeStylesheets ($assets) {
         foreach ( (array)$assets as $remove ) {
             unset( $this->css[array_search($remove, $this->css)] );
         }
         return $this;
     }

    /**
     * Remove javascript module
     *
     * @param array|string $assets
     * @return \App\module\AssetsManagement\Assets
     */

     public function removeJavascripts ($assets) {
         foreach ( (array)$assets as $remove ) {
             unset( $this->js[array_search($remove, $this->js)] );
         }
         return $this;
     }

    /**
     * @param $replace
     * @param $with
     * @param string|array $type
     * @return \App\module\AssetsManagement\Assets
     */
     public function replace ($replace, $with, $type = 'js') {
         if( is_array($type) ) {
             foreach ( array_unique($type) as $row ) {
                 $this->replace($replace, $with, $row);
             }
         }

         switch ($type) {
             case 'css' :
                $this->css[array_search($replace, $this->css)] = $with;
                break; 
             case 'fonts' :
                $this->fonts[array_search($replace, $this->fonts)] = $with;
                break; 
             case 'js' :
                $this->js[array_search($replace, $this->js)] = $with;
                break; 
         }

         return $this;
     }

    /**
     * @return string
     */

     public function renderStylesheets () {
         $data = null;
         $version = null;

         if( $this->withVersion ) {
             $version .= '?version='. $this->version;
         }
         foreach ($this->fonts as $row) {
            foreach ($this->getResourceUrls(array_get(config('ace-assets.resources.fonts', []), $row)) as $resource) {
                $data .= PHP_EOL . '<link rel="stylesheet" type="text/css" href="' . $resource . $version . '"/>';
            }
        }
        foreach ($this->css as $row) {
            foreach ($this->getResourceUrls(array_get(config('ace-assets.resources.css', []), $row)) as $resource) {
                $data .= PHP_EOL . '<link rel="stylesheet" type="text/css" href="' . $resource . $version . '"/>';
            }
        }
        foreach ($this->appendedCss as $row) {
            $data .= PHP_EOL . '<link rel="stylesheet" type="text/css" href="' . $row . $version . '"/>';
        }
        return $data;
     }

    /**
     * @param location
     * @return string
     */

     public function renderScripts ( $location ) {
         $data = null;
         $version = null;

        if( $this->withVersion ) {
            $version .= '?version='. $this->version;
        }
        foreach ($this->js as $row) {
            foreach ($this->getResourceUrls(array_get(config('ace-assets.resources.js', []), $row), $location) as $resource) {
                $data .= PHP_EOL . '<script type="text/javascript" src="' . $resource . $version . '"></script>';
            }
        }
        if (isset($this->appendedJs[$location])) {
            foreach ($this->appendedJs[$location] as $row) {
                $data .= PHP_EOL . '<script type="text/javascript" src="' . $row . $version . '"></script>';
            }
        }
        return $data;
     }

    /**
     * @param array $resources
     * @param null $location
     * @return array
     */
     protected function getResourceUrls ( array $resources, $location = null ) {
         $isUseCdn = ( config('ace-assets.always_use_local') ) ? false : (array_get( $resources, 'use_cdn', false ));
         if ( $isUseCdn ) {
             $data = (array)array_get($resources, 'src.cdn');
         } else {
             $data = (array)array_get($resources, 'src.local');
         }

         if ( !$location ) {
             return $data;
         }

         /**
         * Check matched location
         */
         if ( array_get($resources, 'location') === $location ) {
             return $data;
         }
         return [];
     }

     /**
     * Determine when an asset loaded - Xác định khi một tài sản nạp
     * @param string $assetName
     * @param string $type
     * @return bool
     */
     public function assetLoaded($assetName, $type) {
        if (in_array($assetName, $this->$type)) {
            return true;
        }
        return false;
     }
     
    /**
     * @return array
     */
    public function getAssetsList()
    {
        return [
            'js' => [
                $this->appendedJs,
                $this->js,
            ],
            'css' => [
                $this->appendedCss,
                $this->css,
            ],
            'fonts' => [
                $this->fonts
            ]
        ];
    }
}