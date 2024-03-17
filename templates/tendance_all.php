<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6">
    <div class="card mt-3">
        <div class="card-header text-muted" style="border-bottom:none">
            <h3 class="text-primary mb-0">Tendances</h3>
        </div>
    </div>

    <div class="ajax-refresh">
        <div class="card mt-3">
            <?php foreach ($hashtags as $hashtag) : ?>
                <div class="card-body d-flex justify-content-between align-items-center border-bottom">
                    <p class="card-text mb-0">#<?= $hashtag ?></p>
                    <p class="card-text">
                        <a href="/tendances/<?= $hashtag ?>" class="link-underline link-underline-opacity-0">
                            Voir tous les posts
                        </a>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include 'templates/includes/side_rightbar.php'; ?>
<?php include 'templates/includes/footer.php'; ?>