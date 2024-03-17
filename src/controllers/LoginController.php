<?php

use App\Config\ConfigApp;
use App\User\UserRepository;
use App\User\Registration\CheckLogin;

class LoginController
{
    public function getLoginView()
    {
        $app = new ConfigApp();
        include 'templates/login.php';
    }

    public function loginVerify()
    {
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salt = "vive le projet tweet_academy";

            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = hash("ripemd160", $salt . $_POST['password']);

            $userRep = new UserRepository();
            $userObj = $userRep->getUser(null, null, $email);
    
            if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
                $errors[] = 'L\'adresse e-mail est incorrecte.';
            }
    
            if (!$userObj) {
                $errors[] = 'L\'adresse e-mail n\'est pas reconnue.';
            }
    
            if (empty($errors)) {
                $login = new CheckLogin();
                $success = $login->validateUserLogin($email, $password);

                if ($success) {
                    echo json_encode(['success' => 'Connexion réussie.', 'redirect' => '/']);
                } else {
                    echo json_encode(['error' => 'Vos identifiants sont incorrects.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => $errors]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            header('location: /');
        }
    }

    public function setUserLogout()
    {
        $userRep = new UserRepository();
        $logout = $userRep->disconnectUser();

        if ($logout) {
            header('location: /');
        }
    }
}