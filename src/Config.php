<?php

namespace App;

use ReflectionClass;

/** @package App
 * Constants are defined in order to set them in the database if needed.
 * You must set them using this pattern:
 * const MY_CONSTANT = ['slug' => value]
 */
class Config
{
    const SITE_NAME = 'Edouard Proust Portfolio';
    const SITE_DOMAIN = 'edouardproust.dev';

    const CONTACT_NAME = 'Edouard Proust';
    const CONTACT_EMAIL = 'contact@edouardproust.dev';
    const CONTACT_PHONE = '(+48) 727 775 824';
    const CONTACT_ADDRESS = 'Helclów 9/5A, 31-148 Kraków (Polska)';

    const SOCIAL_LINKEDIN = '#';
    const SOCIAL_GITHUB = '#';
    const SOCIAL_STACKOVERFLOW = '#';

    const PROJECTS_PER_PAGE = 6;
    const LESSONS_PER_PAGE = 6;
    const POSTS_PER_PAGE = 6;

    const HOME_PROJECTS = 3;
    const HOME_FEATURED_PROJECTS = 3;
    const HOME_LESSONS = 3;
    const HOME_POSTS = 3;

    const HEADLINE_MAX_LENGTH = 255;

    public static function getConstants()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }
}
