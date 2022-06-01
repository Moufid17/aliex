### TO DO:
> Legends : <br>
> _*_  : first current task <br>
> _*3_ : third current task


**GENERAL CONFIG**
- [x] Architecture back and frontend
- [x] set up private view : USER.

- *[ ] Setup product [VOTER](https://symfony.com/doc/current/security/voters.html)
  > NB: 
  - [ ] [Define](https://github.com/AdrienMrn/Symfony2022-IWJ/blob/master/config/packages/security.yaml) user's role in _config/packages/_`security.yaml`:
    ```
    security:
      role_hierachy:
          ROLE_ADMIN: ROLE_USER
          ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_USER]
      access_control:
          - { path: ^/admin, roles: ROLE_ADMIN }
          - { path: ^/mon-compte, roles: ROLE_USER }
          - { path: ^/connexion, roles: PUBLIC_ACCESS}
          - { path: ^/inscription, roles: PUBLIC_ACCESS}
          - { path: ^/, roles: ROLE_USER}
    ```
  - [ ] [Define](https://github.com/AdrienMrn/Symfony2022-IWJ/blob/master/src/Security/BrandVoter.php) `ProductVoter.php` in _src/security/_ 

  - [ ] [Update](https://github.com/AdrienMrn/Symfony2022-IWJ/blob/master/src/Controller/Back/BrandController.php) in `ProductController` :
    - example :
      ```
      ...
      use App\Security\BrandVoter;
      use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
      use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
      ...
      
      #[IsGranted(BrandVoter::EDIT, 'brand')]
      public function edit(Request $request, Brand $brand, EntityManagerInterface $entityManager): Response
      {
      ...
      }
      ```

- *1[ ] Setup product [Vich_Uploader](https://symfony.com/bundles/EasyAdminBundle/2.x/integration/vichuploaderbundle.html#customizing-form-fields-for-image-and-file-uploading)
  - [github](https://github.com/dustin10/VichUploaderBundle)


**LOGIN SYSTEM**
- [x] User entity AND Login and Register Form.
- [x] Register
- [x] Login/logout
- [x] Reset Password
- *3[ ] Notification/Email after Registering, reset password and more
  - [mailer](https://symfony.com/doc/current/mailer.html#installation)

**ADMIN**
- *2[ ] Definir ADMIN template

**SERVICES**
  - ANNONCES:
    - [ ] Deposer un annonce :
      - Vue Produit pour admin
    - [ ] Ajout/stockage de fichier produit
  - FILTRE :
    - [ ] Rechecher de produits (par categorie, ville, vendeur, popularité).
  - CART :
    - [ ] Panier utilisateur (Ajouter/Retirer/ Suppression du panier)
  - PAIEMENT :
    - [ ] Integration STRIPE
    - [ ] vente de Produit
    - [ ] Achat de Produit
    - [ ] Tester Paiement
  - FACTURE :
    - [ ] Création
    - [ ] Stockage
  - TRANSPORT :
    - [ ] Confirmation d'envoie et de réception de Produit