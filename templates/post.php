<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6">
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
                    <?= $post->count_comments ?>&emsp;<i class="fa-regular fa-comment"></i>
                </div>
                <div class="col">
                    <?= $post->count_likes ?>&emsp;<i class="fa-regular fa-heart"></i>
                </div>
                <div class="col">
                    <?= $post->count_retweets ?>&emsp;<i class="fa-solid fa-retweet"></i>
                </div>
                <div class="col">
                    {number}&emsp;<i class="fa-solid fa-chart-simple"></i>
                </div>
            </div>
        </div>
        <?php foreach ($comments as $comment) : ?>
            <div class="card-body border-top">
                <div class="row d-flex align-items-center">
                    <div class="col-1">
                        <img src="<?= $comment->author_photo ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    </div>
                    <div class="col-7">
                        <a class="link-underline link-underline-opacity-0" href="/profile/<?= $comment->author ?>">@<?= $comment->author ?></a>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <?= $comment->date ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <p class="card-text"><?= $comment->comment ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card mt-3">
        <div class="card-body" style="padding:0">
            <textarea class="form-control" id="comment-textarea" rows="3" placeholder="Répondre à ce tweet" style="resize:none;border:none"></textarea>
        </div>
        <div class="card-footer text-muted">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-6">
                    <div id="char-count-comment">{counter}</div>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <button class="btn btn-primary" id="comment-btn">Envoyer</button>
                </div>
            </div>
            <div class="row d-flex justify-content-between align-items-center h-auto" id="alert-row">
                <div class="alert alert-danger mt-3 mb-0" id="error-message" role="alert">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'templates/includes/side_rightbar.php'; ?>
<script src="/templates/script/comment.js"></script>
<?php include 'templates/includes/footer.php'; ?>