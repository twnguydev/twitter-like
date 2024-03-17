<?php

session_start();

use App\Feature\Post\PostRepository;
use App\Feature\Post\Comment\CommentRepository;
use App\Config\ConfigApp;
use App\User\UserRepository;

class PostController extends PostManager
{
    const UPLOAD_DIR = 'assets/uploads/';
    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    public function setPostRegistration()
    {
        $userRep = new UserRepository();

        $temp_photos = [];
        $errors = [];
        $links = [];

        $app = new ConfigApp();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['photos'])) {
                $uploaded_photos = $_FILES['photos'];

                if (!file_exists(self::UPLOAD_DIR)) {
                    mkdir(self::UPLOAD_DIR, 0777, true);
                }

                for ($i = 0; $i < count($uploaded_photos['name']); $i++) {
                    $file_name = uniqid('img_') . '_' . time() . '.' . pathinfo($uploaded_photos['name'][$i], PATHINFO_EXTENSION);
                    $file_tmp_size = $uploaded_photos['tmp_name'][$i];
                    $file_size = $uploaded_photos['size'][$i];
                    $file_error = $uploaded_photos['error'][$i];

                    if ($file_error === UPLOAD_ERR_OK) {
                        if ($file_size <= self::MAX_FILE_SIZE) {
                            $destination = self::UPLOAD_DIR . $file_name;
                            move_uploaded_file($file_tmp_size, $destination);

                            $short_url = $this->generateShortUrl($file_name);

                            $temp_photos[$i] = [
                                'hash' => $short_url,
                                'file_path' => $destination
                            ];

                            $links[] = $app->app_url . 'img/' . $short_url;
                        } else {
                            http_response_code(400);
                            echo json_encode(['error' => "La taille du fichier {$uploaded_photos['name'][$i]} dépasse la limite autorisée."]);
                            exit;
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => "Erreur de téléchargement pour le fichier {$uploaded_photos['name'][$i]} : Code $file_error"]);
                        exit;
                    }
                }

                $_SESSION['temp_photos'] = $temp_photos;

                http_response_code(200);
                echo json_encode(['success' => 'Images uploadées avec succès.', 'links' => $links]);
            }

            if (isset($_POST['message']) && !empty($_POST['message'])) {
                $message = htmlspecialchars(strip_tags($_POST['message']));
                $hashtags = isset($_POST['hashtags']) ? $_POST['hashtags'] : null;
                $arobases = isset($_POST['arobases']) ? $_POST['arobases'] : null;
                $images = !empty($_SESSION['temp_photos']) ? $_SESSION['temp_photos'] : null;

                if (strlen($message) > 140) {
                    $errors[] = 'Le message ne peut être supérieur à 140 caractères.';
                }

                $message = $this->replaceAllHashtags($message);
                $message = $this->replaceAllArobases($message);
                $message = $this->replaceAllLinks($message);

                if (empty($errors)) {
                    $postRep = new PostRepository();

                    $setPost = $postRep->setPostRegistration($userRep->isUserLogged()->id, $message, $hashtags, $images);

                    if ($setPost) {
                        http_response_code(200);
                        echo json_encode(['success' => 'Message envoyé avec succès.', 'redirect' => '/']);
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Echec lors de l\'envoi du message.']);
                    }
                }
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
        }
    }

    public function getPostPhoto($params)
    {
        $image_hash = $params['img_hash'];

        $app = new ConfigApp();
        $postRep = new PostRepository();
        $image_path = $postRep->getImageFromHash($image_hash);

        header("location: {$app->app_url}{$image_path}");
    }

    public function getPostView($params)
    {
        $id_post = $params['post_id'];

        $postRep = new PostRepository();
        $post = $postRep->getPost($id_post);

        if ($post) {
            $commentsRep = new CommentRepository();
            $comments = $commentsRep->getComments($id_post);

            include 'templates/post.php';
        } else {
            throw new Exception('Post non trouvé.');
        }
    }

    public function refreshTimeline()
    {
        $postRep = new PostRepository();
        $posts = $postRep->getPosts();

        $html = '';

        foreach ($posts as $post) {
            $html .= '<div class="card mt-3">
                <div class="card-header">
                    <div class="row d-flex align-items-center">
                        <div class="col-1">
                            <img src="' . $post->author_photo . '" alt="" width="32" height="32" class="rounded-circle me-2">
                        </div>
                        <div class="col-7">
                            ' . $post->author . '
                        </div>
                        <div class="col d-flex justify-content-end">
                            il y a ' . $post->date_in_minutes . '
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">' . $post->message . '</p>
                </div>
                <div class="card-footer text-muted">
                    <div class="row text-center">
                        <div class="col">
                            {number} <i class="fa-regular fa-comment"></i>
                        </div>
                        <div class="col">
                            ' . $post->count_likes . ' <i class="fa-regular fa-heart"></i>
                        </div>
                        <div class="col">
                            ' . $post->count_retweets . ' <i class="fa-solid fa-retweet"></i>
                        </div>
                        <div class="col">
                            {number} <i class="fa-solid fa-chart-simple"></i>
                        </div>
                    </div>
                </div>
            </div>';
        }

        echo $html;
    }

    public function setCommentRegistration()
    {
        $userRep = new UserRepository();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['message']) && !empty($_POST['message'])) {
                $id_post = $_POST['id_post'];
                $message = htmlspecialchars(strip_tags($_POST['message']));
                $hashtags = isset($_POST['hashtags']) ? $_POST['hashtags'] : null;
                $arobases = isset($_POST['arobases']) ? $_POST['arobases'] : null;

                if (strlen($message) > 140) {
                    $errors[] = 'Le message ne peut être supérieur à 140 caractères.';
                }

                $message = $this->replaceAllHashtags($message);
                $message = $this->replaceAllArobases($message);
                $message = $this->replaceAllLinks($message);

                if (empty($errors)) {
                    $commentsRep = new CommentRepository();

                    $setComment = $commentsRep->setCommentRegistration($userRep->isUserLogged()->id, $id_post, $message, $hashtags);

                    if ($setComment) {
                        http_response_code(200);
                        echo json_encode(['success' => 'Commentaire envoyé avec succès.', 'redirect' => '/post/' . $id_post]);
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'Echec lors de l\'envoi du commentaire.']);
                    }
                }
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            header('location: /');
        }
    }
}
