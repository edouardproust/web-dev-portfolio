<?php

namespace App;

/**
 * This class allow to store config variables to be used in the project
 */
class Config
{
    const PORTFOLIO_SUBDOMAIN_URL = 'file:///var/www/dev-portfolio/public/_unrouted/';

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_AUTHOR = 'ROLE_AUTHOR';

    const ROLES = [
        'User' => self::ROLE_USER,
        'Author' => self::ROLE_AUTHOR,
        'Admin' => self::ROLE_ADMIN
    ];
    // Roles that allow access to EasyAdmin panel
    const EASY_ADMIN_ROLES = ['ROLE_AUTHOR', 'ROLE_ADMIN'];

    const HEADLINE_MAX_LENGTH = 255;
    const ADMIN_FORM_MAIN_CSS_CLASS = 'col-md-8';
    const ADMIN_FORM_SIDE_CSS_CLASS = 'col-md-4';

    const ADMIN_CRUD_ENTITY_TITLE_MAX_LENGTH = 40;

    const FIELD_BOOL = 'boolean';
    const FIELD_NUM = 'number';
    const FIELD_TEXT = 'text';
    const FIELD_EMAIL = 'email';
    const FIELD_URL = 'url';
}
