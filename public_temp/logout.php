<?php

require_once 'services/AuthorizationService.php';
AuthorizationService::logout();
header("Location: login.php");
exit;
