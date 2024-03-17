<?php

use App\Feature\Post\PostRepository;
use App\User\UserRepository;

$postRep = new PostRepository();
$hashtags = $postRep->getHashtags(3);

$userRep = new UserRepository();
$users = $userRep->getUsers(3);

?>

<div class="col-md-3">
    <div class="sticky-top" style="top:56px">
        <div class="mb-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0"><i class="fa-solid fa-hashtag"></i>Tendances</h6>
            <?php foreach ($hashtags as $hashtag) : ?>
                <div class="d-flex text-muted pt-3">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <strong class="text-primary"><i class="fa-solid fa-hashtag"></i><?= $hashtag ?></strong>
                            <a href="/tendances/<?= $hashtag ?>">Voir</a>
                        </div>
                        <span class="d-block"><?= $postRep->countPosts($hashtag)['nb_posts'] ?> post<?= $postRep->countPosts($hashtag)['nb_posts'] > 1 ? 's' : '' ?> actif<?= $postRep->countPosts($hashtag)['nb_posts'] > 1 ? 's' : '' ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <small class="d-block text-end mt-3">
                <a href="/tendances">Toutes les tendances</a>
            </small>
        </div>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Suggestions</h6>
            <?php foreach ($users as $user) : ?>
                <div class="d-flex text-muted pt-3">
                    <img src="<?= $user->profile_path ?>" alt="profile" class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32">

                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <strong class="text-gray-dark"><?= $user->username ?></strong>
                            <a href="/profile/<?= $user->pseudo ?>">Voir</a>
                        </div>
                        <span class="d-block text-primary">@<?= $user->pseudo ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <small class="d-block text-end mt-3">
                <a href="#">Toutes les suggestions</a>
            </small>
        </div>
    </div>
</div>
</div>
</div>