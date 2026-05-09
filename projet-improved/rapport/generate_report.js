const {
  Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell,
  HeadingLevel, AlignmentType, BorderStyle, WidthType, ShadingType,
  PageNumber, NumberFormat, Header, Footer, LevelFormat, PageBreak
} = require('docx');
const fs = require('fs');

const BLUE  = '1F4E79';
const LBLUE = 'D6E4F0';
const GRAY  = 'F2F2F2';
const WHITE = 'FFFFFF';
const BLACK = '000000';

const border = { style: BorderStyle.SINGLE, size: 1, color: 'CCCCCC' };
const borders = { top: border, bottom: border, left: border, right: border };

function h1(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_1,
    spacing: { before: 400, after: 200 },
    border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: '1F4E79', space: 1 } },
    children: [new TextRun({ text, bold: true, size: 36, color: BLUE, font: 'Arial' })]
  });
}

function h2(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_2,
    spacing: { before: 300, after: 150 },
    children: [new TextRun({ text, bold: true, size: 28, color: BLUE, font: 'Arial' })]
  });
}

function h3(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_3,
    spacing: { before: 200, after: 100 },
    children: [new TextRun({ text, bold: true, size: 24, color: '2E74B5', font: 'Arial' })]
  });
}

function p(text, opts = {}) {
  return new Paragraph({
    spacing: { before: 80, after: 80 },
    children: [new TextRun({ text, size: 22, font: 'Arial', ...opts })]
  });
}

function code(text) {
  return new Paragraph({
    spacing: { before: 60, after: 60 },
    indent: { left: 720 },
    shading: { type: ShadingType.CLEAR, fill: 'F0F4F8' },
    children: [new TextRun({ text, size: 18, font: 'Courier New', color: '1A1A2E' })]
  });
}

function bullet(text) {
  return new Paragraph({
    spacing: { before: 60, after: 60 },
    indent: { left: 720, hanging: 360 },
    children: [
      new TextRun({ text: '\u2022  ', size: 22, font: 'Arial', color: '1F4E79' }),
      new TextRun({ text, size: 22, font: 'Arial' })
    ]
  });
}

function blankLine() {
  return new Paragraph({ children: [new TextRun('')] });
}

function headerRow(cells, widths) {
  return new TableRow({
    tableHeader: true,
    children: cells.map((c, i) => new TableCell({
      borders,
      width: { size: widths[i], type: WidthType.DXA },
      shading: { type: ShadingType.CLEAR, fill: BLUE },
      margins: { top: 80, bottom: 80, left: 120, right: 120 },
      children: [new Paragraph({ children: [new TextRun({ text: c, bold: true, size: 20, color: WHITE, font: 'Arial' })] })]
    }))
  });
}

function dataRow(cells, widths, shade = false) {
  return new TableRow({
    children: cells.map((c, i) => new TableCell({
      borders,
      width: { size: widths[i], type: WidthType.DXA },
      shading: { type: ShadingType.CLEAR, fill: shade ? GRAY : WHITE },
      margins: { top: 80, bottom: 80, left: 120, right: 120 },
      children: [new Paragraph({ children: [new TextRun({ text: c, size: 19, font: 'Arial' })] })]
    }))
  });
}

function simpleTable(headers, rows, widths) {
  return new Table({
    width: { size: widths.reduce((a, b) => a + b, 0), type: WidthType.DXA },
    columnWidths: widths,
    rows: [
      headerRow(headers, widths),
      ...rows.map((r, i) => dataRow(r, widths, i % 2 === 0))
    ]
  });
}

function pageBreak() {
  return new Paragraph({ children: [new PageBreak()] });
}

// =====================================================================
// DOCUMENT
// =====================================================================

const doc = new Document({
  numbering: {
    config: [{
      reference: 'bullets',
      levels: [{ level: 0, format: LevelFormat.BULLET, text: '\u2022', alignment: AlignmentType.LEFT,
        style: { paragraph: { indent: { left: 720, hanging: 360 } } } }]
    }]
  },
  styles: {
    default: { document: { run: { font: 'Arial', size: 22 } } },
    paragraphStyles: [
      { id: 'Heading1', name: 'Heading 1', basedOn: 'Normal', next: 'Normal', quickFormat: true,
        run: { size: 36, bold: true, font: 'Arial', color: BLUE },
        paragraph: { spacing: { before: 400, after: 200 }, outlineLevel: 0 } },
      { id: 'Heading2', name: 'Heading 2', basedOn: 'Normal', next: 'Normal', quickFormat: true,
        run: { size: 28, bold: true, font: 'Arial', color: BLUE },
        paragraph: { spacing: { before: 300, after: 150 }, outlineLevel: 1 } },
      { id: 'Heading3', name: 'Heading 3', basedOn: 'Normal', next: 'Normal', quickFormat: true,
        run: { size: 24, bold: true, font: 'Arial', color: '2E74B5' },
        paragraph: { spacing: { before: 200, after: 100 }, outlineLevel: 2 } },
    ]
  },
  sections: [{
    properties: {
      page: {
        size: { width: 11906, height: 16838 },
        margin: { top: 1440, right: 1080, bottom: 1440, left: 1440 }
      }
    },
    headers: {
      default: new Header({
        children: [new Paragraph({
          border: { bottom: { style: BorderStyle.SINGLE, size: 4, color: '1F4E79', space: 1 } },
          children: [
            new TextRun({ text: 'ESEN E-Learn  |  Rapport de Base de Données', size: 18, color: '555555', font: 'Arial' }),
            new TextRun({ text: '        ', size: 18 }),
          ]
        })]
      })
    },
    footers: {
      default: new Footer({
        children: [new Paragraph({
          alignment: AlignmentType.CENTER,
          border: { top: { style: BorderStyle.SINGLE, size: 4, color: 'CCCCCC', space: 1 } },
          children: [
            new TextRun({ text: 'ESEN  –  2026  –  Page ', size: 18, color: '777777', font: 'Arial' }),
            new TextRun({ children: [PageNumber.CURRENT], size: 18, font: 'Arial', color: '777777' }),
            new TextRun({ text: ' / ', size: 18, color: '777777', font: 'Arial' }),
            new TextRun({ children: [PageNumber.TOTAL_PAGES], size: 18, font: 'Arial', color: '777777' }),
          ]
        })]
      })
    },
    children: [
      // ===================== PAGE DE COUVERTURE =====================
      blankLine(), blankLine(), blankLine(),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 800, after: 200 },
        children: [new TextRun({ text: 'ÉCOLE SUPÉRIEURE D\'ÉCONOMIE NUMÉRIQUE', size: 28, bold: true, color: '555555', font: 'Arial', allCaps: true })]
      }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 100, after: 600 },
        children: [new TextRun({ text: 'ESEN  –  Tunis', size: 24, color: '888888', font: 'Arial' })]
      }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 200, after: 200 },
        shading: { type: ShadingType.CLEAR, fill: BLUE },
        children: [new TextRun({ text: '  ESEN E-LEARN  ', size: 56, bold: true, color: WHITE, font: 'Arial' })]
      }),
      new Paragraph({
        alignment: AlignmentType.CENTER,
        spacing: { before: 200, after: 400 },
        children: [new TextRun({ text: 'Rapport de Base de Données – Projet Final', size: 32, bold: true, color: BLUE, font: 'Arial' })]
      }),
      blankLine(),
      simpleTable(
        ['Élément', 'Détail'],
        [
          ['Projet',    'Plateforme e-learning ESEN'],
          ['SGBD',      'MySQL 8 / MariaDB – compatible phpMyAdmin'],
          ['Éditeur',   'phpMyAdmin'],
          ['Langage',   'SQL + Procédures stockées MySQL (PL-SQL)'],
          ['Année',     '2026'],
        ],
        [3500, 5500]
      ),
      blankLine(), blankLine(), blankLine(),

      // ===================== 1. INTRODUCTION ========================
      pageBreak(),
      h1('1. Introduction'),
      p('Ce rapport présente la conception et l\'implémentation complète de la base de données de la plateforme ESEN E-Learn. Il couvre l\'ensemble des éléments demandés dans le cahier des charges : création des utilisateurs, tables, requêtes, procédures stockées, fonctions, curseurs et triggers.'),
      blankLine(),
      h2('1.1 Contexte du projet'),
      p('ESEN E-Learn est une plateforme de formation en ligne destinée aux étudiants de l\'École Supérieure d\'Économie Numérique. Elle permet la gestion des cours, des inscriptions, des leçons et des quiz. L\'application est développée en PHP/MVC et s\'appuie sur une base de données MySQL.'),
      blankLine(),
      h2('1.2 Architecture de la base de données'),
      p('La base de données esen_elearn contient 7 tables principales reliées par des clés étrangères (FK), 4 procédures stockées, 4 fonctions, 4 triggers et 4 utilisateurs avec des niveaux de privilèges différents.'),
      blankLine(),

      // ===================== 2. SCHÉMA RELATIONNEL ==================
      pageBreak(),
      h1('2. Schéma relationnel de la base de données'),
      h2('2.1 Tables et structure'),
      p('Le schéma relationnel comprend les 7 tables suivantes :'),
      blankLine(),
      simpleTable(
        ['Table', 'Rôle', 'Clé primaire'],
        [
          ['categories',     'Catégories thématiques des cours',            'id_categorie'],
          ['utilisateurs',   'Tous les acteurs (admin, prof, étudiant)',     'id_user'],
          ['cours',          'Catalogue des cours disponibles',              'id_cours'],
          ['lecons',         'Leçons composant chaque cours',                'id_lecon'],
          ['inscriptions',   'Inscription et suivi de progression',         'id_inscription'],
          ['quiz',           'Quiz associés aux cours',                      'id_quiz'],
          ['quiz_questions', 'Questions à choix multiple des quiz',          'id_question'],
        ],
        [2500, 4000, 2500]
      ),
      blankLine(),
      h2('2.2 Clés étrangères (contraintes d\'intégrité référentielle)'),
      simpleTable(
        ['Table', 'Colonne FK', 'Table référencée', 'Comportement ON DELETE'],
        [
          ['cours',          'id_categorie',  'categories',   'RESTRICT'],
          ['cours',          'id_professeur', 'utilisateurs', 'RESTRICT'],
          ['lecons',         'id_cours',      'cours',        'CASCADE'],
          ['inscriptions',   'id_etudiant',   'utilisateurs', 'RESTRICT'],
          ['inscriptions',   'id_cours',      'cours',        'RESTRICT'],
          ['quiz',           'id_cours',      'cours',        'RESTRICT'],
          ['quiz_questions', 'id_quiz',       'quiz',         'CASCADE'],
        ],
        [2000, 2200, 2200, 2600]
      ),
      blankLine(),

      // ===================== 3. UTILISATEURS & PRIVILÈGES ===========
      pageBreak(),
      h1('3. Création des utilisateurs et gestion des privilèges'),
      h2('3.1 Utilisateurs MySQL créés'),
      p('Quatre utilisateurs MySQL ont été créés avec des niveaux de droits différents pour respecter le principe du moindre privilège :'),
      blankLine(),
      simpleTable(
        ['Utilisateur MySQL', 'Rôle métier', 'Droits accordés'],
        [
          ['admin_esen',    'Administrateur système', 'ALL PRIVILEGES sur toute la base'],
          ['prof_esen',     'Professeur',             'SELECT, INSERT, UPDATE, DELETE sur cours, lecons, quiz'],
          ['etudiant_esen', 'Étudiant',               'SELECT sur cours/catégories, INSERT/UPDATE sur inscriptions'],
          ['lecture_esen',  'Reporting / audit',      'SELECT uniquement sur toutes les tables'],
        ],
        [2500, 2000, 4500]
      ),
      blankLine(),
      h2('3.2 Scripts de création (extrait commenté)'),
      code('-- Créer un utilisateur professeur'),
      code('CREATE USER \'prof_esen\'@\'localhost\''),
      code('  IDENTIFIED BY \'Prof@ESEN2026!\';'),
      blankLine(),
      code('-- Accorder les droits sur les tables pédagogiques'),
      code('GRANT SELECT, INSERT, UPDATE, DELETE'),
      code('  ON esen_elearn.cours'),
      code('  TO \'prof_esen\'@\'localhost\';'),
      blankLine(),
      code('-- Appliquer immédiatement'),
      code('FLUSH PRIVILEGES;'),
      blankLine(),

      // ===================== 4. REQUÊTES ============================
      pageBreak(),
      h1('4. Requêtes d\'interrogation et de modification'),
      h2('4.1 Requêtes SELECT avec jointures'),
      p('La requête suivante est utilisée par l\'application PHP (CoursModel::findAllComplets()) pour afficher le catalogue des cours avec les informations du professeur et le nombre d\'inscrits :'),
      blankLine(),
      code('-- Cours actifs avec catégorie, professeur et nb inscrits'),
      code('SELECT c.titre, c.niveau, c.duree_heures,'),
      code('       cat.nom_categorie, cat.couleur,'),
      code('       CONCAT(u.prenom, \' \', u.nom) AS nom_professeur,'),
      code('       COUNT(DISTINCT i.id_inscription) AS nb_inscrits'),
      code('FROM cours c'),
      code('INNER JOIN categories   cat ON c.id_categorie  = cat.id_categorie'),
      code('INNER JOIN utilisateurs u   ON c.id_professeur = u.id_user'),
      code('LEFT  JOIN inscriptions i   ON c.id_cours      = i.id_cours'),
      code('WHERE c.actif = 1'),
      code('GROUP BY c.id_cours, cat.nom_categorie, u.prenom, u.nom'),
      code('ORDER BY c.date_creation DESC;'),
      blankLine(),
      h2('4.2 Requêtes avec HAVING et sous-requêtes'),
      p('Requête HAVING – Catégories ayant plus d\'un cours :'),
      code('SELECT cat.nom_categorie, COUNT(c.id_cours) AS nombre_cours'),
      code('FROM categories cat INNER JOIN cours c ON cat.id_categorie = c.id_categorie'),
      code('GROUP BY cat.id_categorie HAVING COUNT(c.id_cours) >= 1;'),
      blankLine(),
      p('Sous-requête – Cours dont la durée dépasse la moyenne :'),
      code('SELECT titre, duree_heures FROM cours'),
      code('WHERE duree_heures > (SELECT AVG(duree_heures) FROM cours WHERE actif = 1);'),
      blankLine(),
      p('Sous-requête EXISTS – Cours ayant au moins un quiz actif :'),
      code('SELECT c.titre FROM cours c WHERE EXISTS ('),
      code('    SELECT 1 FROM quiz q'),
      code('    WHERE q.id_cours = c.id_cours AND q.actif = 1);'),
      blankLine(),
      h2('4.3 Requêtes de modification (INSERT, UPDATE, DELETE)'),
      p('Inscription d\'un étudiant :'),
      code('INSERT INTO inscriptions (id_etudiant, id_cours, statut, progression)'),
      code('VALUES (3, 4, \'en_cours\', 0);'),
      blankLine(),
      p('Mise à jour de la progression (InscriptionModel::mettreAJourProgression()) :'),
      code('UPDATE inscriptions SET progression = 80'),
      code('WHERE id_etudiant = 3 AND id_cours = 1;'),
      blankLine(),
      p('Suppression d\'une inscription abandonnée :'),
      code('DELETE FROM inscriptions'),
      code('WHERE id_etudiant = 4 AND id_cours = 1 AND statut = \'abandonne\';'),
      blankLine(),

      // ===================== 5. PROCÉDURES & FONCTIONS ==============
      pageBreak(),
      h1('5. Procédures stockées et fonctions'),
      h2('5.1 Procédures stockées'),
      simpleTable(
        ['Procédure', 'Paramètres', 'Description'],
        [
          ['inscrire_etudiant',          'IN: id_etudiant, id_cours',           'Inscrit un étudiant en vérifiant les doublons et l\'état du cours'],
          ['mettre_a_jour_progression',  'IN: id_etudiant, id_cours, prog',     'Met à jour la progression, marque automatiquement "termine" à 100%'],
          ['rapport_inscriptions_cours', 'IN: id_cours',                        'Utilise un curseur EXPLICITE pour parcourir les inscriptions d\'un cours'],
          ['statistiques_globales',      'aucun',                               'Calcule et affiche les KPIs de la plateforme (curseurs implicites)'],
        ],
        [2800, 2800, 3400]
      ),
      blankLine(),
      h3('Exemple : Procédure inscrire_etudiant (curseur implicite)'),
      code('CREATE PROCEDURE inscrire_etudiant(IN p_id_etudiant INT, IN p_id_cours INT)'),
      code('BEGIN'),
      code('    DECLARE v_existe INT DEFAULT 0;'),
      code('    -- Curseur implicite : SELECT INTO'),
      code('    SELECT COUNT(*) INTO v_existe'),
      code('    FROM inscriptions'),
      code('    WHERE id_etudiant = p_id_etudiant AND id_cours = p_id_cours;'),
      blankLine(),
      code('    IF v_existe > 0 THEN'),
      code('        SELECT \'ERREUR : Étudiant déjà inscrit\' AS message;'),
      code('    ELSE'),
      code('        INSERT INTO inscriptions (id_etudiant, id_cours, statut, progression)'),
      code('        VALUES (p_id_etudiant, p_id_cours, \'en_cours\', 0);'),
      code('        SELECT CONCAT(\'OK : id_inscription = \', LAST_INSERT_ID()) AS message;'),
      code('    END IF;'),
      code('END;'),
      blankLine(),
      h2('5.2 Fonctions stockées'),
      simpleTable(
        ['Fonction', 'Paramètre(s)', 'Retour', 'Description'],
        [
          ['get_progression_etudiant', 'id_etudiant, id_cours', 'INT',          'Retourne la progression (0 si non inscrit)'],
          ['est_inscrit',              'id_etudiant, id_cours', 'TINYINT(1)',   '1 si inscrit, 0 sinon'],
          ['calculer_note_quiz',       'id_quiz',               'DECIMAL(5,2)', 'Somme des points de toutes les questions'],
          ['nb_cours_etudiant',        'id_etudiant',           'INT',          'Nombre de cours auxquels l\'étudiant est inscrit'],
        ],
        [2500, 2200, 1600, 2700]
      ),
      blankLine(),

      // ===================== 6. CURSEURS ============================
      pageBreak(),
      h1('6. Curseurs implicites et explicites'),
      h2('6.1 Curseurs implicites'),
      p('En MySQL (comme en PL/SQL Oracle), un curseur implicite est utilisé automatiquement lorsqu\'on effectue une requête SELECT INTO. Le SGBD gère l\'ouverture, la lecture et la fermeture du curseur.'),
      blankLine(),
      p('Exemple d\'utilisation dans la procédure statistiques_globales() :'),
      code('-- Curseur implicite : le SGBD gère l\'accès automatiquement'),
      code('SELECT COUNT(*) INTO v_total_etudiants'),
      code('FROM utilisateurs WHERE role=\'etudiant\' AND actif=1;'),
      blankLine(),
      h2('6.2 Curseur explicite'),
      p('Un curseur explicite est déclaré manuellement et nécessite les étapes DECLARE → OPEN → FETCH → CLOSE. Il est utilisé dans la procédure rapport_inscriptions_cours() pour parcourir ligne par ligne les inscriptions d\'un cours.'),
      blankLine(),
      code('-- Déclaration du curseur explicite'),
      code('DECLARE cur_inscriptions CURSOR FOR'),
      code('    SELECT u.nom, u.prenom, i.statut, i.progression, i.date_inscription'),
      code('    FROM inscriptions i INNER JOIN utilisateurs u ON i.id_etudiant = u.id_user'),
      code('    WHERE i.id_cours = p_id_cours;'),
      blankLine(),
      code('-- Handler pour fin de résultats'),
      code('DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_termine = 1;'),
      blankLine(),
      code('OPEN cur_inscriptions;           -- Ouverture'),
      code('boucle: LOOP'),
      code('    FETCH cur_inscriptions       -- Lecture ligne par ligne'),
      code('    INTO v_nom, v_prenom, v_statut, v_progression, v_date;'),
      code('    IF v_termine = 1 THEN LEAVE boucle; END IF;'),
      code('    -- Traitement de chaque ligne...'),
      code('END LOOP;'),
      code('CLOSE cur_inscriptions;          -- Fermeture'),
      blankLine(),

      // ===================== 7. TRIGGERS ============================
      pageBreak(),
      h1('7. Triggers (Déclencheurs)'),
      p('Les triggers permettent d\'automatiser des actions en réaction à des événements (INSERT, UPDATE, DELETE) sur les tables.'),
      blankLine(),
      simpleTable(
        ['Trigger', 'Événement', 'Table', 'But'],
        [
          ['trg_nb_lecons_insert',      'AFTER INSERT',  'lecons',       'Incrémente nb_lecons dans cours à chaque ajout de leçon'],
          ['trg_nb_lecons_delete',      'AFTER DELETE',  'lecons',       'Décrémente nb_lecons dans cours à chaque suppression'],
          ['trg_valider_progression',   'BEFORE UPDATE', 'inscriptions', 'Valide progression (0-100) et bascule le statut automatiquement'],
          ['trg_log_connexion',         'BEFORE UPDATE', 'utilisateurs', 'Empêche l\'enregistrement d\'une date de connexion future'],
        ],
        [2800, 2000, 2000, 2200]
      ),
      blankLine(),
      h2('7.1 Exemple : trigger trg_valider_progression'),
      code('CREATE TRIGGER trg_valider_progression'),
      code('BEFORE UPDATE ON inscriptions'),
      code('FOR EACH ROW'),
      code('BEGIN'),
      code('    -- Corriger les valeurs hors limites'),
      code('    IF NEW.progression < 0   THEN SET NEW.progression = 0;   END IF;'),
      code('    IF NEW.progression > 100 THEN SET NEW.progression = 100; END IF;'),
      blankLine(),
      code('    -- Changer automatiquement le statut'),
      code('    IF NEW.progression = 100 THEN'),
      code('        SET NEW.statut   = \'termine\';'),
      code('        SET NEW.date_fin = NOW();'),
      code('    ELSEIF NEW.progression > 0 THEN'),
      code('        SET NEW.statut = \'en_cours\';'),
      code('    END IF;'),
      code('END;'),
      blankLine(),

      // ===================== 8. ANNEXE ==============================
      pageBreak(),
      h1('8. Annexe – Inventaire complet des objets BD'),
      h2('8.1 Tables'),
      simpleTable(
        ['Table', 'Colonnes principales', 'Nb lignes (données init.)'],
        [
          ['categories',     'id_categorie, nom_categorie, description, couleur, icone',                          '4'],
          ['utilisateurs',   'id_user, nom, prenom, email, mot_de_passe, role, actif, date_inscription',          '5'],
          ['cours',          'id_cours, titre, description, niveau, duree_heures, nb_lecons, image, id_categorie', '4'],
          ['lecons',         'id_lecon, id_cours, titre, contenu, ordre, duree_minutes',                          '14'],
          ['inscriptions',   'id_inscription, id_etudiant, id_cours, statut, progression, date_inscription',      '5'],
          ['quiz',           'id_quiz, titre, description, note_max, actif, id_cours',                            '3'],
          ['quiz_questions', 'id_question, id_quiz, question, reponse_a/b/c/d, bonne_reponse, points',           '30'],
        ],
        [2200, 5000, 1800]
      ),
      blankLine(),
      h2('8.2 Procédures stockées'),
      simpleTable(
        ['Nom', 'Type de curseur utilisé'],
        [
          ['inscrire_etudiant',          'Curseur implicite (SELECT INTO)'],
          ['mettre_a_jour_progression',  'Curseur implicite (SELECT INTO × 2)'],
          ['rapport_inscriptions_cours', 'Curseur EXPLICITE (DECLARE / OPEN / FETCH / CLOSE)'],
          ['statistiques_globales',      'Curseur implicite (SELECT INTO × 6)'],
        ],
        [4000, 5000]
      ),
      blankLine(),
      h2('8.3 Fonctions'),
      simpleTable(
        ['Nom', 'Type retour'],
        [
          ['get_progression_etudiant', 'INT'],
          ['est_inscrit',              'TINYINT(1)'],
          ['calculer_note_quiz',       'DECIMAL(5,2)'],
          ['nb_cours_etudiant',        'INT'],
        ],
        [4500, 4500]
      ),
      blankLine(),
      h2('8.4 Triggers'),
      simpleTable(
        ['Nom', 'Table', 'Événement', 'Moment'],
        [
          ['trg_nb_lecons_insert',    'lecons',       'INSERT', 'AFTER'],
          ['trg_nb_lecons_delete',    'lecons',       'DELETE', 'AFTER'],
          ['trg_valider_progression', 'inscriptions', 'UPDATE', 'BEFORE'],
          ['trg_log_connexion',       'utilisateurs', 'UPDATE', 'BEFORE'],
        ],
        [3000, 2500, 1700, 1800]
      ),
      blankLine(),
      h2('8.5 Utilisateurs MySQL'),
      simpleTable(
        ['Utilisateur', 'Hôte', 'Niveau de privilège'],
        [
          ['admin_esen',    'localhost', 'ALL PRIVILEGES (DBA)'],
          ['prof_esen',     'localhost', 'SELECT/INSERT/UPDATE/DELETE sur cours, lecons, quiz'],
          ['etudiant_esen', 'localhost', 'SELECT cours/lecons, INSERT/UPDATE inscriptions'],
          ['lecture_esen',  'localhost', 'SELECT uniquement (tous les tables)'],
        ],
        [2500, 1800, 4700]
      ),
      blankLine(),
      p('Pour afficher les utilisateurs dans phpMyAdmin : menu "Comptes utilisateurs" ou exécuter :'),
      code('SELECT user, host FROM mysql.user'),
      code('WHERE user IN (\'admin_esen\',\'prof_esen\',\'etudiant_esen\',\'lecture_esen\');'),
      blankLine(),
    ]
  }]
});

Packer.toBuffer(doc).then(buf => {
  fs.writeFileSync('/home/claude/projet_final/rapport/rapport_esen_elearn.docx', buf);
  console.log('Rapport Word généré avec succès.');
});
