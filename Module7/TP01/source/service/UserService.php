<?php

namespace Service;

use Security\Security;
use Model\UserModel;

include_once './security/Security.php'; 
include_once './model/UserModel.php'; 


class UserService {

    private $userservice;
    private static $pdo;

    
    public function handleRegisterAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verify();

            // Récupérer les données du formulaire
            $nom = Security::sanitizeInput($_POST['nom']);
            $prenom = Security::sanitizeInput($_POST['prenom']);
            $adresse = Security::sanitizeInput($_POST['adresse']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Valider le formulaire
            $errors = Security::validateRegistrationForm($nom, $prenom, $adresse, $email, $password, $confirmPassword);

            if (!empty($errors)) {
                // Ajouter les erreurs au tableau de données pour les afficher dans le formulaire
                $data['errors'] = $errors;
                include_once './view/register.php';
            } else {
                // Appeler la fonction pour enregistrer l'utilisateur
                $error = new UserModel();
                $error->register($nom, $prenom, $adresse, $email, $password, $confirmPassword);
                
                // Si l'enregistrement est réussi, rediriger vers la page de connexion
                if ($error === true) {
                    header("Location: index.php?action=login.php");
                    exit();
                } else {
                    // En cas d'erreur, afficher le message d'erreur sur la page d'inscription
                    // Ajouter le message d'erreur au tableau de données pour l'afficher dans le formulaire
                    $data['error'] = $error;
                    include_once './view/register.php';
                }
            }
        } else {
            include_once './view/register.php';
        }
    }



    public function handleLoginAction() {
       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le jeton CSRF ici avant d'appeler loginUser
            Security::verify();

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $error = new UserModel();
            $error -> login($email, $password);

            if ($error === true) {
                header("Location: index.php?action=dashboard.php");
                exit();
            } else {
                include_once './view/login.php';
            }
        } else {
            include_once './view/login.php';
        }

    }

    public function handleUpdateAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier le jeton CSRF
            Security::verify();

            $id = $_SESSION['user_id'];
            $nom = Security::sanitizeInput($_POST['nom']);
            $prenom = Security::sanitizeInput($_POST['prenom']);
            $adresse = Security::sanitizeInput($_POST['adresse']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Valider le formulaire de mise à jour
            $errors = Security::validateRegistrationForm($nom, $prenom, $adresse, $email, $password, $confirmPassword);

            if (!empty($errors)) {
                $data['errors'] = $errors;
                include_once './view/update.php';
            } else {
                // Mettre à jour les informations utilisateur
                $error = new UserModel();
                $error->updateInfo($id, $nom, $prenom, $adresse, $email, $password, $confirmPassword);

                if ($error === true) {
                    header("Location: index.php?action=dashboard.php");
                    exit();
                } else {
                    include_once './view/update.php';
                }
            }
        } else {
            include_once './view/update.php';
        }
    }

    public function handleCloseAction() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user_id'];

            // Vérifier le jeton CSRF ici avant d'appeler closeAccount
            Security::verify();

            // Appeler la méthode closeAccount de UserManager pour fermer le compte
            $userservice = new UserModel();
            $userservice->closeAccount($id);

            // Détruire la session utilisateur
            session_destroy();

            // Rediriger vers la page d'accueil
            header("Location: index.php");
            exit();
        }
    }

    public function handleLogoutAction() {

        // Détruire la session
        session_destroy();

        // Rediriger vers la page d'accueil
        header("Location: index.php");
        exit();
    }
}
