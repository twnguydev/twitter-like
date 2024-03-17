<?php

use App\Feature\Post\PostRepository;

class TrendController
{
    public function getPostsFromHashtag($params)
    {
        $hashtag = $params['hashtag'];

        $postRep = new PostRepository();

        if ($postRep->getHashtag($hashtag)) {
            $posts = $postRep->getPosts($hashtag);

            include 'templates/tendance.php';
        } else {
            header('location: /');
        }
    }

    public function getHashtags()
    {
        $postRep = new PostRepository();
        $hashtags = $postRep->getHashtags();

        include 'templates/tendance_all.php';
    }
}