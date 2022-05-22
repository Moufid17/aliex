## Getting started

```bash
docker-compose build --pull --no-cache
docker-compose up -d
```

## SYMFONY BUNDLES
```
composer require webapp
```

```
# URL
http://127.0.0.1

# Env DB (à mettre dans .env, si pas déjà présent)
DATABASE_URL="postgresql://postgres:password@db:5432/db?serverVersion=13&charset=utf8"
```

## Commandes utiles
```
# Lister l'ensemble des commandes existances 
docker-compose exec php bin/console

# Supprimer le cache du navigateur
docker-compose exec php bin/console cache:clear

# Création de fichier vierge
docker-compose exec php bin/console make:controller
```

## Gestion de base de données

#### Commandes de création d'entité
```
docker-compose exec php bin/console make:entity
```

#### Mise à jour de la base de données
```
# Voir les requètes qui seront jouer avec force
docker-compose exec php bin/console doctrine:schema:update --dump-sql

# Executer les requètes en DB
docker-compose exec php bin/console doctrine:schema:update --force
```

#### Fixtures 
Documentation : https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html
```
# Installation 
docker compose exec php composer require --dev orm-fixtures

# Installtion Faker 
docker compose exec php composer require --dev fzaninotto/faker
```

#### WebpackEncore
- Run dev and encore dev :
    ```
    npm run dev
    ```
    >[source](https://symfony.com/doc/current/frontend/encore/simple-example.html#configuring-encore-webpack):


""" docker-compose
###> doctrine/doctrine-bundle ###
  # database:
  #   image: postgres:${POSTGRES_VERSION:-13}-alpine
  #   environment:
  #     POSTGRES_DB: ${POSTGRES_DB:-app}
  #     # You should definitely change the password in production
  #     POSTGRES_PASSWORD: ${POSTGRES_PASSWORD:-ChangeMe}
  #     POSTGRES_USER: ${POSTGRES_USER:-symfony}
  #   volumes:
  #     - db-data:/var/lib/postgresql/data:rw
      # You may use a bind-mounted host directory instead, so that it is harder to accidentally remove the volume and lose all your data!
      # - ./docker/db/data:/var/lib/postgresql/data:rw
###< doctrine/doctrine-bundle ###


###> doctrine/doctrine-bundle ###
  # db-data:
###< doctrine/doctrine-bundle ###
"""

""" docker-composer.override-password
###> doctrine/doctrine-bundle ###
  # database:
  #   ports:
  #     - "5432"
###< doctrine/doctrine-bundle ###
"""


""" ./src/Kernel.php
<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
###