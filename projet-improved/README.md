# ESEN E-Learn – Dossier Base de Données

## Ordre d'exécution dans phpMyAdmin

1. `01_creation_utilisateurs_privileges.sql` → Créer les utilisateurs MySQL et les privilèges
2. `02_creation_tables_donnees.sql`          → Créer les tables et insérer les données
3. `03_requetes_interrogation_modification.sql` → Toutes les requêtes (SELECT, JOIN, HAVING, UNION, INSERT, UPDATE, DELETE)
4. `04_procedures_fonctions_triggers.sql`    → Procédures, fonctions, curseurs, triggers + tests
5. `05_annexe_inventaire_objets.sql`         → Inventaire complet des objets BD

## Rapport
- `rapport/rapport_esen_elearn.docx` → Rapport Word complet

## Comment importer dans phpMyAdmin
1. Ouvrir phpMyAdmin
2. Cliquer sur "Importer"
3. Sélectionner les fichiers SQL dans l'ordre ci-dessus
4. Cliquer sur "Exécuter"

**Note :** Le script 01 crée la base `esen_elearn`. Les scripts suivants utilisent `USE esen_elearn;` automatiquement.
