### Tips
- [Form & fileUpload :](https://symfony.com/doc/current/controller/upload_file.html#how-to-upload-files)
    ```
    {# templates/product/new.html.twig #}
    <h1>Adding a new product</h1>

    {{ form_start(form) }}
        {# ... #}

        {{ form_row(form.brochure) }}
    {{ form_end(form) }}
    ```
- [For  with conditon](https://twig.symfony.com/doc/2.x/tags/for.html#adding-a-condition)