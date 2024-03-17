<?php

use App\Feature\Chat\ChatRepository;
use App\User\UserRepository;

class ChatController
{
    public function getChatView($params)
    {
        $pseudo = $params['pseudo'];

        $userRep = new UserRepository();
        $chatRep = new ChatRepository();

        $persons = $chatRep->getPersonsWhoChattedWith($userRep->isUserLogged()->id);

        include 'templates/chat.php';
    }

    public function getChats($params)
    {
        $pseudo = $params['pseudo'];

        $userRep = new UserRepository();
        $chatRep = new ChatRepository();

        $user = $userRep->getUser(null, $pseudo);
        $chats = $chatRep->getChats();

        foreach ($chats as $chat) {
            if ($chat->id_user === $userRep->isUserLogged()->id) {
                $chat->isSender = true;
                $chat->isReceiver = false;
            } else {
                $chat->isReceiver = true;
                $chat->isSender = false;
            }
        }

        include 'templates/chat_messages.php';
    }

    public function setChatRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $receiver = $_POST['receiver'];
            $message = $_POST['message'];

            $errors = [];

            $userRep = new UserRepository();
            $userObj = $userRep->getUser(null, $receiver);

            if (!$userObj) {
                $errors[] = 'Utilisateur non trouvé.';
            }

            if (strlen($message) <= 1 || strlen($message) > 140) {
                $errors[] = 'Le message doit contenir entre 2 et 140 caractères.';
            }

            if (empty($errors)) {
                // echo json_encode($receiver . ' ' . $message . ' ' . $userObj->id . ' ' . $userRep->isUserLogged()->id);
                $chatRep = new ChatRepository();
                $chatRep->setChatRegistration($userRep->isUserLogged()->id, $userObj->id, $message);
                echo json_encode(['success' => 'Message envoyé avec succès.', 'redirect' => '/chat/' . $receiver]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => $errors]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
        }
    }
}
