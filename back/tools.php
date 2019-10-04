<?php
//чистим текст
function clear($t) {
    $textfield = $t;
    $textfield = str_replace('`', '', $textfield);
    $textfield = str_replace('%', '', $textfield);
    $textfield = str_replace(',', '', $textfield);
    $textfield = str_replace('"', '', $textfield);
    $textfield = str_replace(';', '', $textfield);
    $textfield = str_replace(':', '', $textfield);
    $textfield = str_replace("'", '', $textfield);
    $textfield = htmlspecialchars($textfield);
    return trim($textfield);
}
//пишем логи
function logger($message) {    
        $logfile = dirname(__FILE__) . '/logs/' . basename(__FILE__, '.php') . '.log';
        if (is_array($message))
        {
            $message = print_r($message, true) . "\n";
        }
        else
        {
            $message = date("Y.m.d H:i:s") . " " . $message . "\n";
        }
        $exists = file_exists($logfile);
        file_put_contents($logfile, $message, FILE_APPEND);
        if (!$exists) chmod($logfile, 0664);    
}


?>