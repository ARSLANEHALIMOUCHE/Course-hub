# CourseHub

CourseHub est une démonstration pédagogique indépendante. Les comptes, matières et contenus fournis sont fictifs ; aucun élément d’un projet antérieur n’est utilisé.

## Prérequis

- PHP 8.2 ou ultérieur et les extensions Laravel usuelles (`pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, `ctype`, `curl`).
- Composer 2, Node.js 20+ avec npm, et MySQL 8+.

## Installation

À la racine du projet, dans PowerShell ou un terminal :

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Créez une base de données MySQL nommée `coursehub`, puis adaptez `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD` dans `.env`.

```powershell
npm install
php artisan migrate --seed
npm run build
php artisan serve
```

Ouvrez http://127.0.0.1:8000. Pendant le développement, `npm run dev` surveille les fichiers front-end dans un second terminal. Les pièces jointes sont stockées dans le disque privé local de Laravel ; les téléchargements passent par une route authentifiée et contrôlée selon le rôle.

## Comptes de démonstration

Le seeder crée un administrateur, deux professeurs, cinq étudiants, quatre matières et dix cours (dont un brouillon). Tous les comptes de démonstration ont le mot de passe `CourseHub2026!`.

| Rôle | E-mail |
|---|---|
| Administrateur | `admin@coursehub.test` |
| Professeur | `professeur@coursehub.test` |
| Étudiant | `etudiant@coursehub.test` |

Les autres comptes d’exemple sont `ines.laurent@coursehub.test`, `noah.robert@coursehub.test`, `jade.richard@coursehub.test`, `adam.durand@coursehub.test` et `emma.dubois@coursehub.test`.

## Fonctionnalités

- Connexion, déconnexion, inscription étudiante et contrôle des comptes désactivés.
- Espaces distincts pour les administrateurs, professeurs et étudiants ; les routes vérifient l’authentification, l’état actif et le rôle.
- Administration des utilisateurs, des rôles et des matières. La suppression d’une matière associée à un cours est refusée.
- Gestion des cours par leur seul professeur propriétaire, brouillons/publication et pièces jointes PDF (10 Mo maximum par fichier).
- Recherche et filtre par matière/statut, résultats paginés (10 utilisateurs/cours professeur, 12 cours étudiants).
- Les étudiants ne voient et ne téléchargent que les cours publiés. Les fichiers sont servis depuis le stockage privé.
- Interface responsive Blade, Tailwind CSS et JavaScript, avec formulaires labellisés, retours de validation et confirmations de suppression.

L’authentification s’appuie sur les sessions et le hachage du mot de passe fournis par Laravel. Laravel Breeze est déclaré dans les dépendances de développement ; l’interface utilise des vues Blade maison adaptées aux trois espaces.
