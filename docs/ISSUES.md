### Update PHP VERSION
1. In `docker-compose.yml`
    - change : `php:8.0-fpm` to `php:8.1-fpm`
2. Run :
    - stop all containers : `docker-compose down`
    - build the docker-compose file : `docker-compose build --pull --no-cache`
    - pull the docker-compose instance : `docker-compose pull`
    - check your docker php version : `docker exec aliex_php_1 php -v`
    
### [DOCKER ISSUE](https://docs.tibco.com/pub/mash-local/4.1.0/doc/html/docker/GUID-BD850566-5B79-4915-987E-430FC38DAAE4.html): 
> How to Do a Clean Restart of a Docker Instance
1. Stop the container(s) using the following command : `docker-compose down`
2. Delete all containers using the following command : `docker rm -f $(docker ps -a -q)`
3. Delete all volumes using the following command : `docker volume rm $(docker volume ls -q)`
4. Restart the containers using the following command : `docker-compose up -d`

### Upload File : Vich_Uploader
1. Unable to write in "/var/www/public/images/products/" directory 
    > Write mode was not actived for my computer user.
    - Run : `chmod -R 777 public/images/products/`
2. ~Serialization of 'Symfony\Component\HttpFoundation\File\File' is not allowed~
    > The Serializable interface and its serialize and unserialize methods have been added to allow the User class to be serialized to the session. This may or may not be needed depending on your setup, but it's probably a good idea. The id is the most important value that needs to be serialized because the refreshUser() method reloads the user on each request by using the id. In practice, this means that the User object is reloaded from the database on each request using the id from the serialized object. This makes sure all of the User's data is fresh.
        ---
    Symfony also uses the username, salt, and password to verify that the User has not changed between requests. Failing to serialize these may cause you to be logged out on each request. If your User implements EquatableInterface, then instead of these properties being checked, your isEqualTo method is simply called, and you can check whatever properties you want. Unless you understand this, you probably won't need to implement this interface or worry about it."

    - The process below does'nt work. 
        ```
        /src/Entity/User.php

            class User implements UserInterface PasswordAuthenticatedUserInterface{

                ...
                public function __serialize()
                {
                    // return serialize(array(
                    //     $this->id,
                    //     $this->email,
                    //     $this->password,
                    // ));
                    return array(
                        $this->id,
                        $this->email,
                        $this->password,
                    );
                }

                public function __unserialize($serialized)
                {   
                    # It is important to init the params below before return them.
                    $this->id = $serialized[0];
                    $this->email = $serialized[1];
                    $this->password = $serialized[2];
                    return array(
                        $this->id,
                        $this->email,
                        $this->password,
                    );        
                }
            }
        ```

    - Ressources : 
        1. [src](https://github.com/dustin10/VichUploaderBundle/issues/987)
        2. [youtube - Tech Wall](https://www.youtube.com/watch?v=Yd_qvZpD-L4)










```
    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     *
     * @var EmbeddedFile
     */
    /**
     * @Assert\File(
     *     maxSize = "500k",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"},
     *     mimeTypesMessage = "Please upload a valid IMAGE"
     * )
     */
```