<?php include 'templates/includes/header.php'; ?>
<?php include 'templates/includes/navbar.php'; ?>
<?php include 'templates/includes/side_navbar.php'; ?>

<div class="col-md-6 mt-3">
    <div class="card" style="max-height: 88%">
        <div class="card-header d-flex align-items-center justify-content-start">
            <img src="<?= $user->profile_path ?>" alt="" width="32" height="32" class="rounded-circle me-2">
            <div class="d-flex flex-column ms-2">
                <p class="text-muted mb-0"><?= $user->fullname ?></p>
                <a class="link-underline link-underline-opacity-0" href="/profile/<?= $user->pseudo ?>">@<?= $user->pseudo ?></a>
            </div>
        </div>
        <div class="card-body p-2 overflow-auto" id="message-container">
            <?php foreach ($chats as $chat) : ?>
                <?php if ($chat->isSender) : ?>
                    <div class="card-text d-flex justify-content-end text-white mt-2">
                        <p class="card-text bg-primary rounded p-2 w-auto mb-0" style="max-width: 75%"><?= $chat->message ?></p>
                        <img src="<?= $userRep->getUser($chat->id_user)->profile_path ?>" alt="" width="32" height="32" class="rounded-circle ms-2">
                    </div>
                <?php elseif ($chat->isReceiver) : ?>
                    <div class="card-text d-flex justify-content-start mt-2">
                        <img src="<?= $userRep->getUser($chat->id_user)->profile_path ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                        <p class="card-text bg-secondary-subtle rounded p-2 w-75"><?= $chat->message ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="card-header text-muted border-top" style="padding:0;border-bottom:none">
            <textarea class="form-control" id="chat-textarea" rows="1" placeholder="Écrire..." style="resize:none;border:none"></textarea>
        </div>
        <div class="card-footer text-muted">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-6">
                    <div id="char-count">{counter}</div>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <button class="btn btn-primary" id="chat-btn">Envoyer</button>
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
<script src="/templates/script/chat.js"></script>
<?php include 'templates/includes/footer.php'; ?>