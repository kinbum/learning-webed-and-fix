<?php

if (!function_exists('webed_plugins_path')) {
    /**
     * @param string $path
     * @return string
     */
    function webed_plugins_path($path = '')
    {
        return base_path('plugins') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('webed_base_path')) {
    /**
     * @param string $path
     * @return string
     */
    function webed_base_path($path = '')
    {
        return base_path('modules') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('modules_management')) {
    /**
     * @return \App\Module\ModulesManagement\Support\ModulesManagement
     */
    function modules_management()
    {
        return \App\Module\ModulesManagement\Facades\ModulesManagementFacade::getFacadeRoot();
    }
}

if (!function_exists('get_base_vendor_modules_information')) {
    /**
     * @return array
     */
    function get_base_vendor_modules_information()
    {
        $modules = get_folders_in_path(base_path('modules'));
        $modulesArr = [];
        foreach ($modules as $row) {
            $file = $row . '/module.json';
            $data = json_decode(get_file_data($file), true);
            if ($data === null || !is_array($data)) {
                continue;
            }

            $modulesArr[array_get($data, 'namespace')] = array_merge($data, [
                'file' => $file,
                'type' => 'base',
            ]);
        }
        return $modulesArr;
    }
}

if (!function_exists('get_all_module_information')) {
    /**
     * @return array
     */
    function get_all_module_information()
    {
        $modulesArr = [];

        $canAccessDB = true;
        if (app()->runningInConsole()) {
            if (!check_db_connection() || !\Schema::hasTable('plugins')) {
                $canAccessDB = false;
            }
        }

        /**
         * @var \App\Module\ModulesManagement\Repositories\PluginsRepository $pluginRepo
         */
        $pluginRepo = app(\App\Module\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract::class);

        if ($canAccessDB) {
            $plugins = $pluginRepo->get();
        }

        foreach (['base', 'plugins'] as $type) {
            $modules = get_folders_in_path(base_path($type));
            foreach ($modules as $row) {
                $file = $row . '/module.json';
                $data = json_decode(get_file_data($file), true);
                if ($data === null || !is_array($data)) {
                    continue;
                }

                if ($canAccessDB) {
                    if ($type === 'plugins') {
                        $plugin = $plugins->where('alias', '=', array_get($data, 'alias'))->first();

                        if (!$plugin) {
                            $result = $pluginRepo
                                ->editWithValidate(0, [
                                    'alias' => array_get($data, 'alias'),
                                    'enabled' => false,
                                    'installed' => false,
                                ], true, true);
                            /**
                             * Everything ok
                             */
                            if (!$result['error']) {
                                $plugin = $result['data'];
                            }
                        }
                        if ($plugin) {
                            $data['enabled'] = !!$plugin->enabled;
                            $data['installed'] = !!$plugin->installed;
                            $data['id'] = $plugin->id;
                            $data['installed_version'] = $plugin->installed_version;
                        }
                    }
                }

                $modulesArr[array_get($data, 'namespace')] = array_merge($data, [
                    'file' => $file,
                    'type' => $type,
                ]);
            }
        }
        return array_merge(get_base_vendor_modules_information(), $modulesArr);
    }
}

if (!function_exists('get_module_information')) {
    /**
     * @param $alias
     * @return mixed
     */
    function get_module_information($alias)
    {
        return modules_management()->getAllModulesInformation()
            ->where('alias', '=', $alias)
            ->first();
    }
}

if (!function_exists('get_modules_by_type')) {
    /**
     * @param $type
     * @return mixed
     */
    function get_modules_by_type($type)
    {
        return modules_management()->getAllModulesInformation()
            ->where('type', '=', $type);
    }
}

if (!function_exists('save_module_information')) {
    /**
     * @param $alias
     * @param array $data
     * @return bool
     */
    function save_module_information($alias, array $data)
    {
        $module = is_array($alias) ? $alias : get_module_information($alias);
        if (!$module || array_get($module, 'type') !== 'plugins') {
            return false;
        }
        /**
         * @var \App\Module\ModulesManagement\Repositories\PluginsRepository $pluginRepo
         */
        $pluginRepo = app(\App\Module\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract::class);

        $result = $pluginRepo
            ->editWithValidate(array_get($module, 'id'), array_merge($data, [
                'alias' => array_get($module, 'alias'),
            ]), true, true);

        return !array_get($result, 'error');
    }
}


if (!function_exists('get_all_module_aliases')) {

    /**
     * @return array
     */
    function get_all_module_aliases()
    {
        return collect(get_all_module_information())
            ->pluck('alias')->unique()->all();
    }
}


if (!function_exists('module_relative_path')) {
    /**
     * @param string $alias
     * @return string
     */
    function module_relative_path($alias)
    {
        $module = get_module_information($alias);
        $path =str_replace('module.json', '', array_get($module, 'file', ''));
        $relativePath = str_replace(base_path() . '/', '', $path);

        return $relativePath;
    }
}