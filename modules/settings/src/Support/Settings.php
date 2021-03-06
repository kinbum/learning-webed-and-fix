<?php namespace App\Module\Settings\Support;

use Illuminate\Support\Collection;
use App\Module\Settings\Repositories\Contracts\SettingContract;
use App\Module\Settings\Repositories\SettingRepository;

class Settings
{
/**
     * @var array
     */
    private $groups = [
        'basic' => [
            'title' => 'Basic',
            'priority' => 1,
            'items' => [],
        ],
        'advanced' => [
            'title' => 'Advanced',
            'priority' => 2,
            'items' => [],
        ],
    ];

    /**
     * @var array
     */
    protected $settings;

    public function __construct()
    {

    }

    /**
     * @return array
     */
    public function getSettingFromDB()
    {
        /**
         * @var SettingRepository $setting
         */
        $setting = app(SettingContract::class);

        $this->settings = $setting->getAllSettings();

        return $this->settings;
    }

    /**
     * @param string $groupKey
     * @param string $groupTitle
     * @return $this
     */
    public function addGroup($groupKey, $groupTitle, $priority = 999)
    {
        if (!isset($this->groups[$groupKey])) {
            $this->groups[$groupKey] = [
                'title' => $groupTitle,
                'priority' => $priority,
                'items' => [],
            ];
        }

        return $this;
    }

    public function getGroup()
    {
        return $this->groups;
    }

    /**
     * @param $groupKey
     * @param array $options
     * @return $this
     */
    public function modifyGroup($groupKey, $options = [])
    {
        if (isset($options['items'])) {
            unset($options['items']);
        }
        $this->groups[$groupKey] = array_merge($this->groups[$groupKey], $options);

        return $this;
    }

    /**
     * @param $name
     * @param $options
     * @param \Closure $htmlHelpersParams
     * @return $this
     */
    public function addSettingField($name, $options, \Closure $htmlHelpersParams)
    {
        $options = array_merge([
            'group' => 'basic',
            'type' => null,
            'priority' => 999,
            'label' => null,
            'helper' => null,
        ], $options);

        if (array_get($this->groups, $options['group']) === null) {
            $options['group'] = 'basic';
        }

        $this->groups[$options['group']]['items'][] = [
            'name' => $name,
            'type' => $options['type'],
            'priority' => $options['priority'],
            'params' => $htmlHelpersParams,
            'label' => array_get($options, 'label'),
            'helper' => array_get($options, 'helper'),
        ];

        return $this;
    }

    /**
     * @return Collection
     */
    public function export()
    {
        $settingGroup = collect($this->groups)->sortBy('priority')->toArray();

        foreach ($settingGroup as $key => $group) {
            $settingGroup[$key]['items'] = collect($group['items'])->sortBy('priority')->toArray();
        }

        return collect($settingGroup);
    }

    /**
     * @param null $key
     * @param null $default
     * @return array|string
     */
    public function getSetting($key = null, $default = null)
    {
        if ($this->settings === null) {
            $this->settings = $this->getSettingFromDB();
        }

        if ($key === null) {
            return $this->settings;
        }

        if (isset($this->settings[$key]) && $this->settings[$key]) {
            return $this->settings[$key];
        }

        return $default;
    }
}
