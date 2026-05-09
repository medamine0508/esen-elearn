-- ============================================================
-- ESEN E-LEARN – Script 5 : Annexe – Inventaire des objets BD
-- But   : Lister toutes les tables, procédures, fonctions,
--         triggers, vues et utilisateurs du schéma
-- SGBD  : MySQL 8 / MariaDB
-- ============================================================

USE esen_elearn;

-- ----------------------------------------------------------
-- 1. Liste des tables et leurs colonnes
-- ----------------------------------------------------------
SELECT
    TABLE_NAME        AS 'Table',
    COLUMN_NAME       AS 'Colonne',
    COLUMN_TYPE       AS 'Type',
    IS_NULLABLE       AS 'NULL autorisé',
    COLUMN_KEY        AS 'Clé',
    COLUMN_DEFAULT    AS 'Valeur par défaut',
    EXTRA             AS 'Extra'
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'esen_elearn'
ORDER BY TABLE_NAME, ORDINAL_POSITION;

-- ----------------------------------------------------------
-- 2. Liste des clés étrangères (contraintes FK)
-- ----------------------------------------------------------
SELECT
    CONSTRAINT_NAME   AS 'Contrainte',
    TABLE_NAME        AS 'Table source',
    COLUMN_NAME       AS 'Colonne source',
    REFERENCED_TABLE_NAME  AS 'Table référencée',
    REFERENCED_COLUMN_NAME AS 'Colonne référencée'
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA       = 'esen_elearn'
  AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY TABLE_NAME;

-- ----------------------------------------------------------
-- 3. Liste des procédures stockées
-- ----------------------------------------------------------
SELECT
    ROUTINE_NAME AS 'Procédure',
    ROUTINE_TYPE AS 'Type',
    CREATED      AS 'Créée le',
    MODIFIED     AS 'Modifiée le'
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = 'esen_elearn'
  AND ROUTINE_TYPE   = 'PROCEDURE'
ORDER BY ROUTINE_NAME;

-- ----------------------------------------------------------
-- 4. Liste des fonctions stockées
-- ----------------------------------------------------------
SELECT
    ROUTINE_NAME    AS 'Fonction',
    DTD_IDENTIFIER  AS 'Type retour',
    CREATED         AS 'Créée le'
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = 'esen_elearn'
  AND ROUTINE_TYPE   = 'FUNCTION'
ORDER BY ROUTINE_NAME;

-- ----------------------------------------------------------
-- 5. Liste des triggers
-- ----------------------------------------------------------
SELECT
    TRIGGER_NAME     AS 'Trigger',
    EVENT_MANIPULATION AS 'Événement',
    EVENT_OBJECT_TABLE AS 'Table concernée',
    ACTION_TIMING    AS 'Moment'
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = 'esen_elearn'
ORDER BY EVENT_OBJECT_TABLE, ACTION_TIMING;

-- ----------------------------------------------------------
-- 6. Liste des index
-- ----------------------------------------------------------
SELECT
    TABLE_NAME  AS 'Table',
    INDEX_NAME  AS 'Index',
    COLUMN_NAME AS 'Colonne',
    NON_UNIQUE  AS 'Non unique'
FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = 'esen_elearn'
ORDER BY TABLE_NAME, INDEX_NAME;

-- ----------------------------------------------------------
-- 7. Liste des utilisateurs MySQL et leurs hôtes
-- ----------------------------------------------------------
SELECT user AS 'Utilisateur', host AS 'Hôte'
FROM mysql.user
WHERE user IN ('admin_esen','prof_esen','etudiant_esen','lecture_esen','root')
ORDER BY user;

-- ----------------------------------------------------------
-- 8. Récapitulatif des objets du schéma
-- ----------------------------------------------------------
SELECT 'Tables'       AS 'Type d\'objet', COUNT(*) AS 'Nombre'
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'esen_elearn' AND TABLE_TYPE = 'BASE TABLE'

UNION ALL

SELECT 'Procédures', COUNT(*)
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = 'esen_elearn' AND ROUTINE_TYPE = 'PROCEDURE'

UNION ALL

SELECT 'Fonctions', COUNT(*)
FROM INFORMATION_SCHEMA.ROUTINES
WHERE ROUTINE_SCHEMA = 'esen_elearn' AND ROUTINE_TYPE = 'FUNCTION'

UNION ALL

SELECT 'Triggers', COUNT(*)
FROM INFORMATION_SCHEMA.TRIGGERS
WHERE TRIGGER_SCHEMA = 'esen_elearn';
