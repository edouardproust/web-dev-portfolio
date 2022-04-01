# EP Portfolio

Portfolio of Edouard Proust, web developer (PHP , Symfony and Javascript)
v.1.0: march 2022

## Technologies & Requirements

- PHP 7.4
- Symfony 5
- Symfony CLI
- Composer
- Docker
- Docker-compose
- NodeJS

```bash
symfony check:requirements
```

## Launch Dev environment

```bash
composer install
npm install
npm run dev
docker compose up -d
symfony serve -d
php bin/console make:migration -n
php bin/console d:m:m -n
php bin/console d:f:l -n
```

## Prod (deployment)

1. Run these commands (connect to ssh and clone directory). 
```bash
ssh deploy@<host>
git clone https://gitlab.com/<directory>
```
- Replace <host> by the host IP address (eg. 168.38.144.76)
- Replace <directory> by the directory slug (eg. /my-folder/my-project)
- For cloning from gitLab, use these comment: git clone https://gitlab.com/solo-projects3/ep-portfolio.git

2. Update variables in .env.local

3. Connect to server using SSH and run these commands:
```bash
composer install
npm install
npm run dev
docker compose up -d
symfony serve -d
php bin/console make:migration -n
php bin/console d:m:m -n
php bin/console d:f:l -n
```

## Usefull commands

Build Encore assets on save:
```bash
npm run watch
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