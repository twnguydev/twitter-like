# Projet Tweet Academy

Ce projet vise à créer une plateforme de microblogage similaire à Twitter, avec un ensemble de fonctionnalités clés et des mesures de sécurité appropriées.

## 1. Introduction

Tweet Academy est une plateforme de microblogage qui permet aux utilisateurs de partager des messages courts, appelés "tweets", avec d'autres utilisateurs. Les fonctionnalités principales incluent la création de comptes membres, l'envoi de tweets, la gestion des abonnements, la messagerie privée, etc.

## 2. Fonctionnalités

Le projet Tweet Academy intégrera les fonctionnalités suivantes, similaires à celles de Twitter :

- **Compte membre**
- **Tweets** :
  - Ajout de hashtags (#)
  - Mention d'autres utilisateurs dans un tweet
  - Réponse à un tweet
  - Retweet
- **Recherche de tags**
- **Abonnements** :
  - Suivre un membre
- **Personnalisation** :
  - Choix de thème
- **Listes** :
  - Affichage des followers
  - Affichage des followings
- **Rafraîchissement automatique de la timeline**
- **Édition de profil**
- **Messagerie privée**
- **Limite de 140 caractères pour les tweets**

## 3. Authentification

Les mots de passe des utilisateurs seront stockés de manière sécurisée en utilisant le hachage "ripemd160". Un sel commun sera utilisé pour tous les utilisateurs.

## 4. Followers - Following

Les utilisateurs pourront consulter et rechercher les profils des autres utilisateurs. Sur chaque profil utilisateur, un lien permettant de suivre cet utilisateur sera présent.
De plus, sur votre propre profil, il sera possible de lister les personnes que vous suivez (following) et les personnes qui vous suivent (followers).

## 5. Hashtags et Mentions

Si un tweet contient un hashtag ("#tag"), il sera représenté sous forme de lien vers la page de recherche du tag correspondant. De même, si un tweet mentionne une personne ("@pseudo"), la chaîne contenant le "@" sera représentée sous forme de lien pointant vers le profil de la personne désignée. De plus, une liste de suggestions de profils s'affichera à chaque caractère entré après le "@".

## 6. Ajout de Photos

Les utilisateurs pourront ajouter des photos à leurs tweets en les téléchargeant via un formulaire. L'URL de la photo sera incluse dans le tweet avec un lien correspondant. Pour limiter la taille de l'URL de la photo, un système similaire à celui utilisé par http://goo.gl/ sera mis en place.

## 7. Messagerie Privée

Le système de messagerie privée permettra aux utilisateurs d'échanger des messages en privé avec d'autres utilisateurs. Cette fonctionnalité offrira une communication directe et confidentielle entre les membres de la plateforme.

---

Ce fichier README.md fournit un aperçu des fonctionnalités et des caractéristiques clés du projet Tweet Academy. Pour des instructions détaillées sur l'installation, la configuration et l'utilisation, veuillez vous référer à la documentation appropriée.

Pour toute question ou commentaire, n'hésitez pas à nous contacter à l'adresse suivante : [hello@tanguygibrat.fr](mailto:hello@tanguygibrat.fr) ou [tanguy.gibrat@epitech.eu](mailto:tanguy.gibrat@epitech.eu).