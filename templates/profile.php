<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/side_navbar.php';
?>
<style>
    .cover {
        background-image: url(<?= $user->banner_path ?>);
        background-size: cover;
        background-repeat: no-repeat;
        height: 200px;
    }

    .profile-head {
        transform: translateY(5rem);
    }

    .bioandother {
        color: #818181;
        font-size: 0.9em;
    }
</style>
<div class="col-md-6 mb-5">
    <div class="shadow rounded overflow-hidden">
        <div class="px-4 pt-0 pb-4 cover">
            <div class="media align-items-end profile-head w-80 h-50">
                <div class="profile mr-3 w-50">
                    <img src="<?= $user->profile_path ?>" alt="..." width="130" class="rounded mb-2 img-thumbnail" id="profile-img">
                    <h4 class="mt-0 mb-0"><?= $user->username; ?></h4>
                    <h4 class="mt-0 mb-0 p-1 text-primary" style="font-size: 15px;">@<?= $user->pseudo ?></h4>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end text-center h-200" style="height:80px">
            <div class="media-body mb-5">
                <?php if ($access) : ?>
                    <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#update-profile">
                        Éditer le profil
                    </button>
                <?php endif; ?>

                <div class="modal fade mt-5" id="update-profile" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content text-start">
                            <div class="modal-header">
                                <h5 class="modal-title text-primary">Modifier mon profil</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="login-form" action="javascript:void(0);" method="post">
                                    <div class="mb-3">
                                        <label for="profile-bio" class="form-label">Biographie</label>
                                        <textarea class="form-control" name="biography" id="biography" rows="2" style="resize:none"><?= $user->biography ?></textarea>
                                        <div id="char-count" class="mt-1">{counter}</div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col">
                                            <label for="profile-username" class="form-label">Nom</label>
                                            <input type="email" class="form-control" name="username" id="update-username" value="<?= $user->username ?>" aria-describedby="emailHelp">
                                        </div>
                                        <div class="col">
                                            <label for="profile-pseudo" class="form-label">Pseudo</label>
                                            <input type="email" class="form-control" name="pseudo" id="update-pseudo" value="<?= $user->pseudo ?>" aria-describedby="emailHelp">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="profile-email" class="form-label">Adresse e-mail</label>
                                        <input type="email" class="form-control" name="email" id="update-email" value="<?= $user->email ?>" aria-describedby="emailHelp">
                                        <div id="emailHelp" class="form-text">Promis, on ne la partagera avec personne !</div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col">
                                            <label for="profile-birthdate" class="form-label">Date de naissance</label>
                                            <input type="date" class="form-control" name="birthdate" id="update-birthdate" value="<?= $user->birthdate ?>" aria-describedby="emailHelp" disabled>
                                        </div>
                                        <div class="col">
                                            <label for="profile-city" class="form-label">Localisation</label>
                                            <input type="text" class="form-control" name="city" id="update-city" value="<?= $user->city ?>" aria-describedby="emailHelp">
                                        </div>
                                    </div>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col">
                                            <label for="inputPassword6" name="password" class="col-form-label">Mot de passe actuel</label>
                                            <input type="password" id="update-password" class="form-control" aria-describedby="passwordHelpInline">
                                        </div>
                                        <div class="col">
                                            <label for="inputPassword6" name="password" class="col-form-label">Nouveau mot de passe</label>
                                            <input type="password" id="update-confirm-password" class="form-control" aria-describedby="passwordHelpInline">
                                        </div>
                                    </div>
                                </form>
                                <div class="row mx-auto d-flex justify-content-between align-items-center h-auto" id="alert-row">
                                    <div class="alert alert-danger mt-3 mb-0" id="error-message" role="alert">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="update-banner-btn" class="btn btn-primary">Modifier ma bannière</button>
                                <button type="button" id="update-photo-btn" class="btn btn-primary">Modifier ma photo</button>
                                <button type="button" id="update-btn" class="btn btn-warning">Sauvegarder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2 mb-3 rounded shadow-xl">
            <div class="mx-3">
                <p class="font-italic mb-0 pb-2"><?= $user->biography; ?></p>
                <div class="bioandother">
                    <p class="font-italic mb-0 pb-4"><?= $user->city; ?></p>
                </div>
                <ul class="list-inline mb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <li class="list-inline-item">
                            <h5 class="font-weight-bold mb-0 d-block">
                                <a href="/profile/<?= $user->pseudo ?>/followers" class="link-underline link-underline-opacity-0" style="color: inherit">
                                    <span id="count-followers">
                                        <?= $count_followers ?>
                                    </span>
                                    <small class="text-muted" style="font-size:12px">
                                        abonné<?= $count_followers > 1 ? 's' : '' ?>
                                    </small>
                                </a>
                            </h5>
                        </li>
                        <li class="list-inline-item">
                            <h5 class="font-weight-bold mb-0 d-block">
                                <a href="/profile/<?= $user->pseudo ?>/followings" class="link-underline link-underline-opacity-0" style="color: inherit">
                                    <?= $count_followings ?>
                                    <small class="text-muted" style="font-size:12px">
                                        abonnement<?= $count_followings > 1 ? 's' : '' ?>
                                    </small>
                                </a>
                            </h5>
                        </li>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <?php if (!$access) : ?>
                            <button type="button" class="btn btn-secondary">
                                <a href="/chat/<?= $user->pseudo ?>" class="link-underline link-underline-opacity-0" style="color: inherit">
                                    Contacter
                                </a>
                            </button>
                        <?php endif; ?>
                        <?php if ($is_following !== null) : ?>
                            <button type="button" class="btn ms-1 <?= $is_following ? 'btn-primary' : 'btn-success' ?>" id="profile-follow-btn">
                                <?= $is_following ? 'Abonné&emsp;<i class="fa-solid fa-check"></i>' : 'Suivre' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </ul>
            </div>
        </div>
    </div>

    <?php foreach ($posts as $post) : ?>
        <div class="card mt-3">
            <div class="card-header">
                <?php if ($post->activityType === 'liked' && $user->pseudo !== $post->author) : ?>
                    <div class="row d-flex align-items-center border-bottom pb-2 mb-2">
                        <p class="card-text mb-0">
                            <a class="link-underline link-underline-opacity-0" href="/profile/<?= $user->pseudo ?>">@<?= $user->pseudo ?></a> a aimé
                        </p>
                    </div>
                <?php elseif ($post->activityType === 'retweeted' && $user->pseudo !== $post->author) : ?>
                    <div class="row d-flex align-items-center border-bottom pb-2 mb-2">
                        <p class="card-text mb-0">
                            <a class="link-underline link-underline-opacity-0" href="/profile/<?= $user->pseudo ?>">@<?= $user->pseudo ?></a> a retweeté
                        </p>
                    </div>
                <?php endif; ?>
                <div class="row d-flex align-items-center">
                    <div class="col-1">
                        <img src="<?= $post->author_photo ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    </div>
                    <div class="col-6">
                        <a class="link-underline link-underline-opacity-0" href="/profile/<?= $post->author ?>">@<?= $post->author ?></a>
                    </div>
                    <div class="col d-flex justify-content-end card-text">
                        <?= $post->date_in_minutes ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text"><?= $post->message ?></p>
            </div>
            <div class="card-footer text-muted">
                <div class="row text-center">
                    <div class="col">
                        <a href="/post/<?= $post->id ?>" class="link-underline link-underline-opacity-0" style="color: inherit">
                            <?= $post->count_comments ?>&emsp;<i class="fa-regular fa-comment"></i>
                        </a>
                    </div>
                    <div class="col">
                        <span class="like_element" data-post-id="<?= $post->id ?>" style="cursor: pointer">
                            <?= $post->count_likes ?>&emsp;<i class="fa-<?= $post->isLiked ? 'solid' : 'regular' ?> fa-heart" <?= $post->isLiked ? 'style="color: red"' : '' ?>></i>
                        </span>
                    </div>
                    <div class="col">
                        <span class="retweet_element" data-post-id="<?= $post->id ?>" style="cursor: pointer">
                            <?= $post->count_retweets ?>&emsp;<i class="fa-solid fa-retweet" <?= $post->isRetweeted ? 'style="color: green"' : '' ?>></i>
                        </span>
                    </div>
                    <div class="col">
                        {number}&emsp;<i class="fa-solid fa-chart-simple"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/side_rightbar.php'; ?>
<script src="/templates/script/profile.js"></script>
<script src="/templates/script/post_action.js"></script>
<?php include 'includes/footer.php'; ?>