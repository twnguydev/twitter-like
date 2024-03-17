CREATE TABLE `follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_subscriber` int(11) DEFAULT NULL,
  `id_target` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_subscriber`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_target`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'Homme'),
(2, 'Femme'),
(3, 'Autre');

CREATE TABLE `hashtag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(139) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_comment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_comment`) REFERENCES `comments` (`id`) ON DELETE CASCADE
);

INSERT INTO `hashtag` (`id`, `name`, `id_post`, `id_comment`) VALUES
(9, 'hashtags', 13, NULL),
(10, 'hashtag', 13, NULL),
(11, 'hashtags', 14, NULL),
(12, 'devpassion', 14, NULL),
(13, 'pelican', 14, NULL),
(14, 'avec', 15, NULL),
(15, 'plusieurs', 15, NULL),
(17, 'plusieurs', 16, NULL),
(18, 'hashtags', 16, NULL),
(19, 'hashtags', 21, NULL),
(20, 'pelican', 21, NULL),
(21, 'epitech', 21, NULL),
(22, 'webac', 21, NULL),
(28, 'ouf', 25, NULL),
(29, 'ouf', 26, NULL),
(33, 'happy', 27, NULL),
(34, 'welcome', 27, NULL),
(35, 'hashtags', 28, NULL);

CREATE TABLE `image_path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE
);

INSERT INTO `image_path` (`id`, `id_post`, `image_path`, `image_hash`) VALUES
(9, 13, 'assets/uploads/img_65d8b9e146fa1_1708702177.jpg', '936b27'),
(10, 13, 'assets/uploads/img_65d8b9e14721b_1708702177.webp', 'e15afe'),
(13, 15, 'assets/uploads/img_65d8b9e146fa1_1708702177.jpg', '936b27'),
(14, 15, 'assets/uploads/img_65d8b9e14721b_1708702177.webp', 'e15afe'),
(15, 16, 'assets/uploads/img_65d8b9e146fa1_1708702177.jpg', '936b27'),
(16, 16, 'assets/uploads/img_65d8b9e14721b_1708702177.webp', 'e15afe'),
(17, 17, 'assets/uploads/img_65d8bd2e429f0_1708703022.png', '5cfad0'),
(18, 17, 'assets/uploads/img_65d8bd2e42b5f_1708703022.png', '8a11d7'),
(19, 18, 'assets/uploads/img_65d8bd7d09f6d_1708703101.png', '9293dd'),
(20, 18, 'assets/uploads/img_65d8bd7d0a0c0_1708703101.png', '8e02cd'),
(21, 19, 'assets/uploads/img_65d8bdbf5a1ea_1708703167.jpg', '3a4f86'),
(22, 19, 'assets/uploads/img_65d8bdbf5a2eb_1708703167.jpg', '24c2d2'),
(23, 20, 'assets/uploads/img_65d8bdbf5a1ea_1708703167.jpg', '3a4f86'),
(24, 20, 'assets/uploads/img_65d8bdbf5a2eb_1708703167.jpg', '24c2d2'),
(25, 28, 'assets/uploads/img_65f583a6df982_1710588838.png', 'dbad62'),
(26, 28, 'assets/uploads/img_65f583a6dfb15_1710588838.jpeg', '9e82dd');

CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `post` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

INSERT INTO `post` (`id`, `id_user`, `post`, `date`) VALUES
(13, 5, 'test avec <a href=\"/tendances/hashtags\" class=\"text-primary\">#hashtags</a> <a href=\"/tendances/hashtag\" class=\"text-primary\">#hashtag</a> et arobases <a href=\"/profile/twnguydev\" class=\"text-primary\">@twnguydev</a>\r\n<a href=\"\r\nhttp://localhost:8888/img/936b27\" class=\"text-primary\" target=\"_blank\">\r\nhttp://localhost:8888/img/936b27</a>\r\n<a href=\"\r\nhttp://localhost:8888/img/e15afe\" class=\"text-primary\" target=\"_blank\">\r\nhttp://localhost:8888/img/e15afe</a>\r\n', '2024-02-23 16:30:00'),
(28, 5, 'New test for images &amp; <a href=\"/tendances/hashtags\" class=\"text-primary\">#hashtags</a> :)\n<br><a href=\"\nhttp://localhost:8888/img/dbad62\" class=\"text-primary\" target=\"_blank\">\nhttp://localhost:8888/img/dbad62</a><br><a href=\"\nhttp://localhost:8888/img/9e82dd\" class=\"text-primary\" target=\"_blank\">\nhttp://localhost:8888/img/9e82dd</a>', '2024-03-16 12:34:05');

CREATE TABLE `post_comment` (
  `id` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `content` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

INSERT INTO `post_comment` (`id`, `id_post`, `id_user`, `content`, `date`) VALUES
(1, 13, 5, 'test commentaire', '2024-02-23 16:30:00'),
(2, 13, 5, 'test commentaire 2', '2024-02-23 16:30:00'),
(3, 13, 5, 'test commentaire 3', '2024-02-23 16:30:00');

CREATE TABLE `post_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

INSERT INTO `post_like` (`id`, `id_post`, `id_user`, `date`) VALUES
(1, 13, 5, '2024-02-23 16:30:00'),
(2, 13, 5, '2024-02-23 16:30:00'),
(3, 13, 5, '2024-02-23 16:30:00');

CREATE TABLE `post_retweet` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE
);

INSERT INTO `post_retweet` (`id`, `id_user`, `id_post`, `date`) VALUES
(1, 5, 13, '2024-02-23 16:30:00'),
(2, 5, 13, '2024-02-23 16:30:00'),
(3, 5, 13, '2024-02-23 16:30:00');

CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `profile_path` varchar(255) DEFAULT '/assets/profile-img.webp',
  `banner_path` varchar(255) DEFAULT '/assets/profile-banner.jpg',
  `biography` varchar(140) DEFAULT 'Je suis nouveau sur MyTwitter !',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

INSERT INTO `profile` (`id`, `id_user`, `username`, `profile_path`, `banner_path`, `biography`) VALUES
(4, 5, 'Tanguy Dev', '/assets/profiles/twnguydev_50101609f93ec4939f89b1f9a6b0384bf317d9a7e6e4b95cd9eb6241f42b999f.jpg', '/assets/profile-banner.jpg', 'Je suis nouveau sur MyTwitter ! :)'),
(7, 8, 'Miss Dev', '/assets/profiles/missdev__5834033c63d990b7fa9c502c4fec7c87c59c5c5c44500bfea71bd1b10ec3b4a3.jpg', '/assets/banners/missdev__6735fed488ef59fb0fc744fb0539e219d01ab10c819c44761b3d42bb00302ae4.png', 'Je suis nouveau sur MyTwitter !');

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_genre` int(11) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `last_co` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id`) ON DELETE CASCADE
);

INSERT INTO `user` (`id`, `id_genre`, `pseudo`, `fullname`, `email`, `password`, `token`, `birthdate`, `city`, `country`, `is_active`, `created_at`, `last_co`) VALUES
(5, 1, 'twnguydev', 'Tanguy Dev', 'dev.tanguy@gmail.com', '$2y$10$2O5HsD/SmvCQrmHsERyC..ug4NnJuuHlG/p7OSHPNXl1UdVMR4a12', NULL, '1999-03-14', 'Paris', 'France', 1, '2022-12-13 11:30:00', '2023-11-02 09:45:00'),
(8, 2, 'missdev', 'Miss Dev', 'miss.dev@gmail.com', '$2y$10$Qd.AkE5pN9vuf5ZANxZ//O7fA8NpeTtPFphmUm0QZtjtizVEZ3hxG', NULL, '1995-05-22', 'Marseille', 'France', 1, '2023-11-02 09:45:00', '2023-11-02 09:45:00');

CREATE TABLE `user_follow` (
  `id` int(11) NOT NULL,
  `id_follower` int(11) NOT NULL,
  `id_target` int(11) NOT NULL,
  `date` date NOT NULL
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_follower`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_target`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

INSERT INTO `user_follow` (`id`, `id_follower`, `id_target`, `date`) VALUES
(1, 5, 8, '2023-11-02'),
(2, 8, 5, '2023-11-02');

CREATE TABLE `user_message` (
  `id` int(11) NOT NULL,
  `id_sender` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_sender`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_receiver`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

INSERT INTO `user_message` (`id`, `id_sender`, `id_receiver`, `message`, `date`) VALUES
(1, 5, 8, 'Salut Miss Dev !', '2023-11-02 09:45:00'),
(2, 8, 5, 'Salut Tanguy !', '2023-11-02 09:45:00');