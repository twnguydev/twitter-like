<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<div class="container-fluid">
    <div class="row mt-5 mb-5">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h1 class="text-primary text-center">Postez dès maintenant<br>sur <?= $app->app_name ?>.</h1>
            <form id="signup-form" action="javascript:void(0);" method="post" class="mt-5">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Genre</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Sélectionnez</option>
                        <?php foreach ($genres as $genre) : ?>
                            <option value="<?= $genre->id ?>"><?= $genre->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Nom et prénom</label>
                    <input type="text" class="form-control" name="fullname" id="signup-fullname" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" name="email" id="signup-email" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">Promis, on ne la partagera avec personne !</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Pseudonyme</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="text" name="pseudo" id="signup-pseudo" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div id="emailHelp" class="form-text">Doit contenir au moins 5 caractères.</div>
                </div>
                <div class="col-auto">
                    <label for="inputPassword6" name="birthdate" id="signup-email" class="col-form-label">Date de naissance</label>
                </div>
                <div class="row g-3 align-items-center mb-3">
                    <div class="col">
                        <input type="date" max="<?= date('Y-m-d', strtotime('-13 years')) ?>" id="signup-birthdate" class="form-control" aria-describedby="passwordHelpInline">
                    </div>
                    <div class="col">
                        <span id="passwordHelpInline" class="form-text">
                            Doit avoir plus de 13 ans pour accéder à la plateforme.
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Ville</label>
                    <input type="text" class="form-control" name="city" id="signup-city" aria-describedby="emailHelp">
                </div>
                <div class="col-auto">
                    <label for="inputPassword6" class="col-form-label">Mot de passe et confirmation</label>
                </div>
                <div class="row g-3 align-items-center mb-3">
                    <div class="col">
                        <input type="password" id="signup-password" name="password" class="form-control mb-1" aria-describedby="passwordHelpInline">
                        <input type="password" id="signup-confirm-password" name="password" class="form-control" aria-describedby="passwordHelpInline">
                    </div>
                    <div class="col">
                        <span id="passwordHelpInline" class="form-text">
                            Doit contenir au moins 8 caractères.
                        </span>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="terms" id="signup-terms" class="form-check-input">
                    <label class="form-check-label" for="exampleCheck1">J'accepte les termes et conditions d'utilisation.</label>
                </div>
                <button type="submit" id="signup-btn" class="btn btn-primary">M'inscrire</button>
            </form>
            <div class="row d-flex justify-content-between align-items-center" id="alert-row">
                <div class="alert alert-danger mt-3" id="error-message" role="alert">
                </div>
            </div>
            <h6 class="mt-5 text-center">Déjà inscrit ? <a href="/login">Connectez-vous.</a></h6>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<script src="./templates/script/signup.js"></script>
<?php include 'templates/includes/footer.php'; ?>