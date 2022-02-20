<?php

namespace App;

/**
 * This class allow to store config variables to be used in the project
 */
class Config
{
    const ROLES = [
        'User' => 'ROLE_USER',
        'Author' => 'ROLE_AUTHOR',
        'Admin' => 'ROLE_ADMIN'
    ];

    const HEADLINE_MAX_LENGTH = 255;
    const ADMIN_FORM_MAIN_CSS_CLASS = 'col-md-8';
    const ADMIN_FORM_SIDE_CSS_CLASS = 'col-md-4';

    const ADMIN_CRUD_ENTITY_TITLE_MAX_LENGTH = 40;
}
