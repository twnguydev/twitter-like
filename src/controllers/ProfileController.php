<?php

use App\Config\Auth;
use App\Feature\Follow\FollowRepository;
use App\Feature\Post\PostRepository;
use App\Feature\Post\Like\LikeRepository;
use App\Feature\Post\Retweet\RetweetRepository;
use App\User\Registration\CheckLogin;
use App\User\UserRepository;

class ProfileController
{
    public function getProfileView($params)
    {
        $pseudo = $params['pseudo'];

        $userRep = new UserRepository();
        $user = $userRep->getUser(null, $pseudo, null);
        $userLogged = $userRep->isUserLogged();

        $auth = new Auth();
        if ($auth->checkAccess($user->id, $userLogged->id)) {
            $access = true;
        }

        if ($user && $userLogged) {
            $postRep = new PostRepository();
            $posts = $postRep->getPostsLinkedToUser($user->id);
        
            $likeRep = new LikeRepository();
            $retweetRep = new RetweetRepository();
        
            foreach ($posts as $post) {
                $post->activityType = 'created';

                if ($likeRep->isLiked($user->id, $post->id)) {
                    $post->activityType = 'liked';
                }

                if ($retweetRep->isRetweeted($user->id, $post->id)) {
                    $post->activityType = 'retweeted';
                }
        
                if ($likeRep->isLiked($userLogged->id, $post->id)) {
                    $post->isLiked = true;
                } else {
                    $post->isLiked = false;
                }
        
                if ($retweetRep->isRetweeted($userLogged->id, $post->id)) {
                    $post->isRetweeted = true;
                } else {
                    $post->isRetweeted = false;
                }
            }
        
            $followRep = new FollowRepository();
            $count_followers = $followRep->countAllFollowers($user->id);
            $count_followings = $followRep->countAllFollowings($user->id);
        
            $is_following = $followRep->isFollowing($userLogged->id, $user->id);
            $is_following = $userLogged->id !== $user->id ? $is_following : null;
        
            include 'templates/profile.php';
        } else {
            throw new Exception('Profil introuvable.');
        }
    }

    public function setProfileUpdate($params)
    {
        $pseudo = $params['pseudo'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userRep = new UserRepository();
            $userObj = $userRep->getUser(null, $pseudo, null);

            $loginRep = new CheckLogin();

            $errors = [];
            $biography = null;
            $username = null;
            $new_pseudo = null;
            $email = null;
            $password = null;
            $new_password_hash = null;
            $city = null;

            if (isset($_POST['biography'])) {
                $biography = $_POST['biography'];

                if (strlen($biography) > 140) {
                    $errors[] = 'La biographie ne doit pas dépasser 140 caractères.';
                }
            }

            if (isset($_POST['username'])) {
                $username = $_POST['username'];

                if (!preg_match('/^[a-zA-Z]{2,}\s[a-zA-Z]{2,}$/', $username)) {
                    $errors[] = 'Le nom ou prénom est incorrect.';
                }
            }

            if (isset($_POST['pseudo'])) {
                $new_pseudo = $_POST['pseudo'];

                if (strlen($new_pseudo) < 3) {
                    $errors[] = 'Le pseudonyme est incorrect.';
                }

                if ($userObj && $userObj->pseudo === $new_pseudo) {
                    $errors[] = 'Le pseudonyme est déjà pris.';
                }
            }

            if (isset($_POST['email'])) {
                $email = $_POST['email'];

                if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
                    $errors[] = 'L\'adresse e-mail est incorrecte.';
                }

                if ($userObj && $userObj->email === $email) {
                    $errors[] = 'L\'adresse e-mail est déjà prise.';
                }
            }

            if (isset($_POST['password'])) {
                $salt = "vive le projet tweet_academy";
                $password = hash("ripemd160", $salt . $_POST['password']);

                if ($userObj && $password !== $loginRep->getUserPassword($userObj->id)) {
                    $errors[] = 'Votre mot de passe initial est incorrect.';
                }
            }

            if (isset($_POST['confirmPassword'])) {
                $confirmPassword = $_POST['confirmPassword'];

                if (isset($password) && strlen($confirmPassword) < 8) {
                    $errors[] = 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
                } else {
                    $salt = "vive le projet tweet_academy";
                    $new_password_hash = hash("ripemd160", $salt . $confirmPassword);
                }
            } elseif ($password !== null) {
                $errors[] = 'Veuillez confirmer votre nouveau mot de passe.';
            } else {
                $new_password_hash = $password;
            }

            if (isset($_POST['city'])) {
                $city = $_POST['city'];

                if (strlen($city) < 3) {
                    $errors[] = 'La ville est incorrecte.';
                }
            }

            if (empty($errors)) {
                $success = $userRep->setProfileUpdate($userObj->id, $biography, $username, $new_pseudo, $email, $city, $new_password_hash);
                if ($success) {
                    echo json_encode(['success' => 'Modification réussie.', 'redirect' => '/profile/' . ($new_pseudo !== null ? $new_pseudo : $pseudo)]);
                } else {
                    echo json_encode(['error' => 'Une erreur est survenue lors du traitement de la demande.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => $errors]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            header('location: /profile/' . $pseudo);
        }
    }

    public function setProfilePhotoUpdate($params)
    {
        $pseudo = $params['pseudo'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userRep = new UserRepository();
            $userObj = $userRep->getUser(null, $pseudo, null);

            $errors = [];
            $photo = null;
            $banner = null;

            if (isset($_FILES['photo'])) {
                $photo = $_FILES['photo'];

                if ($photo['size'] > 1000000) {
                    $errors[] = 'La taille de l\'image ne doit pas dépasser 1 Mo.';
                }

                if (!in_array($photo['type'], ['image/jpeg', 'image/png'])) {
                    $errors[] = 'Le format de l\'image doit être JPEG ou PNG.';
                }
            }

            if (isset($_FILES['banner'])) {
                $banner = $_FILES['banner'];

                if ($banner['size'] > 1000000) {
                    $errors[] = 'La taille de l\'image ne doit pas dépasser 1 Mo.';
                }

                if (!in_array($banner['type'], ['image/jpeg', 'image/png'])) {
                    $errors[] = 'Le format de l\'image doit être JPEG ou PNG.';
                }
            }

            if (empty($errors)) {
                $image_link = null;
                $banner_link = null;

                if ($photo && isset($photo['tmp_name']) && isset($photo['name'])) {
                    $image_path = $photo['tmp_name'];
                    $image_filename = $photo['name'];

                    $filename_hash = hash('sha256', $image_filename);
                    $file_extension = pathinfo($image_filename, PATHINFO_EXTENSION);
                    $new_image_filename = $pseudo . '_' . $filename_hash . '.' . $file_extension;
                
                    $image_target_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/profiles/' . $new_image_filename;

                    if (move_uploaded_file($image_path, $image_target_path)) {
                        $this->resizeImage($image_target_path, 400, 400);

                        $image_link = '/assets/profiles/' . $new_image_filename;
                    }
                }

                if ($banner && isset($banner['tmp_name']) && isset($banner['name'])) {
                    $banner_path = $banner['tmp_name'];
                    $banner_filename = $banner['name'];

                    $filename_hash = hash('sha256', $banner_filename);
                    $file_extension = pathinfo($banner_filename, PATHINFO_EXTENSION);
                    $new_banner_filename = $pseudo . '_' . $filename_hash . '.' . $file_extension;

                    $banner_target_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/banners/' . $new_banner_filename;

                    if (move_uploaded_file($banner_path, $banner_target_path)) {
                        $banner_link = '/assets/banners/' . $new_banner_filename;
                    }
                }

                $success = $userRep->setProfilePhoto($userObj->id, $image_link, $banner_link);

                if ($success) {
                    http_response_code(200);
                    echo json_encode(['success' => 'Photo reçue avec succès', 'image' => $image_link ?? 'null', 'banner' => $banner_link ?? 'null']);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Une erreur est survenue lors de l\'enregistrement de la photo.']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => $errors]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            header('location: /profile/' . $pseudo);
        }
    }

    public function resizeImage($filePath, $newWidth, $newHeight)
    {
        list($width, $height, $type) = getimagesize($filePath);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($filePath);
                break;
            default:
                return false;
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($resizedImage, $filePath);
                break;
            case IMAGETYPE_PNG:
                imagepng($resizedImage, $filePath);
                break;
        }

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return true;
    }
}
