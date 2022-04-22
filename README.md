# EP Portfolio

Portfolio of Edouard Proust, web developer (PHP , Symfony and Javascript)
v.1.0: march 2022

This project is developped on GitLab: https://gitlab.com/solo-projects3/ep-portfolio.git

----------

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
----------

## Development

```bash
composer install
npm install
npm run dev
docker-compose up -d
symfony serve -d
```
If database not set up yet;
```bash
php bin/console make:migration -n
php bin/console d:m:m -n
php bin/console d:f:l --group=dev -n
```

----------

## Production

### 1. Run these commands (connect to ssh and clone directory):
```bash
ssh deploy@<host>
git clone https://gitlab.com/<directory> .
```
- Replace <host> by the host IP address (eg. 168.38.144.76)
- Replace <directory> by the direMy portfolio featuring my projects as a web developer.ctory slug (eg. /my-folder/my-project)
- For cloning from gitLab, use this command: `git clone https://gitlab.com/<directory>`

### 2. Create an .env.local containing these keys:
```bash
APP_ENV=prod
MAILER_DSN=smtp://<dsn_host>
DATABASE_URL="mysql://<db_user>:<db_password>@<db_host>/<db_name>"
```
Don't precise "serverVersion" attribute in DATABASE_URL or this error may be fired: "The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue".

### 3. Connect to server using SSH and run these commands:
```bash
composer install
npm install
npm run dev
```
On first deployment only (database not set up yet);
```bash
symfony console make:migration -n
symfony console d:m:m -n
symfony console d:f:l --group=prod -n
```

### 4. Create your admin account:
```bash
php bin/console app:create:admin <username> <password>
```

----------

## Usefull commands

Build Encore assets on save:
```bash
npm run watch
```

----------

## Config

- **Add or Edit a global dir or file path to be used anywhere in the project:** 
Add or Edit a const in _Path.php_ and then call it where you need: `Path::MY_PATH`
- **Add or Edit a global config value to be used anywhere in the project:** 
Add or Edit a const in _Config.php_ and then call it where you need: `Config::MY_VALUE`
- **Add an new option (AdminOption) in Admin dahsboard:** 
Add a new const in _src/DataFixtures/AdminOptions.php_
- **Change fixtures default values:** 
Edit const in _src/DataFixtures/AppFixtures.php_

----------

## CKEditor

- **Reorder toolbar:** update `Editor.defaultConfig()` function in _assets/ckeditor/builds/**{build_name}**/src/ckeditor.js_

- **Add plugins and features:** 
    - [Download package on NPM](https://www.npmjs.com/search?q=%40ckeditor-5%2Fckeditor5-)
    - Run this command: `npm i @ckeditor5/ckeditor5-<plugin_name>`
    - Add this line after other **import** statements on the top of _assets/ckeditor/builds/**{build-name}**/src/ckeditor.js_ : `import <PluginName> from '@ckeditor/ckeditor5-<plugin-name>/src/<file-name>.js';

- **Add a build:**
    - [Configure and download package here](https://ckeditor.com/ckeditor-5/online-builder/)
    - Unzip it in _assets/ckeditor/builds/**{build-name}**_
    - In _assets/js/admin/ckeditor.js_, update the first line: `import ClassicEditor from '../../../public/build/ckeditor/builds/<build-name>/src/ckeditor';`


**/!\ After any of the above actions:**
1. update `Editor.defaultConfig()` function in _assets/ckeditor/builds/**{build-name}**/src/ckeditor.js_
2. Run these commands:
```bash 
npm run build
npm run dev
```