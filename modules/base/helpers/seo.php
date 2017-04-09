<?php

if (!function_exists('seo')) {
    /**
     * @return \WebEd\Base\Core\Support\SEO
     */
    function seo()
    {
        return \App\Module\Base\Facades\SeoFacade::getFacadeRoot();
    }
}
