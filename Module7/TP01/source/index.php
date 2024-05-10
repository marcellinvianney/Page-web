<?php

use Controller\UserController;

include_once 'controller/UserController.php';
include_once 'view/parts/header.php';

$userController = new UserController();
$userController->handleRequest();

// include_once 'view/parts/footer.php';

