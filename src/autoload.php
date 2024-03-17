<?php

spl_autoload_register(function ($class) {
    $directories = ['src/lib', 'src/models', 'src/controllers'];

    $classMap = [
        'ConfigApp' => 'app_config.php',
        'ConfigDatabase' => 'db_config.php',
        'DatabaseConnection' => 'db_config.php',
        'Auth' => 'auth.php',
        'UserRepository' => 'user_class.php',
        'GenreRepository' => 'genre_class.php',
        'CheckLogin' => 'login_class.php',
        'CheckSignup' => 'signup_class.php',
        'PostRepository' => 'post_class.php',
        'PostManager' => 'post_manager.php',
        'CommentRepository' => 'comment_class.php',
        'FollowRepository' => 'follow_class.php',
        'RetweetRepository' => 'post_retweet_class.php',
        'LikeRepository' => 'post_like_class.php',
        'ChatRepository' => 'chat_class.php',
    ];

    $classParts = explode('\\', $class);
    $className = end($classParts);

    if (isset($classMap[$className])) {
        foreach ($directories as $directory) {
            $file = $directory . DIRECTORY_SEPARATOR . $classMap[$className];
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
