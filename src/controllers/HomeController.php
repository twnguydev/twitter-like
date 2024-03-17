<?php

use App\Feature\Post\PostRepository;
use App\Feature\Post\Like\LikeRepository;
use App\Feature\Post\Retweet\RetweetRepository;
use App\User\UserRepository;

class HomeController
{
    public function getHomeView()
    {
        $postRep = new PostRepository();
        $posts = $postRep->getPosts();

        $userRep = new UserRepository();

        if ($userRep->isUserLogged()) {
            $likeRep = new LikeRepository();
            $retweetRep = new RetweetRepository();

            foreach ($posts as $post) {
                $post->isLiked = $likeRep->isLiked($userRep->isUserLogged()->id, $post->id);
                $post->isRetweeted = $retweetRep->isRetweeted($userRep->isUserLogged()->id, $post->id);
            }
        }

        include 'templates/homepage.php';
    }

    public function searchUsers()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['query'])) {
                $userRep = new UserRepository();
                $searchInfos = $userRep->searchUsers($_POST['query'], 4);
                echo json_encode($searchInfos);
            }
        }
    }
}