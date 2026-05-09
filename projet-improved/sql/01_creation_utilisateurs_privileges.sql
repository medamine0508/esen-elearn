-- ============================================================
-- ESEN E-LEARN – Script 1 : Création des utilisateurs MySQL
--                           et gestion des privilèges
-- SGBD  : MySQL 8 / MariaDB  (compatible phpMyAdmin)
-- Auteur: Équipe Projet ESEN
-- Date  : 2026
-- ============================================================

-- ----------------------------------------------------------
-- 1. Création de la base de données
-- ----------------------------------------------------------
DROP DATABASE IF EXISTS esen_elearn;
CREATE DATABASE esen_elearn
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE esen_elearn;

-- ----------------------------------------------------------
-- 2. Création des utilisateurs MySQL
--    (équivalent CREATE USER en Oracle)
-- ----------------------------------------------------------

-- Supprimer les utilisateurs s'ils existent déjà
DROP USER IF EXISTS 'admin_esen'@'localhost';
DROP USER IF EXISTS 'prof_esen'@'localhost';
DROP USER IF EXISTS 'etudiant_esen'@'localhost';
DROP USER IF EXISTS 'lecture_esen'@'localhost';

-- Utilisateur administrateur (accès total à esen_elearn)
CREATE USER 'admin_esen'@'localhost'
  IDENTIFIED BY 'Admin@ESEN2026!';

-- Utilisateur professeur (lecture + modification des cours/leçons)
CREATE USER 'prof_esen'@'localhost'
  IDENTIFIED BY 'Prof@ESEN2026!';

-- Utilisateur étudiant (lecture + inscription)
CREATE USER 'etudiant_esen'@'localhost'
  IDENTIFIED BY 'Etud@ESEN2026!';

-- Utilisateur lecture seule (reporting)
CREATE USER 'lecture_esen'@'localhost'
  IDENTIFIED BY 'Lect@ESEN2026!';

-- ----------------------------------------------------------
-- 3. Attribution des privilèges (GRANT)
-- ----------------------------------------------------------

-- Administrateur : tous les droits sur la base
GRANT ALL PRIVILEGES
  ON esen_elearn.*
  TO 'admin_esen'@'localhost';

-- Professeur : lecture globale + modification des tables
--              liées aux cours et aux leçons
GRANT SELECT, INSERT, UPDATE, DELETE
  ON esen_elearn.cours
  TO 'prof_esen'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE
  ON esen_elearn.lecons
  TO 'prof_esen'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE
  ON esen_elearn.quiz
  TO 'prof_esen'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE
  ON esen_elearn.quiz_questions
  TO 'prof_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.categories
  TO 'prof_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.utilisateurs
  TO 'prof_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.inscriptions
  TO 'prof_esen'@'localhost';

-- Étudiant : lecture des cours + gestion de ses inscriptions
GRANT SELECT
  ON esen_elearn.cours
  TO 'etudiant_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.categories
  TO 'etudiant_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.lecons
  TO 'etudiant_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.quiz
  TO 'etudiant_esen'@'localhost';

GRANT SELECT
  ON esen_elearn.quiz_questions
  TO 'etudiant_esen'@'localhost';

GRANT SELECT, INSERT, UPDATE
  ON esen_elearn.inscriptions
  TO 'etudiant_esen'@'localhost';

-- Lecture seule : uniquement SELECT sur toutes les tables
GRANT SELECT
  ON esen_elearn.*
  TO 'lecture_esen'@'localhost';

-- Appliquer immédiatement les privilèges
FLUSH PRIVILEGES;

-- ----------------------------------------------------------
-- 4. Vérification des utilisateurs créés
-- ----------------------------------------------------------
-- Exécuter après création pour vérifier :
-- SELECT user, host FROM mysql.user
-- WHERE user IN ('admin_esen','prof_esen','etudiant_esen','lecture_esen');

-- Vérifier les privilèges d'un utilisateur :
-- SHOW GRANTS FOR 'prof_esen'@'localhost';
