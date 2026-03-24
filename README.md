# La Taverne de l'ENI/ QUEST.ENI (Projet Sortir.com) - v1

Ce projet est une réinvention créative du projet scolaire "Sortir.com". [cite_start]La société ENI souhaite développer pour ses stagiaires actifs ainsi que ses anciens stagiaires une plateforme web leur permettant d’organiser des sorties[cite: 5]. Pour nous démarquer, cette plateforme adopte une direction artistique inspirée des jeux de rôle (RPG) de l'ère PS2, transformant une simple application utilitaire en une véritable guilde d'aventuriers.

## Le Concept

[cite_start]La plateforme est une plateforme privée dont l’inscription sera gérée par le ou les administrateurs[cite: 6]. Les étudiants n'y organisent plus de simples activités, ils proposent des "Quêtes". [cite_start]Les sorties, ainsi que les participants ont un site de rattachement, pour permettre une organisation géographique des sorties[cite: 7]. [cite_start]L'objectif principal est de permettre de faire du lien extra-formation entre les stagiaires et ainsi de développer de la cohésion sociale[cite: 9].

## Stack Technique

| Technologie | Rôle |
| :--- | :--- |
| **Symfony** | Framework Backend (PHP) |
| **Twig** | Moteur de rendu des templates |
| **WAMP / phpMyAdmin** | Serveur local et gestion de la base de données MySQL |
| **Tailwind CSS** | Framework CSS utilitaire pour le design sur-mesure |
| **DaisyUI** | Composants UI préconstruits branchés sur Tailwind |
| **Webpack Encore** | Compilation et gestion des assets Front-End |

## Fonctionnalités Principales

* Création, modification et annulation de Quêtes (Sorties).
* Inscription et désistement aux différentes activités proposées par les autres membres.
* Gestion des profils utilisateurs (Aventuriers).
* Administration globale pour gérer les utilisateurs, les campus (Factions/Sites) et modérer la plateforme.
* Filtrage avancé des Quêtes selon la date, le lieu, l'organisateur et l'état d'inscription.

##  Prérequis Installation

* PHP 8.2 ou supérieur
* Composer
* Node.js et NPM (pour la compilation Tailwind/DaisyUI)
* WAMP Server (avec MySQL/MariaDB lancé)

## Guide d'Installation Local

1. Clonez ce dépôt sur votre machine locale.
2. Ouvrez un terminal à la racine du projet et installez les dépendances PHP avec la commande `composer install`.
3. Installez les dépendances Front-End (Tailwind & DaisyUI) avec la commande `npm install`.
4. Dupliquez le fichier `.env` en `.env.local` et configurez votre connexion à la base de données (ex: `DATABASE_URL="mysql://root:@127.0.0.1:3306/taverne_eni?serverVersion=8.0.32&charset=utf8mb4"`).
5. Créez la base de données via le terminal avec `php bin/console d:d:c`.
6. Exécutez les migrations pour générer les tables avec `php bin/console d:m:m`.
7. Chargez le jeu de fausses données (Fixtures) pour peupler la Taverne avec `php bin/console d:f:l`.
8. Compilez les assets CSS/JS avec la commande `npm run dev` (ou `npm run watch` pour le rechargement automatique).
9. Lancez le serveur local Symfony avec `symfony serve -d`.
10. Accédez à l'application via `https://127.0.0.1:8000`.

## À propos de l'auteur

Ce projet a été développé dans le cadre de ma formation de Concepteur Développeur d'Applications (CDA) bac+3 à l'ENI. Il sert également de vitrine technique de mes compétences en développement Full-Stack.

Je suis actuellement à la recherche d'un stage du 20 avril au 12 juin 2026, ainsi que d'une entreprise pour une alternance à partir de l'année prochaine. N'hésitez pas à me contacter via mon profil GitHub ou mon LinkedIn pour discuter de ce projet ou de futures collaborations !
