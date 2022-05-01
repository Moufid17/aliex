### Sourcing :
> https://github.com/AdrienMrn
### Getting Start :
```
Download docker services : docker-compose build --pull --no-cache 
Run it : docker-compose up -d
```
- Apache error or : 
```
sudo service apache2 status
sudo service apache2 stop
docker-compose up -d
```
- pd_sql error:
```
docker-compose ps
docker kill CONTAINER-ID ou docker-compose down
docker-compose up -d
```
- Running prefix : `docker-compose exec ...`
- Get in browser : 
    - Front : `http://127.0.0.1`
    - Back : `http://127.0.0.1/admin/`
    > Routing system is redefine into `/config/routes/annotations.yaml`. Also all controller routing and template configuration.
    > Routing system is redefine into `./src/Kernel.php`.
- Env DB (if it is not define yet):
    ```
    DATABASE_URL="postgresql://postgres:password@db:5432/db?serverVersion=13&charset=utf8"
    ```

### Tips Commands :
- #### DB CONFIG (without adminer):
    - Properties: 
        - Name: db@localhost 
        - database : db
        - host: localhost
        - User: postgres
        - Pass : password
        - url : `jdbc:postgresql://localhost:5432/db`
    - CMDs:
        ```
            - doctrine:schema:update --dump-sql (update schema)
            - doctrine:schema:update --force (update my database without make migration)
        ```
- #### ROUTE :
    ```
    - docker-compose exec php bin/console debug:router
    - docker-compose exec php bin/console -e dev ou prod
    ```
- #### FIXTURES :
    - Setup :
      ```
      docker-compose exec php composer require --dev orm-fixtures
      ```
    - Define fixture dependencies (if it is necessary): 
        ```
        public function getDependencies()
        {
            return [
                BrandFixtures::class,
                CustomerFixtures::class
            ];
        }
        ```
    - Loading (after fixtures created, persisted and flushed):
      ```
      docker-compose exec php bin/console make:Fixture
      ```
      >[doc : Loading the Fixture Files in Order](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html#loading-the-fixture-files-in-order)
    > externals docs :
    - [Sharing Objects between Fixtures](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html#sharing-objects-between-fixtures)

    - Add a `new owner` to `/src/` folder  after the `fixtures` implementation : 
        ```
        sudo chown -R moufid src
        ```
- [404 ERROR](https://symfony.com/doc/current/controller.html#managing-errors-and-404-pages)
### Important concepts :
1. `Params converter` : Definir l'entité comme paramètre du *controlleur* aulieu de l'id par exemple. 

2. `Entity vs Repository` :
- **Entity** : Représentation model de l'objet dans la base
- **Repository** : Regroupe l'ensemble des requêtes possible sur la base. 
    >Un objet est retourner au lieu de la classe.<br>
    >Il peut être obtenu par appel direct ave `use` ou par le biais de l'`entity manager`.

3. `Persist vs Flush`:
    - **Persist** :Ecouter l'objet grâce à soit :
        - entity manager : `Doctrine\ORM\EntityManagerInterface`
        - object manager : `Doctrine\Persistence\ObjectManager`
    - **Flush** : update datas in database.

4. Route naming :  `controller_method`
5. `Utiliser un integer (id) dans la view` grâce à (`~`):
    ```
    <input type="hidden" name="token" value="{{ csrf_token('delete-item'~car.id) }}"/>
    ```

6. [Get entity property list in form](https://symfony.com/doc/current/reference/forms/types/entity.html):
    ```
    ->add('brand', EntityType::class, [
    'class' => Brand::class,
    'choice_label' => 'name',
    ])
    ```
7. [DateType with widget](https://symfony.com/doc/current/reference/forms/types/date.html):
