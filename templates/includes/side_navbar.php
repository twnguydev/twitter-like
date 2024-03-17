<?php

use App\User\UserRepository;

$userRep = new UserRepository();
$userLogged = $userRep->isUserLogged();
$userInfos = $userRep->getUser($userLogged->id);

function isActivePage($urlPattern)
{
    return ($_SERVER['REQUEST_URI'] === $urlPattern) ? 'active text-white' : '';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top" style="top:56px">
                <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary sidebar-height">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="/" class="nav-link link-body-emphasis <?= isActivePage('/') ?>">
                                <i class="fa-solid fa-house mx-3"></i>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <a href="/search" class="nav-link link-body-emphasis <?= isActivePage('/search') ?>">
                                <i class="fa-solid fa-magnifying-glass mx-3"></i>
                                Explorer
                            </a>
                        </li>
                        <li>
                            <a href="/chat" class="nav-link link-body-emphasis <?= isActivePage('/chat') ?>">
                                <i class="fa-solid fa-comment mx-3"></i>
                                Messages
                            </a>
                        </li>
                        <li>
                            <a href="/profile/<?= $userInfos->pseudo ?>" class="nav-link <?= isActivePage('/profile/' . $userInfos->pseudo) ?> link-body-emphasis">
                                <i class="fa-solid fa-user mx-3"></i>
                                Profil
                            </a>
                        </li>
                        <li>
                            <a href="/settings" class="nav-link link-body-emphasis <?= isActivePage('/settings') ?>">
                                <i class="fa-solid fa-gear mx-3"></i>
                                Paramètres
                            </a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-primary w-100">Poster</a>
                    <hr>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $userInfos->profile_path ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>@<?= $userInfos->pseudo ?></strong>
                        </a>
                        <ul class="dropdown-menu text-small shadow">
                            <li><a class="dropdown-item" href="/logout">Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>