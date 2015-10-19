<?php
define("PHPIO_PROFILE", "_profile");

$is_enabled = (
    (isset($_REQUEST[PHPIO_PROFILE]) && $_REQUEST[PHPIO_PROFILE]) ||
    (isset($_COOKIE[PHPIO_PROFILE]) && $_COOKIE[PHPIO_PROFILE]) ||
    (isset($_SERVER[PHPIO_PROFILE]) && $_SERVER[PHPIO_PROFILE])
);

if ( !$is_enabled ) {
    return;
}

if (!extension_loaded('aop')) {
    error_log('phpio - either extension AOP must be loaded');
    return;
}

if (isset($_GET[PHPIO_PROFILE])) {
    //Give them a cookie to hold status, and redirect back to the same page
    setcookie(PHPIO_PROFILE, $_GET[PHPIO_PROFILE]);
    $redirect_url = str_replace(array(PHPIO_PROFILE.'=1',PHPIO_PROFILE.'=0'), '', $_SERVER['REQUEST_URI']);
    header("Location: $redirect_url");
    exit;
}

require __DIR__.'/boot.php';
PHPIO::hook();
