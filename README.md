# Touiteur — SAE Développement Web S3

Application web de microblogging développée en PHP dans le cadre de la SAE du semestre 3 du BUT Informatique.

## Équipe

- **Zayd Benzakour** — Navigation et affichage des touites
- **Aminou Daricaoui** — Publication et gestion des données
- **Vincent Jaquet** — Fonctionnalités sociales et mur personnalisé

## Présentation

Touiteur permet de publier et consulter des messages courts appelés _touites_.

L'application propose un système de hashtags, de suivi d'utilisateurs et un mur personnalisé regroupant les publications des utilisateurs suivis et celles contenant les hashtags auxquels l'utilisateur est abonné.

## Fonctionnalités

| N°  | Fonctionnalité                                          |
| --- | ------------------------------------------------------- |
| 1   | Affichage des touites par ordre chronologique inverse   |
| 2   | Consultation du détail d'un touite                      |
| 4   | Consultation des touites d'un auteur                    |
| 5   | Consultation des touites associés à un hashtag          |
| 6   | Publication de touites (235 caractères maximum)         |
| 8   | Extraction et enregistrement des hashtags               |
| 9   | Mur personnalisé                                        |
| 10  | Suivi d'utilisateurs                                    |
| 11  | Abonnement aux hashtags                                 |
| 14  | Classement des utilisateurs selon leur nombre d'abonnés |

## Technologies utilisées

- PHP
- MariaDB
- PDO
- Composer avec autoload PSR-4
- HTML, CSS et JavaScript
- Bootstrap
- Docker pour l'environnement local

## Architecture

Le projet utilise une organisation en plusieurs composants :

- `public/` : point d'entrée et ressources accessibles depuis le navigateur.
- `src/Action/` : traitement des fonctionnalités.
- `src/Repository/` : accès aux données et requêtes SQL.
- `src/Database/` : connexion à la base de données et schéma SQL.
- `config/` : configuration de l'application.

La navigation est gérée par un dispatcher et une fabrique d'actions (`ActionFactory`).

## Installation

### Prérequis

- PHP avec les extensions nécessaires à MariaDB et au traitement des chaînes de caractères
- Composer
- MariaDB
- Un environnement capable d'exécuter l'application PHP

### Récupérer le projet

```bash
git clone https://github.com/Zayd54500/touiteur-sae.git
cd touiteur-sae
```

### Installer les dépendances

```bash
composer install
```

### Configurer la base de données

Le schéma SQL est disponible dans :

```text
src/Database/schema.sql
```

Créer une base MariaDB puis y importer ce schéma.

Configurer ensuite les paramètres de connexion attendus par `src/Database/DatabaseConnection.php`.

Les identifiants et mots de passe de la base de données doivent rester dans la configuration locale et ne doivent pas être publiés dans le dépôt.

### Lancer l'application

Une fois MariaDB configuré et accessible, exécuter depuis la racine du projet :

```bash
php -S localhost:8000 -t public
```

Ouvrir ensuite :

http://localhost:8000

## Tester les fonctionnalités

L'application permet de sélectionner un utilisateur de test directement depuis l'interface.

Exemples d'utilisateurs : Alice, Vincent et Zayd.

Scénario de test :

1. Sélectionner un utilisateur.
2. Consulter le fil d'actualité.
3. Ouvrir le détail d'un touite.
4. Consulter les publications d'un auteur ou d'un hashtag.
5. Publier un touite avec un hashtag.
6. Suivre un autre utilisateur.
7. S'abonner à un hashtag.
8. Consulter le mur personnalisé.
9. Afficher le classement des utilisateurs.

## Sécurité

L'application intègre plusieurs protections :

- Requêtes préparées PDO pour les accès SQL.
- Validation des données saisies.
- Échappement HTML des contenus affichés.
- Jetons CSRF pour les formulaires POST.

## Dépôt GitHub

https://github.com/Zayd54500/touiteur-sae
