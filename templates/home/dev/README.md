# Base Blog Project

Projet de base Symfony 6.3 template préconfiguré, avec des entités et des repository de base pour faire un blog.

- php8.2
- Apache
- yarn
- mySql
- Symfony 6.3

Des bundles sont préinstallés également

- EasyAdmin
- ApiPlatform
- RamseyUuid

Des routes Api sont préconfigurées

**<span style="color: red;">Pour utiliser ce projet template, il faut au préalable avoir installé Docker et Docker compose sur sa machine.</span>**

----

## Installation
### Docker :
Pour installer le projet, exécuter la commande `make install` à la racine du dossier. 
Tu auras le temps de faire couler un café ! *(Un bon développeur AIME le café)*

Si on rencontre une erreur au moment de l'installation, consulte [les erreurs communes](#erreurs-communes-lors-de-linstallation)
Sinon contacter le propriétaire du repo

## Comment fonctionne le projet
## Uniquement en .env dev
Ce projet utilise EasyAdmin 4 la route d'accès au login de connexion est [http://localhost:8088/login/login-admin-interface](http://localhost:8088/login/login-admin-interface)

### Pour se connecter:
- <u>Mail</u> : test@technique.com
- <u>Password</u> : p@ssword


Des fixtures sont disponibles

### Erreurs communes lors de l'installation
><span style="background-color:#f55f69; color:black;">Could not create database my-database for connection named default</span></br>
 <span style="background-color:#f55f69; color:black;">An exception occurred while executing a query: SQLSTATE[HY000]: General error: 1007 Can't create database 'my-database'; database exists</span>

il faudra lancer alors la commande `make db-drop` puis de relancer la commande `make install`

## Executer les tests sur son instance

Au préalable, executer la commande suivante :

```shell
make connect
# puis
php bin/console secrets:set AKISMET_KEY --env=test
```

Sortir de la console bash puis éxecuter :
```shell
make create-test-env
```

Cela va créer la base de données de test.

Puis exécuter 

```shell
make phpunit
```