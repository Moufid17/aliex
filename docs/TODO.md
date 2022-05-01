IN progress : 
 - Add categories in DB.
### TO DO 1:
- [x] docker : rm database | check apirest procject
- [x] optional : remove web pack encore 
- [x] Architecture back and frontend
- [ ] *define entities : category and products | Display 
- [ ] User entity (Guard) | Login and Register Form.
- [ ] set up private view : USER.
- [ ] mailer warning <sub>[setting up](https://symfony.com/doc/current/mailer.html#installation)</sub>
- [ ] Set up Admin template



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