<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<div class="container-fluid">
    <div class="row mt-5 mb-5">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h1 class="text-primary text-center">Connectez-vous à votre<br>compte <?= $app->app_name ?>.</h1>
            <form id="login-form" action="javascript:void(0);" method="post" class="mt-5">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" name="email" id="login-email" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">Promis, on ne la partagera avec personne !</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" id="login-password" aria-describedby="emailHelp">
                </div>
                <button type="submit" id="login-btn" class="btn btn-primary mt-5">Me connecter</button>
            </form>
            <div class="row d-flex justify-content-between align-items-center" id="alert-row">
                <div class="alert alert-danger mt-5" id="error-message" role="alert">
                </div>
            </div>
            <h6 class="mt-5 text-center">Pas encore inscrit ? <a href="/signup">Inscrivez-vous.</a></h6>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<script src="./templates/script/login.js"></script>
<?php include 'templates/includes/footer.php'; ?>