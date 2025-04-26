# Mon LMS - Plateforme d'apprentissage en ligne

Bienvenue sur **Mon LMS**, une application de gestion de cours et d'apprentissage en ligne.

## 🚀 Technologies utilisées

- Laravel 11
- Inertia.js
- Vue.js 3
- Jetstream
- Tailwind CSS
- SweetAlert2
- Toastify.js
- MySQL
- PHP 8.3

## 📚 Fonctionnalités principales

- Gestion des cours
- Gestion des leçons
- Inscription et suivi des utilisateurs
- Progression des leçons
- Passage et résultats de quiz
- Tableau de bord pour les administrateurs et étudiants
- Paramètres du site (nom du site, logo, nombre d'éléments par page)

> 🔥 Le projet est déjà **fonctionnel**, mais il n'est **pas encore totalement parfait**.  
> De futures versions plus complètes sont prévues pour améliorer l'expérience utilisateur.

## ⚙️ Installation locale

1. Cloner le projet :
   ```bash
   git clone https://github.com/stevarison6/lms-laravel.git

2.Se déplacer dans le dossier :
cd lms-laravel

3.Installer les dépendances PHP :
composer install

4.Installer les dépendances Node.js :
npm install && npm run build

5.Copier le fichier .env :
cp .env.example .env

6.Générer la clé de l'application :
php artisan key:generate

7.Configurer la base de données dans .env.

8.Exécuter les migrations et seeders :
php artisan migrate --seed

9.Lancer le serveur local :
php artisan serve

🎯 Notes importantes
L'accès aux pages spéciales administrateur est protégé par un middleware "admin".

Les utilisateurs simples ne peuvent voir que leurs cours inscrits.

🧑‍💻 Auteur
Arison NOMENJANAHARY
stevarison6
