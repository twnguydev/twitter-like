<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6">
    <div class="card mt-3">
        <div class="card-header text-muted" style="border-bottom:none">
            <h3 class="text-primary mb-0"><a href="/profile/<?= $user->pseudo ?>" class="link-underline link-underline-opacity-0">@<?= $user->pseudo ?></a> a suivi</h3>
        </div>
    </div>

    <div class="card mt-3">
        <?php foreach ($following_user_data as $i => $follower) : ?>
            <div class="card-<?= $i % 2 === 0 ? 'header' : 'footer' ?>" <?= $i === 0 ? 'style="border-bottom: none"' : '' ?>>
                <div class="row d-flex align-items-center">
                    <div class="col-1">
                        <img src="<?= $follower->profile_path ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    </div>
                    <div class="col-7">
                        <a class="link-underline link-underline-opacity-0" href="/profile/<?= $follower->pseudo ?>">@<?= $follower->pseudo ?></a>
                    </div>
                    <div class="col d-flex justify-content-end">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'templates/includes/side_rightbar.php'; ?>
<?php include 'templates/includes/footer.php'; ?>