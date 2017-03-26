<?php

if(! function_exists('assets_management')) {
    /*
    ** return Class Assets from facades
    */
    function assets_management () {
        return \App\Module\AssetManagement\Facades\AssetsFacade::getFacadeRoot();
    }
}

if(! function_exists('assets_list')) {
    function assets_list () {
        return assets_management()->getAssetsList();
    }
}