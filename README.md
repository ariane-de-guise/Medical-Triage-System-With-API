# Présentation du projet

## Démarrage du projet

### API

```bash
cd <directory>/api
composer install
sail up -d
sail artisan migrate --seed
```

### Interface

```bash
cd ../interface
composer install
sail up -d
sail npm install
sail npm run dev
```

---

## Accès au projet

* **phpMyAdmin / Base de données :** [http://localhost:8081/](http://localhost:8081/)
* **Interface web :** [http://localhost:80/](http://localhost:80/)

---

## Comptes de démonstration

Dans la table `users`, vous pouvez utiliser les comptes suivants pour tester les différents rôles :

* **Administrateur :** utilisateur avec l’ID `1`
* **Infirmier :** utilisateur avec l’ID `3`
* **Médecin :** utilisateur avec l’ID `8`
* **Patient :** utilisateur avec l’ID `11`

---

## Fonctionnalités à tester

### Infirmier / Médecin

* Tester le système de **files d’attente**
* Observer la gestion de la répartition des patients

### Patient

* Consulter le **dossier médical personnel**

### Administrateur

* Gérer librement les données de la base de données via l’interface d’administration

---

## Endpoints administratifs imbriqués

Le rôle administrateur donne accès à plusieurs endpoints spécialisés :

* **3 endpoints principaux** dans la section Admin
* **3 endpoints imbriqués** dans la ressource `comptes`

Ces endpoints imbriqués sont accessibles depuis la vue détaillée d’un compte (`show`).

### Exemple

```text
/admin/comptes/{id}/telephones
```

Permet d’accéder aux téléphones associés au compte sélectionné.
