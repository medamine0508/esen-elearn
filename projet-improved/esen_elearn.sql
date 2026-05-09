-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 30 avr. 2026 à 17:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `esen_elearn`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `couleur` varchar(10) DEFAULT '#3498db',
  `icone` varchar(50) DEFAULT 'fas fa-book'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`, `description`, `couleur`, `icone`) VALUES
(1, 'Informatique', 'Programmation et développement web', '#3498db', 'fas fa-laptop-code'),
(2, 'E-Business', 'Commerce électronique et marketing digital', '#e74c3c', 'fas fa-shopping-cart'),
(3, 'Management', 'Gestion de projet et entrepreneuriat', '#2ecc71', 'fas fa-briefcase'),
(4, 'Langues', 'Anglais des affaires et communication', '#f39c12', 'fas fa-language');

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id_cours` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `niveau` enum('debutant','intermediaire','avance') DEFAULT 'debutant',
  `duree_heures` decimal(5,1) DEFAULT NULL,
  `nb_lecons` int(11) DEFAULT 0,
  `image` varchar(255) NOT NULL DEFAULT 'cours1.jpg',
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp(),
  `id_categorie` int(11) NOT NULL,
  `id_professeur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id_cours`, `titre`, `description`, `niveau`, `duree_heures`, `nb_lecons`, `image`, `actif`, `date_creation`, `id_categorie`, `id_professeur`) VALUES
(1, 'PHP et MySQL pour débutants', 'Apprenez à créer des sites web dynamiques avec PHP et MySQL. Ce cours couvre les bases du PHP, la connexion à une base de données, les requêtes SQL, la gestion des formulaires et la sécurité.', 'debutant', 20.0, 15, 'cours1.png', 1, '2026-04-24 15:23:39', 1, 2),
(2, 'Marketing Digital et Réseaux Sociaux', 'Stratégie de marketing digital, SEO, réseaux sociaux et publicité en ligne. Apprenez à construire une présence digitale solide et à générer du trafic qualifié.', 'intermediaire', 15.0, 10, 'cours6.webp', 1, '2026-04-24 15:23:39', 2, 2),
(3, 'Gestion de Projet Agile Scrum', 'Méthodologie Scrum pour gérer vos projets de façon agile. Maîtrisez les sprints, les cérémonies Scrum, les rôles (Product Owner, Scrum Master) et la planification itérative.', 'intermediaire', 12.0, 8, 'cours3.webp', 1, '2026-04-24 15:23:39', 3, 2),
(4, 'JavaScript Avancé - ES6 et React', 'Maîtrisez JavaScript moderne avec ES6 et initiation à React.js. Destructuration, promesses, async/await, composants React, hooks et gestion d\'état.', 'avance', 30.0, 20, 'cours2.png', 1, '2026-04-24 15:23:39', 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id_inscription` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_cours` int(11) NOT NULL,
  `statut` enum('en_cours','termine','abandonne') DEFAULT 'en_cours',
  `progression` int(11) DEFAULT 0,
  `date_inscription` datetime DEFAULT current_timestamp(),
  `date_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id_inscription`, `id_etudiant`, `id_cours`, `statut`, `progression`, `date_inscription`, `date_fin`) VALUES
(1, 3, 1, 'en_cours', 65, '2026-04-24 15:23:39', NULL),
(2, 3, 2, 'termine', 100, '2026-04-24 15:23:39', '2026-04-24 16:00:00'),
(3, 4, 2, 'en_cours', 30, '2026-04-24 15:23:39', NULL),
(4, 4, 3, 'en_cours', 50, '2026-04-24 15:23:39', NULL),
(5, 5, 3, 'en_cours', 0, '2026-04-24 15:34:48', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `lecons`
--

CREATE TABLE `lecons` (
  `id_lecon` int(11) NOT NULL,
  `id_cours` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `contenu` text DEFAULT NULL,
  `ordre` int(11) DEFAULT 1,
  `duree_minutes` int(11) DEFAULT 0,
  `actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lecons`
--

INSERT INTO `lecons` (`id_lecon`, `id_cours`, `titre`, `contenu`, `ordre`, `duree_minutes`, `actif`) VALUES
(1, 1, 'Introduction à PHP', 'Variables, types, opérateurs', 1, 45, 1),
(2, 1, 'Structures de contrôle', 'If/else, boucles for, while, foreach', 2, 60, 1),
(3, 1, 'Fonctions et portée', 'Définir et appeler des fonctions, closures', 3, 50, 1),
(4, 1, 'Connexion MySQL avec PDO', 'DSN, prepare, execute, fetch', 4, 75, 1),
(5, 1, 'Formulaires et sécurité', 'GET/POST, CSRF, htmlspecialchars, validation', 5, 60, 1),
(6, 2, 'Introduction au Marketing Digital', 'Définition, canaux, KPIs', 1, 40, 1),
(7, 2, 'SEO On-Page', 'Balises meta, mots-clés, structure URL', 2, 55, 1),
(8, 2, 'Réseaux sociaux et Community Mgt', 'Facebook, Instagram, LinkedIn, stratégies', 3, 65, 1),
(9, 3, 'Les rôles Scrum', 'Product Owner, Scrum Master, Équipe Dev', 1, 45, 1),
(10, 3, 'Sprint Planning', 'Backlog, user stories, estimation', 2, 60, 1),
(11, 3, 'Daily Scrum et Rétrospective', 'Stand-up 15 min, amélioration continue', 3, 40, 1),
(12, 4, 'ES6 : Arrow functions et Classes', 'Syntaxe moderne JavaScript', 1, 50, 1),
(13, 4, 'Promesses et Async/Await', 'Gestion asynchrone propre', 2, 70, 1),
(14, 4, 'Introduction à React', 'JSX, composants fonctionnels, props', 3, 80, 1);

-- --------------------------------------------------------

--
-- Structure de la table `quiz`
--

CREATE TABLE `quiz` (
  `id_quiz` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `note_max` decimal(5,2) DEFAULT 20.00,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp(),
  `id_cours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quiz`
--

INSERT INTO `quiz` (`id_quiz`, `titre`, `description`, `note_max`, `actif`, `date_creation`, `id_cours`) VALUES
(1, 'Quiz PHP – Les bases', 'Variables, conditions et boucles', 20.00, 1, '2026-04-24 15:23:39', 1),
(2, 'Quiz Marketing – SEO', 'Techniques de référencement', 20.00, 1, '2026-04-24 15:23:39', 2),
(3, 'Quiz Scrum – Terminologie', 'Vocabulaire et rôles Scrum', 20.00, 1, '2026-04-24 15:23:39', 3);

-- --------------------------------------------------------

--
-- Structure de la table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id_question` int(11) NOT NULL,
  `id_quiz` int(11) NOT NULL,
  `question` text NOT NULL,
  `reponse_a` varchar(300) NOT NULL,
  `reponse_b` varchar(300) NOT NULL,
  `reponse_c` varchar(300) DEFAULT NULL,
  `reponse_d` varchar(300) DEFAULT NULL,
  `bonne_reponse` enum('a','b','c','d') NOT NULL,
  `points` decimal(4,2) DEFAULT 2.00,
  `ordre` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id_question`, `id_quiz`, `question`, `reponse_a`, `reponse_b`, `reponse_c`, `reponse_d`, `bonne_reponse`, `points`, `ordre`) VALUES
(1, 1, 'Quelle balise démarre un bloc PHP ?', '<?php', '<php>', '<!php>', '<script>', 'a', 2.00, 1),
(2, 1, 'Comment déclare-t-on une variable en PHP ?', '$nom', '#nom', '@nom', '&nom', 'a', 2.00, 2),
(3, 1, 'Quelle fonction affiche du texte en PHP ?', 'print()', 'echo()', 'console()', 'echo ou print', 'd', 2.00, 3),
(4, 1, 'PDO signifie :', 'PHP Data Objects', 'Portable Data Option', 'PHP Database Operator', 'Post Data Object', 'a', 2.00, 4),
(5, 1, 'Quelle méthode PDO sécurise les requêtes ?', 'prepare()', 'query()', 'exec()', 'connect()', 'a', 2.00, 5),
(6, 1, 'Quelle superglobale récupère un champ POST ?', '$_POST', '$_GET', '$_REQUEST', '$_FORM', 'a', 2.00, 6),
(7, 1, 'Quel opérateur compare valeur ET type ?', '===', '==', '=', '!==', 'a', 2.00, 7),
(8, 1, 'Fonction pour hacher un mot de passe en PHP ?', 'password_hash()', 'md5()', 'sha1()', 'encrypt()', 'a', 2.00, 8),
(9, 1, 'Quel tableau contient les variables de session ?', '$_SESSION', '$_COOKIE', '$_SERVER', '$_GLOBAL', 'a', 2.00, 9),
(10, 1, 'include() diffère de require() car :', 'require lève une erreur fatale si absent', 'include est plus rapide', 'ils sont identiques', 'require ignore les doublons', 'a', 2.00, 10),
(11, 2, 'SEO signifie :', 'Search Engine Optimization', 'Social Engine Operation', 'Site Edit Option', 'Search Edit Operator', 'a', 2.00, 1),
(12, 2, 'Quel outil Google mesure le trafic d\'un site ?', 'Google Analytics', 'Google Ads', 'Google Search Console', 'Google Tag Manager', 'a', 2.00, 2),
(13, 2, 'Le taux de rebond mesure :', 'La proportion de visites d\'une seule page', 'Le nombre de clics', 'Le revenu par visite', 'Le temps sur le site', 'a', 2.00, 3),
(14, 2, 'CTA signifie :', 'Call To Action', 'Click Through Ad', 'Content To Audience', 'Cost To Advertise', 'a', 2.00, 4),
(15, 2, 'KPI signifie :', 'Key Performance Indicator', 'Key Page Index', 'Known Page Impressions', 'Key Pricing Information', 'a', 2.00, 5),
(16, 2, 'Un backlink est :', 'Un lien d\'un autre site vers le vôtre', 'Un lien interne de votre site', 'Un lien brisé', 'Un lien sponsorisé', 'a', 2.00, 6),
(17, 2, 'Le marketing par email s\'appelle aussi :', 'Email marketing ou emailing', 'SEO', 'SEM', 'SMM', 'a', 2.00, 7),
(18, 2, 'CPM signifie :', 'Coût Pour Mille impressions', 'Coût Par Mois', 'Clics Par Minute', 'Coût Par Message', 'a', 2.00, 8),
(19, 2, 'Quel réseau est idéal pour le B2B ?', 'LinkedIn', 'TikTok', 'Snapchat', 'Pinterest', 'a', 2.00, 9),
(20, 2, 'La conversion désigne :', 'Une action cible réalisée par un visiteur', 'Un clic sur une publicité', 'Un partage de contenu', 'Un commentaire', 'a', 2.00, 10),
(21, 3, 'Qui priorise le Product Backlog ?', 'Product Owner', 'Scrum Master', 'Équipe de développement', 'Stakeholder', 'a', 2.00, 1),
(22, 3, 'Durée recommandée d\'un Daily Scrum ?', '15 minutes', '30 minutes', '1 heure', 'Pas de durée fixe', 'a', 2.00, 2),
(23, 3, 'Un Sprint dure en général :', '1 à 4 semaines', '1 jour', '3 mois', '6 mois', 'a', 2.00, 3),
(24, 3, 'La vélocité d\'une équipe Scrum mesure :', 'La quantité de travail accomplie par sprint', 'La vitesse de déploiement', 'Le nombre de bugs corrigés', 'Le temps de réunion', 'a', 2.00, 4),
(25, 3, 'Une User Story commence par :', 'En tant que [rôle], je veux [besoin]', 'Le système doit…', 'L\'application fera…', 'TODO:', 'a', 2.00, 5),
(26, 3, 'Le Scrum Master est responsable de :', 'Faciliter le processus Scrum et lever les obstacles', 'Écrire le code', 'Définir les exigences', 'Gérer le budget', 'a', 2.00, 6),
(27, 3, 'La rétrospective a lieu :', 'À la fin de chaque sprint', 'Chaque matin', 'Une fois par mois', 'Avant le sprint', 'a', 2.00, 7),
(28, 3, 'Le Definition of Done (DoD) définit :', 'Les critères pour qu\'une tâche soit terminée', 'La durée d\'un sprint', 'Le nombre de développeurs', 'Le budget du projet', 'a', 2.00, 8),
(29, 3, 'Le Sprint Review sert à :', 'Montrer les résultats aux parties prenantes', 'Corriger les bugs', 'Planifier le prochain sprint', 'Rédiger la documentation', 'a', 2.00, 9),
(30, 3, 'Agile met l\'accent sur :', 'La flexibilité et la collaboration', 'Les processus rigides', 'La documentation exhaustive', 'Les contrats stricts', 'a', 2.00, 10);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','professeur','etudiant') DEFAULT 'etudiant',
  `actif` tinyint(1) DEFAULT 1,
  `date_inscription` datetime DEFAULT current_timestamp(),
  `derniere_connexion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_user`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `actif`, `date_inscription`, `derniere_connexion`) VALUES
(1, 'Admin', 'ESEN', 'admin@esen.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '2026-04-24 15:23:39', NULL),
(2, 'Ben Ali', 'Mohamed', 'prof@esen.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professeur', 1, '2026-04-24 15:23:39', NULL),
(3, 'Trabelsi', 'Aziz', 'etudiant@esen.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 1, '2026-04-24 15:23:39', NULL),
(4, 'Gharbi', 'Sonia', 'sonia@esen.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 1, '2026-04-24 15:23:39', NULL),
(5, 'Gabsi', 'Mohamed', 'mohamedaziz.gabsi@esen.tn', '$2y$10$4nhi.QgpwFAqVySSDTtOreADkV/Ri9.58UdrGdb2nRrhRBvYBI4my', 'etudiant', 1, '2026-04-24 15:34:39', '2026-04-24 16:34:44');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id_cours`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_professeur` (`id_professeur`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id_inscription`),
  ADD UNIQUE KEY `uq_inscription` (`id_etudiant`,`id_cours`),
  ADD KEY `id_cours` (`id_cours`);

--
-- Index pour la table `lecons`
--
ALTER TABLE `lecons`
  ADD PRIMARY KEY (`id_lecon`),
  ADD KEY `id_cours` (`id_cours`);

--
-- Index pour la table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id_quiz`),
  ADD KEY `id_cours` (`id_cours`);

--
-- Index pour la table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id_question`),
  ADD KEY `id_quiz` (`id_quiz`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id_cours` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id_inscription` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `lecons`
--
ALTER TABLE `lecons`
  MODIFY `id_lecon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id_quiz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id_question` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`),
  ADD CONSTRAINT `cours_ibfk_2` FOREIGN KEY (`id_professeur`) REFERENCES `utilisateurs` (`id_user`);

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `utilisateurs` (`id_user`),
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`);

--
-- Contraintes pour la table `lecons`
--
ALTER TABLE `lecons`
  ADD CONSTRAINT `lecons_ibfk_1` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`);

--
-- Contraintes pour la table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id_quiz`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
