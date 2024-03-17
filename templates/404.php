<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<?php

use App\Config\ConfigApp;

$app = new ConfigApp();
?>

<div class="col-md-6">
    <div class="card mt-3">
        <div class="card-header">
            <div class="row d-flex align-items-start">
                <h3 class="text-primary mb-0">Oh oh...</h3>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text h5">
                Il semblerait que vous ayiez trouvé un glitch dans la matrix.<br><br>
                Error : <?= $message ?>
            </p>
        </div>
        <div class="card-footer text-muted">
            <p class="card-text">Si vous pensez qu'il s'agit d'une erreur, contactez-nous à l'adresse <a href="mailto:<?= $app->store_email ?>"><?= $app->store_email ?></a>.</p>
        </div>
    </div>
</div>

<?php include 'templates/includes/side_rightbar.php'; ?>
<?php include 'templates/includes/footer.php'; ?>