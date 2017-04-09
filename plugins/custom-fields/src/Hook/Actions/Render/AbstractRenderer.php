<?php namespace App\Plugins\CustomFields\Hook\Actions\Render;

use App\Module\Base\Models\Contracts\BaseModelContract;
use App\Module\Base\Models\EloquentBase;
use App\Module\Users\Models\User;

abstract class AbstractRenderer
{
    public function __construct()
    {
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = auth()->user();

        $roles = [];
        foreach ($loggedInUser->roles()->select('id')->get() as $role) {
            $roles[] = $role->id;
        }

        /**
         * Every models will have these rules by default
         */
        add_custom_field_rules([
            'logged_in_user' => $loggedInUser->id,
            'logged_in_user_has_role' => $roles
        ]);
    }

    /**
     * @param string $type
     * @param EloquentBase $item
     */
    public function render($type, BaseModelContract $item)
    {
        $customFieldBoxes = get_custom_field_boxes($item, $item->id);

        if (!$customFieldBoxes) {
            return;
        }

        $view = view('custom-fields::admin.custom-fields-boxes-renderer', [
            'customFieldBoxes' => json_encode($customFieldBoxes),
        ])->render();

        echo $view;
    }
}
