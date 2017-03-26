<?php namespace App\Module\Base\Providers;

use Illuminate\Support\ServiceProvider;

use Form;
use Html;

class CollectiveServiceProvider extends ServiceProvider {
    public function register () {
       // 
    }

    public function boot () {
        $this->registerFormComponents();
        $this->registerHtmlComponents();
    }

    private function registerFormComponents () {
        /**
         * Custom checkbox
         * Every checkbox will not have the same name
         */
        Form::component('customCheckbox', 'base::admin._components.custom-checkbox', [
            /**
             * @var array $values
             * @template: [
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             * ]
             */
            'values',
        ]);

        /**
         * Custom radio
         * Every radio in list must have the same name
         */
        Form::component('customRadio', 'base::admin._components.custom-radio', [
            /**
             * @var string $name
             */
            'name',
            /**
             * @var array $values
             * @template: [
             *      [string $value, string $label, bool $disabled],
             *      [string $value, string $label, bool $disabled],
             *      [string $value, string $label, bool $disabled],
             * ]
             */
            'values',
            /**
             * @var string|null $selected
             */
            'selected' => null,
        ]);

        /**
         * Select image box
         */
        Form::component('selectImageBox', 'base::admin._components.select-image-box', [
            'name',
            'value' => null,
            'thumbnail' => null,
            'label' => null,
        ]);

        /**
         * Select file box
         */
        Form::component('selectFileBox', 'base::admin._components.select-file-box', [
            'name',
            'value' => null,
            'thumbnail' => null,
            'label' => null,
        ]);
    }

    private function registerHtmlComponents () {
        /**Label*/
        Html::component('label', 'base::admin._components.label', [
            'text',
            'type' => 'default',
            'tag' => 'span',
        ]);
        
        /**
         * Note
         */
        Html::component('note', 'base::admin._components.note', [
            'text',
            'type' => 'default',
            'dismissable' => true,
        ]);
    }
}