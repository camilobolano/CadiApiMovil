<?php

require __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER["REQUEST_URI"]);

$uri = explode( "/", $uri["path"] );
if ((isset($uri[3]) && $uri[3] != 'user') || !isset($uri[4])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$objFeedController = new UserController();
$strMethodName = $uri[4] . 'Action';

if (method_exists($objFeedController, $strMethodName)) {
    $objFeedController->{$strMethodName}();
} else {
    header("HTTP/1.1 404 Not Found");
    exit();
}


