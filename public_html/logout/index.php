<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: /login/");
?>