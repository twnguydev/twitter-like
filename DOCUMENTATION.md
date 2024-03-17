# Documentation - Configuration du Projet Tweet Academy

Ce document explique les étapes nécessaires pour configurer correctement le projet Tweet Academy.

## 1. Importer la base de données

- Assurez-vous d'avoir accès à PHPMyAdmin ou à un outil similaire pour gérer les bases de données MySQL.
- Dans PHPMyAdmin, créez une nouvelle base de données nommée "tweet_academy".
- Importez le fichier database.sql fourni avec le projet dans la base de données que vous venez de créer. Cela va créer toutes les tables nécessaires pour le projet.

## 2. Configuration de `app_config.php`

- Accédez au fichier `src/lib/app_config.php` dans votre éditeur de texte préféré.
- Vous y trouverez différentes constantes à configurer. Assurez-vous de les ajuster selon votre environnement de développement. Voici les constantes et leurs significations :
  - `app_name` : Le nom de votre application.
  - `app_description` : La description de votre application.
  - `app_keywords` : Les mots-clés associés à votre application.
  - `app_author` : L'auteur ou l'équipe de développement de l'application.
  - `app_url` : L'URL de base de votre application.
  - `store_address` : L'adresse de votre magasin ou de votre entreprise.
  - `store_phone` : Le numéro de téléphone de votre magasin ou de votre entreprise.
  - `store_email` : L'adresse e-mail de contact de votre magasin ou de votre entreprise.

## 3. Configuration de `db_config.php`

- Accédez au fichier `src/lib/db_config.php` dans votre éditeur de texte préféré.
- Vous y trouverez les paramètres de configuration de la base de données. Assurez-vous de les ajuster en fonction de votre environnement de développement. Voici les paramètres à configurer :
  - `host` : L'hôte de la base de données MySQL.
  - `db_name` : Le nom de la base de données MySQL que vous avez créée pour le projet.
  - `user` : Le nom d'utilisateur de la base de données MySQL.
  - `password` : Le mot de passe de la base de données MySQL.
  - `charset` : Le charset utilisé pour la base de données MySQL. (recommandé : `utf8mb4`)

Une fois que vous avez configuré correctement ces fichiers, votre projet Tweet Academy devrait être prêt à être utilisé.

Pour toute question ou assistance supplémentaire, n'hésitez pas à me contacter à l'adresse suivante : [hello@tanguygibrat.fr](mailto:hello@tanguygibrat.fr) ou [tanguy.gibrat@epitech.eu](mailto:tanguy.gibrat@epitech.eu).
