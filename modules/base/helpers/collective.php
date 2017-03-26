<?php

use Collective\Html\HtmlFacade;
use Collective\Html\FormFacade;

if ( !function_exists('html') ) {
    function html () {
        return HtmlFacade::getFacadeRoot();
    }
}

if ( !function_exists('form') ) {
    function form () {
        return FormFacade::getFacadeRoot();
    }
}