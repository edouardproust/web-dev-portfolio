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