<?php

namespace Controller;

use Security\Security;

use Service\UserService;

require_once './model/UserModel.php';
include_once './service/UserService.php'; 
include_once './security/Security.php'; 



if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


class UserController {
    private $userModel;

    // public function __construct() {
        
    // }

    public function handleRequest() {
        try{

            if (isset($_GET['action'])) {
                $action = $_GET['action'];
                // Protection CSRF pour toutes les actions POST
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    Security::verify();
                }
            

                switch ($action) {
                    case 'register':
                        $userService = new UserService();
                        $userService->handleRegisterAction();
                        break;
                    case 'login':
                        $userService = new UserService();
                        $userService->handleLoginAction();
                        break;
                    case 'dashboard':
                        include_once './view/dashboard.php';
                        break;
                    case 'update':
                        $userService = new UserService();
                        $userService->handleUpdateAction();
                        break;
                    case 'close':
                        $userService = new UserService();
                        $userService->handleCloseAction();
                        break;
                    case 'logout':
                        $userService = new UserService();
                        $userService->handleLogoutAction();
                        break;
                    default:
                        include_once './view/home.php';
                        break;
                }
            } else {
                include_once './view/home.php';
            }
        }catch(\Exception $e){
            $error_message = $e->getMessage();
            require_once './view/error.php';
        }
        
    }

}
