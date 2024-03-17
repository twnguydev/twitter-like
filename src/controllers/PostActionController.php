<?php

use App\Feature\Post\Like\LikeRepository;
use App\Feature\Post\Retweet\RetweetRepository;
use App\User\UserRepository;

class PostActionController
{
    public function setLike()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['data'] === 'true') {
                $id_post = $_POST['id_post'];
                $userRep = new UserRepository();
                $userLogged = $userRep->isUserLogged();

                $likeRep = new LikeRepository();
                if ($likeRep->isLiked($userLogged->id, $id_post)) {
                    $likeRep->unsetLike($userLogged->id, $id_post);
                    echo json_encode(['success' => 'dislike', 'id_post' => $id_post, 'likes' => $likeRep->countLikes($id_post)]);
                } else {
                    $likeRep->setLike($userLogged->id, $id_post);
                    echo json_encode(['success' => 'like', 'id_post' => $id_post, 'likes' => $likeRep->countLikes($id_post)]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la requête.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            header('Location: /');
        }
    }

    public function setRetweet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['data'] === 'true') {
                $id_post = $_POST['id_post'];
                $userRep = new UserRepository();
                $userLogged = $userRep->isUserLogged();

                $retweetRep = new RetweetRepository();
                if ($retweetRep->isRetweeted($userLogged->id, $id_post)) {
                    $retweetRep->unsetRetweet($userLogged->id, $id_post);
                    echo json_encode(['success' => 'unretweet', 'id_post' => $id_post, 'retweets' => $retweetRep->countRetweets($id_post)]);
                } else {
                    $retweetRep->setRetweet($userLogged->id, $id_post);
                    echo json_encode(['success' => 'retweet', 'id_post' => $id_post, 'retweets' => $retweetRep->countRetweets($id_post)]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Erreur lors de la requête.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            header('Location: /');
        }
    }
}