<?php

if (!function_exists('breadcrumbs')) {
    /**
     * @return \WebEd\Base\Core\Support\Breadcrumbs
     */
    function breadcrumbs()
    {
        return \App\Module\Base\Facades\BreadcrumbsFacade::getFacadeRoot();
    }
}