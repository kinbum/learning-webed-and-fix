<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;
use App\Module\Settings\Http\Controllers\SettingController;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
       app()->booted(function () {
            $this->request = request();

            $this->booted();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      
    }

    private function booted()
    {
        add_filter('settings.before-edit.post', function ($data, SettingController $controller) {
            if($controller->request->get('_tab') === 'advanced') {
                $data['construction_mode'] = (int)($this->request->has('construction_mode'));
                $data['show_admin_bar'] = (int)($this->request->has('show_admin_bar'));
            }
            return $data;
        });
    }
}
