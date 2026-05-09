-- ============================================================
-- ESEN E-LEARN – Script 3 : Requêtes d'interrogation
--                           et de modification
-- SGBD  : MySQL 8 / MariaDB  (compatible phpMyAdmin)
-- Couvre : SELECT simple, jointures, GROUP BY, HAVING,
--          sous-requêtes, EXISTS, UNION, INSERT, UPDATE, DELETE
-- ============================================================

USE esen_elearn;

-- ============================================================
-- SECTION A : REQUÊTES SELECT SIMPLES (projection, sélection)
-- ============================================================

-- A1. Liste de tous les cours actifs
-- Affiche le titre, le niveau et la durée de chaque cours actif
SELECT titre, niveau, duree_heures
FROM cours
WHERE actif = 1
ORDER BY titre ASC;

-- A2. Liste des utilisateurs avec leur rôle
-- Filtre uniquement les comptes actifs
SELECT CONCAT(prenom, ' ', nom) AS nom_complet,
       email,
       role,
       date_inscription
FROM utilisateurs
WHERE actif = 1
ORDER BY role, nom;

-- A3. Cours de niveau avancé dont la durée dépasse 20 heures
SELECT titre, duree_heures, niveau
FROM cours
WHERE niveau = 'avance'
  AND duree_heures > 20;

-- A4. Étudiants inscrits à au moins un cours avec statut "en_cours"
SELECT DISTINCT u.nom, u.prenom, u.email
FROM utilisateurs u
JOIN inscriptions i ON u.id_user = i.id_etudiant
WHERE i.statut = 'en_cours';

-- ============================================================
-- SECTION B : JOINTURES (INNER JOIN, LEFT JOIN)
-- ============================================================

-- B1. Cours avec leur catégorie et le nom du professeur (INNER JOIN)
-- Requête utilisée dans CoursModel::findAllComplets()
SELECT c.id_cours,
       c.titre,
       c.niveau,
       c.duree_heures,
       cat.nom_categorie,
       cat.couleur,
       CONCAT(u.prenom, ' ', u.nom) AS nom_professeur
FROM cours c
INNER JOIN categories   cat ON c.id_categorie  = cat.id_categorie
INNER JOIN utilisateurs u   ON c.id_professeur = u.id_user
WHERE c.actif = 1
ORDER BY c.date_creation DESC;

-- B2. Cours avec le nombre d'inscrits (LEFT JOIN pour inclure
--     les cours sans inscription)
SELECT c.titre,
       cat.nom_categorie,
       COUNT(i.id_inscription) AS nb_inscrits,
       ROUND(AVG(i.progression), 1) AS progression_moyenne
FROM cours c
INNER JOIN categories   cat ON c.id_categorie = cat.id_categorie
LEFT  JOIN inscriptions i   ON c.id_cours     = i.id_cours
GROUP BY c.id_cours, c.titre, cat.nom_categorie
ORDER BY nb_inscrits DESC;

-- B3. Détail complet d'un cours (jointure multiple + agrégation)
-- Requête utilisée dans CoursModel::findCompletById()
SELECT c.*,
       cat.nom_categorie,
       cat.couleur,
       cat.icone,
       CONCAT(u.prenom, ' ', u.nom) AS nom_professeur,
       u.email                       AS email_professeur,
       COUNT(DISTINCT i.id_inscription) AS nb_inscrits,
       AVG(i.progression)               AS progression_moyenne
FROM cours c
INNER JOIN categories   cat ON c.id_categorie  = cat.id_categorie
INNER JOIN utilisateurs u   ON c.id_professeur = u.id_user
LEFT  JOIN inscriptions i   ON c.id_cours      = i.id_cours
WHERE c.id_cours = 1
GROUP BY c.id_cours, cat.nom_categorie, cat.couleur, cat.icone,
         u.prenom, u.nom, u.email;

-- B4. Leçons d'un cours avec son titre
SELECT l.ordre, l.titre, l.duree_minutes, c.titre AS titre_cours
FROM lecons l
INNER JOIN cours c ON l.id_cours = c.id_cours
WHERE c.id_cours = 1
ORDER BY l.ordre;

-- ============================================================
-- SECTION C : GROUP BY / HAVING
-- ============================================================

-- C1. Catégories ayant plus d'un cours actif
SELECT cat.nom_categorie,
       COUNT(c.id_cours) AS nombre_cours,
       SUM(c.duree_heures) AS total_heures
FROM categories cat
INNER JOIN cours c ON cat.id_categorie = c.id_categorie
WHERE c.actif = 1
GROUP BY cat.id_categorie, cat.nom_categorie
HAVING COUNT(c.id_cours) >= 1
ORDER BY nombre_cours DESC;

-- C2. Étudiants dont la progression moyenne dépasse 40%
SELECT u.nom, u.prenom,
       COUNT(i.id_inscription)  AS nb_cours,
       ROUND(AVG(i.progression), 1) AS progression_moy
FROM utilisateurs u
INNER JOIN inscriptions i ON u.id_user = i.id_etudiant
GROUP BY u.id_user, u.nom, u.prenom
HAVING AVG(i.progression) > 40
ORDER BY progression_moy DESC;

-- C3. Cours avec plus de 5 leçons
SELECT c.titre, COUNT(l.id_lecon) AS nb_lecons_reelles
FROM cours c
LEFT JOIN lecons l ON c.id_cours = l.id_cours AND l.actif = 1
GROUP BY c.id_cours, c.titre
HAVING COUNT(l.id_lecon) > 5
ORDER BY nb_lecons_reelles DESC;

-- ============================================================
-- SECTION D : SOUS-REQUÊTES (Subqueries)
-- ============================================================

-- D1. Cours dont la durée est supérieure à la moyenne
SELECT titre, duree_heures, niveau
FROM cours
WHERE duree_heures > (
    SELECT AVG(duree_heures) FROM cours WHERE actif = 1
)
ORDER BY duree_heures DESC;

-- D2. Étudiants qui ne sont inscrits à aucun cours (sous-requête NOT IN)
SELECT nom, prenom, email
FROM utilisateurs
WHERE role = 'etudiant'
  AND id_user NOT IN (
      SELECT DISTINCT id_etudiant FROM inscriptions
  );

-- D3. Cours ayant au moins un quiz actif (sous-requête EXISTS)
SELECT c.titre, c.niveau
FROM cours c
WHERE EXISTS (
    SELECT 1 FROM quiz q
    WHERE q.id_cours = c.id_cours AND q.actif = 1
);

-- D4. Cours sans quiz (sous-requête NOT EXISTS)
SELECT c.titre
FROM cours c
WHERE NOT EXISTS (
    SELECT 1 FROM quiz q WHERE q.id_cours = c.id_cours
);

-- D5. Statistiques par étudiant (sous-requête scalaire corrélée)
SELECT u.nom, u.prenom,
       (SELECT COUNT(*) FROM inscriptions i
        WHERE i.id_etudiant = u.id_user) AS nb_inscriptions,
       (SELECT COUNT(*) FROM inscriptions i
        WHERE i.id_etudiant = u.id_user AND i.statut = 'termine') AS cours_termines
FROM utilisateurs u
WHERE u.role = 'etudiant';

-- ============================================================
-- SECTION E : UNION / INTERSECT / EXCEPT
-- ============================================================

-- E1. UNION : Liste unifiée cours débutant + cours avancé
--     (avec étiquette pour distinguer les groupes)
SELECT titre, niveau, 'Priorité haute' AS recommandation
FROM cours
WHERE niveau = 'debutant' AND actif = 1

UNION ALL

SELECT titre, niveau, 'Expert requis' AS recommandation
FROM cours
WHERE niveau = 'avance' AND actif = 1;

-- E2. UNION : Emails des admins et des professeurs (liste unique)
SELECT email, 'Administrateur' AS type_compte
FROM utilisateurs
WHERE role = 'admin'

UNION

SELECT email, 'Professeur' AS type_compte
FROM utilisateurs
WHERE role = 'professeur';

-- ============================================================
-- SECTION F : REQUÊTES DE MODIFICATION (INSERT, UPDATE, DELETE)
-- ============================================================

-- F1. INSERT : Ajouter un nouveau cours
INSERT INTO cours (titre, description, niveau, duree_heures, nb_lecons, image, actif, id_categorie, id_professeur)
VALUES ('Python pour la Data Science',
        'Introduction à Python, NumPy, Pandas et Matplotlib pour l\'analyse de données.',
        'intermediaire', 25.0, 12, 'cours1.jpg', 1, 1, 2);

-- F2. INSERT : Inscrire un étudiant à un cours
-- (la contrainte UNIQUE KEY uq_inscription empêche les doublons)
INSERT INTO inscriptions (id_etudiant, id_cours, statut, progression)
VALUES (4, 1, 'en_cours', 0);

-- F3. UPDATE : Mettre à jour la progression d'un étudiant
-- Requête utilisée dans InscriptionModel::mettreAJourProgression()
UPDATE inscriptions
SET progression = 80
WHERE id_etudiant = 3
  AND id_cours    = 1;

-- F4. UPDATE : Marquer un cours comme terminé
UPDATE inscriptions
SET statut   = 'termine',
    date_fin = NOW(),
    progression = 100
WHERE id_etudiant = 3
  AND id_cours    = 2;

-- F5. UPDATE : Désactiver un cours (soft delete)
UPDATE cours
SET actif = 0
WHERE id_cours = 5;   -- cours Python ajouté en F1

-- F6. UPDATE : Modifier l'email d'un utilisateur
UPDATE utilisateurs
SET email = 'nouveau.email@esen.tn'
WHERE id_user = 5;

-- F7. DELETE : Supprimer une inscription abandonnée
DELETE FROM inscriptions
WHERE id_etudiant = 4
  AND id_cours    = 1
  AND statut      = 'abandonne';

-- F8. DELETE : Supprimer les leçons d'un cours supprimé
-- (normalement géré par ON DELETE CASCADE, illustré ici manuellement)
DELETE FROM lecons
WHERE id_cours = 5;   -- cours Python désactivé

-- F9. Annuler les ajouts de la section F (nettoyage)
DELETE FROM cours        WHERE titre = 'Python pour la Data Science';
UPDATE inscriptions SET progression = 65
  WHERE id_etudiant = 3 AND id_cours = 1;
UPDATE utilisateurs SET email = 'mohamedaziz.gabsi@esen.tn' WHERE id_user = 5;

-- ============================================================
-- SECTION G : REQUÊTES STATISTIQUES (dashboard admin)
-- ============================================================

-- G1. Tableau de bord global
SELECT
    (SELECT COUNT(*) FROM utilisateurs WHERE role='etudiant' AND actif=1) AS total_etudiants,
    (SELECT COUNT(*) FROM cours WHERE actif=1)                             AS total_cours,
    (SELECT COUNT(*) FROM inscriptions)                                    AS total_inscriptions,
    (SELECT COUNT(*) FROM inscriptions WHERE statut='termine')             AS cours_termines;

-- G2. Rapport des inscriptions par cours
SELECT c.titre,
       cat.nom_categorie,
       COUNT(i.id_inscription)           AS nb_inscrits,
       SUM(i.statut = 'termine')         AS nb_termines,
       SUM(i.statut = 'en_cours')        AS nb_en_cours,
       ROUND(AVG(i.progression), 1)      AS progression_moy
FROM cours c
INNER JOIN categories   cat ON c.id_categorie = cat.id_categorie
LEFT  JOIN inscriptions i   ON c.id_cours     = i.id_cours
GROUP BY c.id_cours, c.titre, cat.nom_categorie
ORDER BY nb_inscrits DESC;

-- G3. Rapport mensuel des inscriptions
SELECT DATE_FORMAT(date_inscription, '%Y-%m') AS mois,
       COUNT(*)                               AS nb_inscriptions
FROM inscriptions
GROUP BY mois
ORDER BY mois;
