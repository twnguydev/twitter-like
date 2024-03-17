<?php

use App\Feature\Follow\FollowRepository;
use App\User\UserRepository;

class FollowListController
{
    public function getFollowersView($params)
    {
        $pseudo = $params['pseudo'];

        $userRepository = new UserRepository();
        $user = $userRepository->getUser(null, $pseudo, null);

        $followRep = new FollowRepository();
        $followers = $followRep->getFollowers($user->id);

        $follower_user_data = [];
        foreach ($followers as $follower) {
            $follower_user_data[] = $userRepository->getUser($follower->id_user);
        }

        include 'templates/followers.php';
    }

    public function getFollowingView($params)
    {
        $pseudo = $params['pseudo'];

        $userRepository = new UserRepository();
        $user = $userRepository->getUser(null, $pseudo, null);

        $followRep = new FollowRepository();
        $followings = $followRep->getFollowings($user->id);

        $following_user_data = [];
        foreach ($followings as $following) {
            $following_user_data[] = $userRepository->getUser($following->id_followed);
        }

        include 'templates/following.php';
    }

    public function setFollowUser($params)
    {
        $pseudo = $params['pseudo'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['data'] === 'true') {
                $userRep = new UserRepository();
                $userLogged = $userRep->isUserLogged();

                $user = $userRep->getUser(null, $pseudo, null);

                $followRep = new FollowRepository();
                if ($followRep->isFollowing($userLogged->id, $user->id)) {
                    $followRep->setUnfollow($userLogged->id, $user->id);
                    echo json_encode(['success' => 'unfollow', 'redirect' => '/profile/' . $pseudo, 'followers' => $followRep->countAllFollowers($user->id)]);
                } else {
                    $followRep->setFollow($userLogged->id, $user->id);
                    echo json_encode(['success' => 'follow', 'redirect' => '/profile/' . $pseudo, 'followers' => $followRep->countAllFollowers($user->id)]);
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
