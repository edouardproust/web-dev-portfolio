# EP Portfolio

Portfolio of Edouard Proust, web developer (PHP , Symfony and Javascript)

## Technologies & Requirements

- PHP 7.4
- Symfony 5
- Symfony CLI
- Composer
- Docker
- Docker-compose
- Nodejs & yarn

```bash
symfony check:requirements
```

## Launch

```bash
composer install
yarn install
yarn dev
docker compose up -d
symfony serve -d
symfony console make:migration
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

## Usefull commands

To build Encore files automatically:
```bash
yarn watch
```

## Config

- **Add or Edit a global dir or file path to be used anywhere in the project:** 
Add or Edit a const in Path.php and then call it where you need: Path::MY_PATH
- **Add or Edit a global config value to be used anywhere in the project:** 
Add or Edit a const in Config.php and then call it where you need: Config::MY_VALUE
- **Add an new option (AdminOption) in Admin dahsboard:** 
Add a new const in src/DataFixtures/AdminOptions.php
- **Change fixtures default values:** 
Edit const in src/DataFixtures/AppFixtures.php