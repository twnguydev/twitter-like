<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6">
    <div class="card mt-3">
        <div class="card-header text-muted">
            <h3 class="text-primary mb-0">Messagerie</h3>
        </div>
        <div class="card-header text-muted">
            <p class="card-text">Veillez à rester bienveillant dans lors des discussions avec les autres utilisateurs.</p>
        </div>
        <?php foreach ($persons as $person) : ?>
            <div class="card-body d-flex justify-content-center align-items-center border-bottom">
                <div class="row d-flex align-items-center justify-content-between w-100">
                    <div class="col-1">
                        <img src="<?= $person->profile_path ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    </div>
                    <div class="col-6 ms-3">
                        <a class="link-underline link-underline-opacity-0" href="/profile/<?= $person->pseudo ?>">@<?= $person->pseudo ?></a>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <a href="/chat/<?= $person->pseudo ?>" class="link-underline link-underline-opacity-0">
                            Contacter&emsp;<i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($persons)) : ?>
            <div class="card-body d-flex justify-content-between align-items-center">
                <p class="card-text">Consultez le profil d'un utilisateur pour lui envoyer un message.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'templates/includes/side_rightbar.php'; ?>
<?php include 'templates/includes/footer.php'; ?>