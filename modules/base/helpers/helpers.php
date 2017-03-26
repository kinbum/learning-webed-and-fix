<?php

if (!function_exists('is_in_dashboard')) {
    /**
     * @return bool
     */
    function is_in_dashboard()
    {
        $segment = request()->segment(1);
        if ($segment === config('ace.admin_prefix', 'ace-panel')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_image')) {
    /**
     * @param $fields
     * @param $updateTo
     */
    function get_image($image, $default = '/admin/images/no-image.png')
    {
        if (!$image || !trim($image)) {
            return $default;
        }
        return $image;
    }
}

if (!function_exists('convert_timestamp_format')) {
    /**
     * @param $dateTime
     * @param $format
     * @return string
     */
    function convert_timestamp_format($dateTime, $format = 'Y-m-d H:i:s')
    {
        if ($dateTime == '0000-00-00 00:00:00') {
            return null;
        }
        $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
        return $date->format($format);
    }
}