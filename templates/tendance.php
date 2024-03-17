<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6">
    <div class="card mt-3">
        <div class="card-header text-muted" style="border-bottom:none">
            <h3 class="text-primary mb-0">#<?= $hashtag ?></h3>
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
                        <div class="col-7">
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
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include 'templates/includes/side_rightbar.php'; ?>
<?php include 'templates/includes/footer.php'; ?>