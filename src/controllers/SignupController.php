<?php

use App\Config\ConfigApp;
use App\User\UserRepository;
use App\User\Genre\GenreRepository;
use App\User\Registration\CheckSignup;

class SignupController
{
    public function getSignupView()
    {
        $app = new ConfigApp();

        $getGenres = new GenreRepository();
        $genres = $getGenres->getGenres();

        include 'templates/signup.php';
    }

    public function setRegistration()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $setUser = new CheckSignup();

            $genre = $_POST['genre'];
            $fullname = $_POST['fullname'];
            $pseudo = $_POST['pseudo'];
            $email = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $city = $_POST['city'];

            $salt = "vive le projet tweet_academy";
            $password = hash("ripemd160", $salt . $_POST['password']);

            $userRep = new UserRepository();
            $userObj = $userRep->getUser(null, null, $email);

            $actual_date = new DateTime();
            $actual_year = $actual_date->format('Y');
        
            $birthdate_value = new DateTime($birthdate);
            $birthdate_year = $birthdate_value->format('Y');

            if (!is_int($genre) && $genre < 1) {
                $errors[] = 'Veuillez spécifier votre genre.';
            }

            if (strlen($pseudo) < 3 || !preg_match('/^[A-Za-z0-9-_]+$/', $pseudo)) {
                $errors[] = 'Votre pseudonyme est incorrect.';
            }            

            if (!preg_match('/^[a-zA-Z]{2,}\s[a-zA-Z]{2,}$/', $fullname)) {
                $errors[] = 'Votre nom ou prénom est incorrect.';
            }

            if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
                $errors[] = 'Votre adresse e-mail est incorrecte.';
            }
            
            if ($userObj) {
                $errors[] = 'Cette adresse e-mail est déjà prise.';
            }

            if ($actual_year - $birthdate_year < 18) {
                $errors[] = 'Vous devez être majeur pour pouvoir vous inscrire sur le site.';
            }

            if (strlen($city) < 3) {
                $errors[] = 'La ville est incorrecte.';
            }

            if (!preg_match('/[\p{L}\p{N}\p{P}]{8,}/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }

            if (empty($errors)) {
                $success = $setUser->setUserRegistration($genre, $fullname, $pseudo, $email, $password, $birthdate, $city);

                if ($success) {
                    echo json_encode(['success' => 'Inscription réussie.', 'redirect' => '/login']);
                } else {
                    echo json_encode(['error' => 'Une erreur est survenue lors de l\'enregistrement.']);
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
}