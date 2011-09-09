<?php
session_start();

include_once '../web-app/lib/SMVC/Autoload.php';

SMVC_Locale::setLocale('pt_BR');

SMVC_Request::startTemplate($_GET);

?>
