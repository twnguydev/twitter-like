<?php

use App\Config\ConfigApp;
use App\User\UserRepository;

$header = new ConfigApp();
$userLogged = new UserRepository();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-body sticky-top" style="z-index:9999">
    <div class="container-fluid mx-5">
        <a class="navbar-brand" href="/"><?= $header->app_name ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($userLogged->isUserLogged()) : ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Pour vous</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Abonnements</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <button class="nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (auto)">
                        <span id="bd-theme-text">Thème</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                Light mode
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                Dark mode
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php if ($userLogged->isUserLogged()) : ?>
                <form class="col-lg-6">
                    <div class="dropdown">
                        <input class="form-control" type="text" placeholder="Recherche" aria-label="Search" id="searchInput" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" autocomplete="off">
                        <div class="dropdown-menu w-100" aria-labelledby="searchInput" style="z-index: 9999" id="search-content">
                            <?php foreach ($searchInfos as $searchInfo) : ?>
                                <a class="dropdown-item" href="/profile/<?= $searchInfo->pseudo ?>">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex flex-row align-items-center">
                                            <img src="<?= $searchInfo->profile_path ?>" alt="profile picture" class="rounded-circle" width="30" height="30">
                                            <p class="ms-2 text-primary mb-0">@<?= $searchInfo->pseudo ?></p>
                                        </div>
                                        <div class="ms-2 justify-self-end">
                                            <p class="m-0"><?= $searchInfo->count_followers ?> suivent</p>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>
<script src="/templates/script/navbar.js"></script>