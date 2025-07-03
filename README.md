# 🎓 Sessions – Gestion de sessions de formation

**Sessions** est une application web développée avec **Symfony 7**, destinée à faciliter la gestion complète des **formations** en entreprise ou organisme de formation.

Elle permet de gérer les **catégories de formation**, les **formations**, les **sessions**, les **formateurs** et les **stagiaires**, de manière simple et efficace.

---

## 🧩 Fonctionnalités principales

- 🔹 Gestion des **catégories de formation** (ex : Informatique, Bureautique, Langues, etc.)
- 🔹 Création et gestion des **formations** associées à chaque catégorie
- 🔹 Planification de plusieurs **sessions** par formation
- 🔹 Attribution de **formateurs** à chaque session
- 🔹 Inscription de **stagiaires** à une ou plusieurs sessions
- 🔹 Vue calendrier des sessions à venir *(optionnel)*
- 🔹 Interface d’administration pour toutes les entités

---

## 🛠️ Technologies utilisées

- ⚙️ **Framework** : Symfony 7 (PHP)
- 🗃️ **Base de données** : MySQL
- 💅 **Frontend** : Twig, Bootstrap 5
- 🔐 **Sécurité** : Authentification via Symfony Security (optionnel)
- 🔄 **ORM** : Doctrine

---

## 🖥️ Aperçu de la structure des entités

```plaintext
Catégorie
└──> Formation
     └──> Session
          ├──> Formateur(s)
          └──> Stagiaire(s)
```
---

## 🚀 Installation du projet

### 1. Cloner le dépôt
```
git clone https://github.com/tonutilisateur/sessions.git
cd sessions
```
### 2. Installer les dépendances PHP
```
composer install
```
### 3. Configurez l'environnement
Copiez le fichier .env si besoin
```
cp .env .env.local
```
Modifiez les paramètres de connexion à la base de données dans .env.local :
```
DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/nom_de_base"
```
### 4. Créer la base de données
```
php bin/console doctrine:database:create
```
### 5. Exécuter les migrations
```
php bin/console doctrine:migrations:migrate
```
### 6. Lancer le serveur local
```
symfony server:start
```
L'application sera accessible à l’adresse :
📍 http://localhost:8000

## 👤 Auteur

Développé par Ivan Veltz

📧 Contact : ivan.veltz@live.fr

🔗 LinkedIn : linkedin.com/in/ivan-veltz-5214ba142/
