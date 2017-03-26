<?php
use App\Module\Hook\Support\Facades\ActionsFacade;
use App\Module\Hook\Support\Facades\FiltersFacade;


if( !function_exists('add_action') ) {
    function add_action ( $hook, $callback, $priority = 20 ) {
        ActionsFacade::addListener( $hook, $callback, $priority );
    }
}


if( !function_exists('do_action') ) {
    function do_action ( $hookName, ...$args ) {
        ActionsFacade::fire( $hookName, $args );
    }
}


if( !function_exists('add_filter') ) {
    function add_filter ( $hook, $callback, $priority = 20 ) {
        FiltersFacade::addListener( $hook, $callback, $priority );
    }
}


if( !function_exists('do_filter') ) {
    function do_filter ( $hookName, ...$args ) {
        return FiltersFacade::fire( $hookName, $args );
    }
}


if( !function_exists('get_hooks') ) {
    function get_hooks ( $name = null, $isFilter = true ) {
        if ( $isFilter == true ) {
            $listeners = FiltersFacades::getListeners();
        } else {
            $listeners = ActionsFacades::getListeners();
        }

        if ( empty($name) ) {
            return $listeners;
        }
        return array_get($listeners, $name);
    }
}

