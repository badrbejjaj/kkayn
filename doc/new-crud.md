# Create New Crud

## Backend

---

### OpenApi :

* Create new file in `openapi\apis` folder and give it the name of the entity
* Inside the folder create 3 `yaml` files  `components.yaml` , `paths.yaml` and `tags.yaml`
* `components.yaml` : create the main entity modele
* `paths.yaml` : create default paths `create/update/delete/list`
* `tags.yaml` : add the tag name of the crud

### Symfony :

* Create the entity in `backend/src/Entity` (use the command `php bin/console make:entity`)
* Create the Repository in `backend/src/Repository` (if you use the command the repo will automatically created)
* Next Create new file in `backend\src\Api` folder named with the crud name suffixed with the word `Api`
* `Service Declaration` : Go to `services.yaml` in `backend\config` folder and add:

```yaml
        app.api.CRUDNAME:
            class: App\Api\{CRUDNAME}Api
            tags:
                - { name: "kkayn_api.api", api: "{CRUDNAME}" }
```

* Create a migration by running the command `php bin/console doctrine:migration:diff`
* Run the migration `php bin/console doctrine:migration:migrate`


## FrontEnd

---