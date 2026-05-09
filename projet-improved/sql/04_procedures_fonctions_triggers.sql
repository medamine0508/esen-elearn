-- ============================================================
-- ESEN E-LEARN – Script 4 : Procédures stockées, Fonctions,
--                           Curseurs et Triggers
-- SGBD  : MySQL 8 / MariaDB  (compatible phpMyAdmin)
-- Note  : MySQL utilise BEGIN…END avec DELIMITER $$
--         équivalent au PL/SQL Oracle BEGIN…END /
-- ============================================================

USE esen_elearn;

-- Changer le délimiteur pour permettre l'écriture des blocs
DELIMITER $$

-- ============================================================
-- SECTION A : PROCÉDURES STOCKÉES (Stored Procedures)
-- ============================================================

-- ----------------------------------------------------------
-- Procédure P1 : inscrire_etudiant
-- But   : Inscrire un étudiant à un cours en vérifiant
--         qu'il n'est pas déjà inscrit (curseur implicite)
-- Usage : CALL inscrire_etudiant(3, 4);
-- ----------------------------------------------------------
DROP PROCEDURE IF EXISTS inscrire_etudiant $$
CREATE PROCEDURE inscrire_etudiant(
    IN  p_id_etudiant INT,   -- Identifiant de l'étudiant
    IN  p_id_cours    INT    -- Identifiant du cours
)
BEGIN
    -- Déclaration des variables locales
    DECLARE v_existe      INT DEFAULT 0;   -- Nombre d'inscriptions existantes
    DECLARE v_cours_actif INT DEFAULT 0;   -- Statut du cours

    -- Curseur implicite : SELECT INTO (MySQL/PL-SQL)
    -- Vérifie si l'étudiant est déjà inscrit
    SELECT COUNT(*) INTO v_existe
    FROM inscriptions
    WHERE id_etudiant = p_id_etudiant
      AND id_cours    = p_id_cours;

    -- Vérifie que le cours est actif
    SELECT actif INTO v_cours_actif
    FROM cours
    WHERE id_cours = p_id_cours;

    -- Logique métier avec IF / ELSEIF / END IF
    IF v_existe > 0 THEN
        -- L'étudiant est déjà inscrit → message d'avertissement
        SELECT 'ERREUR : Étudiant déjà inscrit à ce cours' AS message;

    ELSEIF v_cours_actif = 0 THEN
        -- Le cours est désactivé
        SELECT 'ERREUR : Ce cours n\'est pas disponible' AS message;

    ELSE
        -- Insertion de la nouvelle inscription
        INSERT INTO inscriptions (id_etudiant, id_cours, statut, progression)
        VALUES (p_id_etudiant, p_id_cours, 'en_cours', 0);

        -- Confirmation de succès
        SELECT CONCAT('OK : Étudiant inscrit avec succès. id_inscription = ',
                       LAST_INSERT_ID()) AS message;
    END IF;

END $$

-- ----------------------------------------------------------
-- Procédure P2 : mettre_a_jour_progression
-- But   : Met à jour la progression et change le statut
--         automatiquement à "termine" si progression = 100
-- Usage : CALL mettre_a_jour_progression(3, 1, 100);
-- ----------------------------------------------------------
DROP PROCEDURE IF EXISTS mettre_a_jour_progression $$
CREATE PROCEDURE mettre_a_jour_progression(
    IN p_id_etudiant  INT,
    IN p_id_cours     INT,
    IN p_progression  INT    -- Valeur entre 0 et 100
)
BEGIN
    -- Variable locale pour le nouveau statut
    DECLARE v_statut VARCHAR(20) DEFAULT 'en_cours';
    DECLARE v_date_fin DATETIME  DEFAULT NULL;

    -- Validation de la progression (entre 0 et 100)
    IF p_progression < 0   THEN SET p_progression = 0; END IF;
    IF p_progression > 100 THEN SET p_progression = 100; END IF;

    -- Si la progression atteint 100%, marquer comme terminé
    IF p_progression = 100 THEN
        SET v_statut   = 'termine';
        SET v_date_fin = NOW();   -- Enregistrer la date de fin
    END IF;

    -- Mise à jour de l'inscription
    UPDATE inscriptions
    SET progression = p_progression,
        statut      = v_statut,
        date_fin    = v_date_fin
    WHERE id_etudiant = p_id_etudiant
      AND id_cours    = p_id_cours;

    -- Retourner le résultat mis à jour
    SELECT i.progression, i.statut, i.date_fin,
           CONCAT(u.prenom, ' ', u.nom) AS etudiant,
           c.titre                       AS cours
    FROM inscriptions i
    JOIN utilisateurs u ON i.id_etudiant = u.id_user
    JOIN cours c        ON i.id_cours    = c.id_cours
    WHERE i.id_etudiant = p_id_etudiant
      AND i.id_cours    = p_id_cours;

END $$

-- ----------------------------------------------------------
-- Procédure P3 : rapport_inscriptions_cours (curseur EXPLICITE)
-- But   : Parcourir toutes les inscriptions d'un cours
--         et afficher un rapport ligne par ligne
-- Usage : CALL rapport_inscriptions_cours(1);
-- ----------------------------------------------------------
DROP PROCEDURE IF EXISTS rapport_inscriptions_cours $$
CREATE PROCEDURE rapport_inscriptions_cours(
    IN p_id_cours INT
)
BEGIN
    -- Variables pour stocker les données du curseur
    DECLARE v_nom        VARCHAR(50);
    DECLARE v_prenom     VARCHAR(50);
    DECLARE v_statut     VARCHAR(20);
    DECLARE v_progression INT;
    DECLARE v_date_inscr  DATETIME;
    DECLARE v_termine     INT DEFAULT 0;  -- Indicateur de fin de curseur

    -- === DÉCLARATION DU CURSEUR EXPLICITE ===
    -- Sélectionne toutes les inscriptions du cours demandé
    DECLARE cur_inscriptions CURSOR FOR
        SELECT u.nom, u.prenom, i.statut, i.progression, i.date_inscription
        FROM inscriptions i
        INNER JOIN utilisateurs u ON i.id_etudiant = u.id_user
        WHERE i.id_cours = p_id_cours
        ORDER BY i.date_inscription;

    -- Handler : quand plus de lignes → mettre v_termine à 1
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_termine = 1;

    -- Créer une table temporaire pour stocker le rapport
    DROP TEMPORARY TABLE IF EXISTS tmp_rapport;
    CREATE TEMPORARY TABLE tmp_rapport (
        nom_complet  VARCHAR(101),
        statut       VARCHAR(20),
        progression  INT,
        date_inscr   DATETIME
    );

    -- === OUVERTURE DU CURSEUR ===
    OPEN cur_inscriptions;

    -- === BOUCLE DE PARCOURS ===
    boucle_rapport: LOOP
        -- Récupérer la ligne suivante (FETCH)
        FETCH cur_inscriptions
        INTO v_nom, v_prenom, v_statut, v_progression, v_date_inscr;

        -- Sortir de la boucle si plus de données
        IF v_termine = 1 THEN
            LEAVE boucle_rapport;
        END IF;

        -- Insérer dans la table temporaire
        INSERT INTO tmp_rapport
        VALUES (CONCAT(v_prenom, ' ', v_nom),
                v_statut,
                v_progression,
                v_date_inscr);

    END LOOP boucle_rapport;

    -- === FERMETURE DU CURSEUR ===
    CLOSE cur_inscriptions;

    -- Afficher le rapport complet
    SELECT * FROM tmp_rapport ORDER BY statut, nom_complet;
    DROP TEMPORARY TABLE IF EXISTS tmp_rapport;

END $$

-- ----------------------------------------------------------
-- Procédure P4 : statistiques_globales
-- But   : Calcule et affiche les statistiques complètes
--         de la plateforme (utilisée dans le dashboard admin)
-- Usage : CALL statistiques_globales();
-- ----------------------------------------------------------
DROP PROCEDURE IF EXISTS statistiques_globales $$
CREATE PROCEDURE statistiques_globales()
BEGIN
    -- Déclarations de variables pour chaque statistique
    DECLARE v_total_etudiants    INT;
    DECLARE v_total_professeurs  INT;
    DECLARE v_total_cours        INT;
    DECLARE v_total_inscriptions INT;
    DECLARE v_cours_termines     INT;
    DECLARE v_progression_moy    DECIMAL(5,2);

    -- Curseurs implicites : SELECT INTO
    SELECT COUNT(*) INTO v_total_etudiants
      FROM utilisateurs WHERE role='etudiant' AND actif=1;

    SELECT COUNT(*) INTO v_total_professeurs
      FROM utilisateurs WHERE role='professeur' AND actif=1;

    SELECT COUNT(*) INTO v_total_cours
      FROM cours WHERE actif=1;

    SELECT COUNT(*) INTO v_total_inscriptions
      FROM inscriptions;

    SELECT COUNT(*) INTO v_cours_termines
      FROM inscriptions WHERE statut='termine';

    SELECT ROUND(AVG(progression), 2) INTO v_progression_moy
      FROM inscriptions;

    -- Affichage des statistiques
    SELECT
        v_total_etudiants    AS 'Étudiants actifs',
        v_total_professeurs  AS 'Professeurs actifs',
        v_total_cours        AS 'Cours disponibles',
        v_total_inscriptions AS 'Total inscriptions',
        v_cours_termines     AS 'Cours terminés',
        CONCAT(v_progression_moy, '%') AS 'Progression moyenne';

END $$


-- ============================================================
-- SECTION B : FONCTIONS STOCKÉES (Functions)
-- ============================================================

-- ----------------------------------------------------------
-- Fonction F1 : get_progression_etudiant
-- But   : Retourne la progression d'un étudiant dans un cours
-- Usage : SELECT get_progression_etudiant(3, 1);
-- ----------------------------------------------------------
DROP FUNCTION IF EXISTS get_progression_etudiant $$
CREATE FUNCTION get_progression_etudiant(
    p_id_etudiant INT,
    p_id_cours    INT
)
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_progression INT DEFAULT 0;  -- Valeur par défaut

    -- Récupérer la progression (curseur implicite)
    SELECT progression INTO v_progression
    FROM inscriptions
    WHERE id_etudiant = p_id_etudiant
      AND id_cours    = p_id_cours
    LIMIT 1;

    -- Retourner la valeur (0 si non inscrit)
    RETURN COALESCE(v_progression, 0);
END $$

-- ----------------------------------------------------------
-- Fonction F2 : est_inscrit
-- But   : Vérifie si un étudiant est inscrit à un cours
--         Retourne 1 (oui) ou 0 (non)
-- Usage : SELECT est_inscrit(3, 1);
-- ----------------------------------------------------------
DROP FUNCTION IF EXISTS est_inscrit $$
CREATE FUNCTION est_inscrit(
    p_id_etudiant INT,
    p_id_cours    INT
)
RETURNS TINYINT(1)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_count TINYINT(1) DEFAULT 0;

    -- Compter les inscriptions correspondantes
    SELECT COUNT(*) INTO v_count
    FROM inscriptions
    WHERE id_etudiant = p_id_etudiant
      AND id_cours    = p_id_cours;

    RETURN v_count;   -- 1 si inscrit, 0 sinon
END $$

-- ----------------------------------------------------------
-- Fonction F3 : calculer_note_quiz
-- But   : Calcule la note maximale possible d'un quiz
--         en additionnant les points de toutes les questions
-- Usage : SELECT calculer_note_quiz(1);
-- ----------------------------------------------------------
DROP FUNCTION IF EXISTS calculer_note_quiz $$
CREATE FUNCTION calculer_note_quiz(p_id_quiz INT)
RETURNS DECIMAL(5,2)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_note_totale DECIMAL(5,2) DEFAULT 0.00;

    -- Somme des points de toutes les questions du quiz
    SELECT SUM(points) INTO v_note_totale
    FROM quiz_questions
    WHERE id_quiz = p_id_quiz;

    RETURN COALESCE(v_note_totale, 0.00);
END $$

-- ----------------------------------------------------------
-- Fonction F4 : nb_cours_etudiant
-- But   : Retourne le nombre de cours auxquels
--         un étudiant est inscrit
-- Usage : SELECT nb_cours_etudiant(3);
-- ----------------------------------------------------------
DROP FUNCTION IF EXISTS nb_cours_etudiant $$
CREATE FUNCTION nb_cours_etudiant(p_id_etudiant INT)
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_nb INT DEFAULT 0;

    SELECT COUNT(*) INTO v_nb
    FROM inscriptions
    WHERE id_etudiant = p_id_etudiant;

    RETURN v_nb;
END $$


-- ============================================================
-- SECTION C : TRIGGERS (Déclencheurs)
-- ============================================================

-- ----------------------------------------------------------
-- Trigger T1 : trg_nb_lecons_insert
-- But   : Après l'ajout d'une leçon, incrémenter
--         le compteur nb_lecons dans la table cours
-- Événement : AFTER INSERT ON lecons
-- ----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_nb_lecons_insert $$
CREATE TRIGGER trg_nb_lecons_insert
AFTER INSERT ON lecons
FOR EACH ROW
BEGIN
    -- Mettre à jour le compteur de leçons dans cours
    -- NEW.id_cours = identifiant du cours de la leçon insérée
    UPDATE cours
    SET nb_lecons = nb_lecons + 1
    WHERE id_cours = NEW.id_cours;
END $$

-- ----------------------------------------------------------
-- Trigger T2 : trg_nb_lecons_delete
-- But   : Après la suppression d'une leçon, décrémenter
--         le compteur nb_lecons dans la table cours
-- Événement : AFTER DELETE ON lecons
-- ----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_nb_lecons_delete $$
CREATE TRIGGER trg_nb_lecons_delete
AFTER DELETE ON lecons
FOR EACH ROW
BEGIN
    -- Décrémenter le compteur (pas en dessous de 0)
    UPDATE cours
    SET nb_lecons = GREATEST(nb_lecons - 1, 0)
    WHERE id_cours = OLD.id_cours;
    -- OLD.id_cours = identifiant du cours de la leçon supprimée
END $$

-- ----------------------------------------------------------
-- Trigger T3 : trg_valider_progression
-- But   : Avant la mise à jour d'une inscription,
--         vérifier que la progression est entre 0 et 100
--         et changer automatiquement le statut
-- Événement : BEFORE UPDATE ON inscriptions
-- ----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_valider_progression $$
CREATE TRIGGER trg_valider_progression
BEFORE UPDATE ON inscriptions
FOR EACH ROW
BEGIN
    -- Corriger la progression si hors limites
    IF NEW.progression < 0   THEN SET NEW.progression = 0; END IF;
    IF NEW.progression > 100 THEN SET NEW.progression = 100; END IF;

    -- Mettre à jour le statut automatiquement
    IF NEW.progression = 100 THEN
        SET NEW.statut   = 'termine';   -- Cours terminé
        SET NEW.date_fin = NOW();       -- Date de complétion
    ELSEIF NEW.progression > 0 THEN
        SET NEW.statut = 'en_cours';    -- En cours d'apprentissage
    END IF;
END $$

-- ----------------------------------------------------------
-- Trigger T4 : trg_log_connexion
-- But   : Mettre à jour derniere_connexion lors de la
--         modification du champ (simulé par UPDATE)
-- Événement : BEFORE UPDATE ON utilisateurs
-- ----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_log_connexion $$
CREATE TRIGGER trg_log_connexion
BEFORE UPDATE ON utilisateurs
FOR EACH ROW
BEGIN
    -- Si le mot de passe est modifié ou connexion détectée,
    -- mettre à jour la date de dernière connexion
    IF NEW.derniere_connexion IS NOT NULL
       AND (OLD.derniere_connexion IS NULL
            OR NEW.derniere_connexion > OLD.derniere_connexion) THEN
        -- La date est déjà fournie par l'application
        -- Ce trigger valide simplement que la date n'est pas future
        IF NEW.derniere_connexion > NOW() THEN
            SET NEW.derniere_connexion = NOW();
        END IF;
    END IF;
END $$

-- Rétablir le délimiteur standard
DELIMITER ;

-- ============================================================
-- SECTION D : TESTS DES PROCÉDURES ET FONCTIONS
-- ============================================================

-- Test P1 : Inscrire un étudiant à un cours
CALL inscrire_etudiant(3, 3);   -- Déjà inscrit → message d'erreur
CALL inscrire_etudiant(4, 4);   -- Nouvelle inscription → succès

-- Test P2 : Mettre à jour la progression
CALL mettre_a_jour_progression(3, 1, 100);  -- Passer à 100% → terminé

-- Test P3 : Rapport des inscriptions du cours 1
CALL rapport_inscriptions_cours(1);

-- Test P4 : Statistiques globales
CALL statistiques_globales();

-- Test F1 : Progression d'un étudiant
SELECT get_progression_etudiant(3, 1) AS progression_aziz_cours1;

-- Test F2 : Vérifier inscription
SELECT est_inscrit(3, 1) AS aziz_inscrit_cours1,
       est_inscrit(3, 4) AS aziz_inscrit_cours4;

-- Test F3 : Note maximale quiz
SELECT calculer_note_quiz(1) AS note_max_quiz_php;

-- Test F4 : Nombre de cours d'un étudiant
SELECT nb_cours_etudiant(3) AS nb_cours_aziz;

-- Test Trigger T1 : Ajouter une leçon et vérifier le compteur
INSERT INTO lecons (id_cours, titre, contenu, ordre, duree_minutes)
VALUES (1, 'Leçon de test trigger', 'Contenu test', 6, 30);

SELECT id_cours, titre, nb_lecons FROM cours WHERE id_cours = 1;
-- Résultat attendu : nb_lecons = 16

-- Test Trigger T2 : Supprimer la leçon ajoutée
DELETE FROM lecons WHERE titre = 'Leçon de test trigger';
SELECT id_cours, titre, nb_lecons FROM cours WHERE id_cours = 1;
-- Résultat attendu : nb_lecons = 15

-- Test Trigger T3 : Mettre à jour une progression > 100
UPDATE inscriptions SET progression = 150 WHERE id_etudiant = 4 AND id_cours = 2;
SELECT progression, statut FROM inscriptions WHERE id_etudiant = 4 AND id_cours = 2;
-- Résultat attendu : progression = 100, statut = 'termine'

-- Remettre les données de test à leur état initial
UPDATE inscriptions SET progression = 65, statut = 'en_cours', date_fin = NULL
WHERE id_etudiant = 3 AND id_cours = 1;
UPDATE inscriptions SET progression = 30, statut = 'en_cours', date_fin = NULL
WHERE id_etudiant = 4 AND id_cours = 2;
