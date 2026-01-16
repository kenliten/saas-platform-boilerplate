<?php


if (!function_exists('log_info')) {
    function log_info($message)
    {
        $logsDir = __DIR__ . "../../storage/logs";
        error_log($message, 3, $logsDir . '/info.log');
    }

    function log_error($message)
    {
        $logsDir = __DIR__ . "../../storage/logs";
        error_log($message, 3, $logsDir . '/error.log');
    }
}