<?php namespace App\Module\Hook\Support;

abstract class AbstractHookEvent {
    /*
    ** @Lưu trữ các listeners sự kiện 
    ** @var array 
    ** @wr: listeners là các hành động được thực hiện khi sự kiện xảy ra
    */
    protected $listeners = [];

    /*
    ** @ Thêm một hành động
    ** @param string $hook Hook name
    ** @param mixed $callback function to execute
    ** @param integer $priority do uu tien hanh dong
    */
    public function addListener ( $hook, $callback, $priority ) {
        $this->listeners[$hook][$priority] = compact('callback');
    }

    /**
     * Gets a sorted list of all listeners
     * @return array
     */
     public function getListeners () {
         /**
         * Sort by priority
         */
         foreach( $this->listeners as $key => &$listeners ) {
             uksort($listeners, function ($param1, $param2) {
                 return strnatcmp($param1, $param2);
             });
         }
         return $this->listeners;
     }

    /**
     * Get the function
     * @param $callback
     * @return \Closure|array|null|mixed
     */
     public function getFunction ( $callback ) {
        if( is_string($callback) ) {
            if( strpos( $callback, '@' ) ) {
                $callback = explode('@', $callback);
                return [app('\\' . $callback[0]), $callback[1]];
            } else {
                return $callback;
            }
        } elseif ( $callback instanceof \Closure ) {
            return $callback;
        } elseif ( is_array($callback) && sizeof($callback) > 1 ) {
            return [app('\\' . $callback[0]), $callback[1]];
        }
        
        return null;
     }


    /**
     * Fires a new action
     * @param string $action Name of action
     * @param array $args Arguments passed to the action
     */
     abstract function fire ($action, array $args);
}