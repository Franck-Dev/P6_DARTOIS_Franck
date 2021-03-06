[![Codacy Badge](https://app.codacy.com/project/badge/Grade/d66b769aa28846f1bc05ffe0971efde2)](https://www.codacy.com/gh/Franck-Dev/P6_DARTOIS_Franck/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Franck-Dev/P6_DARTOIS_Franck&amp;utm_campaign=Badge_Grade)
# P6_DARTOIS_Franck

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/4945f9c89b554816a4b29603eceaf0e6)](https://app.codacy.com/gh/Franck-Dev/P6_DARTOIS_Franck?utm_source=github.com&utm_medium=referral&utm_content=Franck-Dev/P6_DARTOIS_Franck&utm_campaign=Badge_Grade_Settings)

Création d'un site communautaire de partage de figures de snowboard via le framework Symfony.

## Environnement utilisé durant le développement
* Symfony 4.4.1
* Composer 1.11.99
* Bootstrap 4.1.3
* jQuery 3.3.1
* Xampp 5.6.24
    * Apache 2.4.33
    * PHP 7.2.7
    * MariaDB 4.8.2

## Installation
1. Clonez ou téléchargez le repository GitHub sur le serveur :
```
    git clone https://github.com/Franck-Dev/P6_DARTOIS_Franck.git
```
2. Configurez vos variables d'environnement tel que la connexion à la base de données ou votre serveur SMTP ou adresse mail dans le fichier `.env.local` qui devra être crée à la racine du projet en réalisant une copie du fichier `.env`.

3. Téléchargez et installez les dépendances du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
```
    php bin/console doctrine:database:create
```
5. Créez les différentes tables de la base de données en appliquant les migrations :
```
    php bin/console doctrine:migrations:migrate
```
6. (Optionnel) Installer les fixtures pour avoir une démo de données fictives :
```
    php bin/console doctrine:fixtures:load
```

