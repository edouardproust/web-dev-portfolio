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

## Dev: generate environment

```bash
composer install
npm install
npm run dev
docker-compose up -d
symfony serve -d
php bin/console make:migration -n
php bin/console d:m:m -n
php bin/console d:f:l -n
php bin/console ckeditor:install public/bundles/fosckeditor
```

## Prod: deployment

**1. Run these commands (connect to ssh and clone directory).**
```bash
ssh deploy@<host>
git clone https://gitlab.com/<directory> .
```
- Replace <host> by the host IP address (eg. 168.38.144.76)
- Replace <directory> by the directory slug (eg. /my-folder/my-project)
- For cloning from gitLab, use this command: `git clone https://gitlab.com/<directory>`

**2. Create an .env.local containing these keys:**
```bash
APP_ENV=prod
MAILER_DSN=smtp://<dsn_host>
DATABASE_URL="mysql://<db_user>:<db_password>@<db_host>/<db_name>"
```
Don't precise "serverVersion" attribute in DATABASE_URL or this error may be fired: "The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue".

**3. Connect to server using SSH and run these command:**
```bash
composer install
```

**4. [security] Update your admin credentials: connect to admin panel (username: "admin", password: "admin") -> click on top-right photo -> My profile

## Usefull commands

- Add an admin account: `php bin/console app:create:admin <username> <password>`
- Build Encore assets on save: `npm run watch`

## Config

- **Add or Edit a global dir or file path to be used anywhere in the project:** 
Add or Edit a const in _Path.php_ and then call it where you need: `Path::MY_PATH`
- **Add or Edit a global config value to be used anywhere in the project:** 
Add or Edit a const in _Config.php_ and then call it where you need: `Config::MY_VALUE`
- **Add an new option (AdminOption) in Admin dahsboard:** 
Add a new const in _src/DataFixtures/AdminOptions.php_
- **Change fixtures default values:** 
Edit const in _src/DataFixtures/AppFixtures.php_
