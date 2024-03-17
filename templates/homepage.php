<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6 mb-5">
    <div class="card mt-3">
        <div class="card-body" style="padding:0">
            <textarea class="form-control" id="post-textarea" rows="3" placeholder="Quoi de neuf ?!" style="resize:none;border:none"></textarea>
        </div>
        <div class="card-footer text-muted">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-3">
                    <i class="fa-solid fa-image" id="post-add-photos" style="font-size: 25px;cursor:pointer"></i>
                </div>
                <div class="col-3">
                    <div id="char-count">{counter}</div>
                </div>
                <div class="col-3 d-flex justify-content-end">
                    <button class="btn btn-primary" id="post-btn">Envoyer</button>
                </div>
            </div>
            <div class="row d-flex justify-content-between align-items-center h-auto" id="alert-row">
                <div class="alert alert-danger mt-3 mb-0" id="error-message" role="alert">
                </div>
            </div>
        </div>
    </div>

    <div class="ajax-refresh">
        <?php foreach ($posts as $post) : ?>
            <div class="card mt-3">
                <div class="card-header">
                    <div class="row d-flex align-items-center">
                        <div class="col-1">
                            <img src="<?= $post->author_photo ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                        </div>
                        <div class="col-6">
                            <a class="link-underline link-underline-opacity-0" href="/profile/<?= $post->author ?>">@<?= $post->author ?></a>
                        </div>
                        <div class="col d-flex justify-content-end">
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
</div>
<?php include 'templates/includes/side_rightbar.php'; ?>
<?php include 'templates/includes/footer.php'; ?>
<script src="./templates/script/post.js"></script>
<script src="./templates/script/timeline.js"></script>
<script src="./templates/script/post_action.js"></script>