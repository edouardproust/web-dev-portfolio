<?php

namespace App\DataFixtures;

use App\Config;
use ReflectionClass;
use Symfony\Component\Serializer\Encoder\JsonEncode;

/**
 * Define website options (AdminOption) to be saved in database
 * - Constant are saved in the order you listed them in the class (from top to bottom)
 * - You must set them using this pattern:
 * >const MY_CONSTANT = [type, value, isActive, label, help]
 * - The type is mandatory. Other options are optionnal:
 * >const MY_CONSTANT = [type] will work / const MY_CONSTANT = [] won't work 
 * - Index of the array: 
 * >'type' = The type of the field 
 * (eg. Config::FIELD_TEXT, Config::FIELD_BOOL, Config::FIELD_URL, 
 * Config::FIELD_EMAIL or Config::FIELD_NUMBER)
 * 'value' = The default value of thie option
 * 'label' = Custom label on top of the field (if null, it will)
 * 'help' = Help message below the field
 */
class AdminOptions
{
    const SITE_NAME =  [
        Config::FIELD_TEXT, 'Edouard Proust Portfolio', null,
        'Site: Title',
        'The site\'s name, displayed on top, and on several locations of the website and in emails.'
    ];
    const SITE_DOMAIN = [
        Config::FIELD_TEXT, 'edouardproust.dev', null,
        'Site: Domaine name',
        'The website domaine name (without "https://www"). Eg. "mysite.com"'
    ];

    const CONTACT_NAME = [
        Config::FIELD_TEXT, 'Edouard Proust', null,
        'Contact: Website owner\'s name',
        'The name displayed in the "from" field in the emails you send.'
    ];
    const CONTACT_EMAIL = [
        Config::FIELD_EMAIL, 'contact@edouardproust.dev', null,
        'Contact: Email',
        'The email the visitors will use to send you emails. 
        It is displayed on Contact page and in the "from" field of the email you send.'
    ];
    const CONTACT_PHONE = [
        Config::FIELD_TEXT, '(+48) 727 775 824', null,
        'Contact: Phone',
        'The phone number the visitors can use to call you or your company. 
        It is displayed on Contact page.'
    ];
    const CONTACT_ADDRESS = [
        Config::FIELD_TEXT, 'Helclów 9/5A, 31-148 Kraków (Polska)', null,
        'Contact: Address',
        'The address of your company / where you live. It is displayed on Contact page.'
    ];

    const SOCIAL_LINKEDIN = [
        Config::FIELD_URL, 'https://fr.linkedin.com/in/edouardproust', null,
        'Social: LinkedIn profile',
        'Link to you LinkedIn profile (eg. https://www.linkedin.com/pub/dir/myname)'
    ];
    const SOCIAL_GITHUB = [
        Config::FIELD_URL, 'https://github.com/edouardproust', null,
        'Social: GitHub profile',
        'Link to you LinkedIn profile (eg. https://github.com/myname)'
    ];
    const SOCIAL_STACKOVERFLOW = [
        Config::FIELD_URL, 'https://stackoverflow.com/users/13865643/edouard', null,
        'Social: StackOverflow profile',
        'Link to you LinkedIn profile (eg. https://stackoverflow.com/users/12345678/myname)'
    ];

    const NOTIFICATION_NEW_COMMENT = [
        Config::FIELD_BOOL, null, true,
        'Notification: New comment published',
        'Do you want to receive an email each time a comment is published 
        by a visitor on a project, post or lesson? 
        (You need to validate comments before them to be published on the website.'
    ];
    const NOTIFICATION_NEW_AUTHOR = [
        Config::FIELD_BOOL, null, true,
        'Notification: New author registration',
        'Do you want to receive an email each time a visitor submit a registration to become an author? 
        (You need to validate them before they can write lessons or posts.)'
    ];

    const SHOW_COMMENTS_ON_PROJECT = [
        Config::FIELD_BOOL, null, true,
        'Comments: Show on Projects',
        'Do you want to show comments at the bottom on a project page, and allow visitors to write one?'
    ];
    const SHOW_COMMENTS_ON_LESSON = [
        Config::FIELD_BOOL, null, true,
        'Comments: Show on Pessons',
        'Do you want to show comments at the bottom on a lesson page, and allow visitors to write one?'
    ];
    const SHOW_COMMENTS_ON_POST = [
        Config::FIELD_BOOL, null, true,
        'Comments: Show on Posts',
        'Do you want to show comments at the bottom on a post page, and allow visitors to write one?'
    ];

    const PROJECTS_PER_PAGE = [
        Config::FIELD_NUM, 6, null,
        'Collection: Projects per page',
        'How many projects do you want to display per collection page?'
    ];
    const LESSONS_PER_PAGE = [
        'number', 6, null,
        'Collection: Lessons per page',
        'How many lessons do you want to display per collection page?'
    ];
    const POSTS_PER_PAGE = [
        Config::FIELD_NUM, 6, null,
        'Collection: posts per page',
        'How many posts do you want to display per collection page?'
    ];

    const HOME_PROJECTS = [
        Config::FIELD_NUM, 3, null,
        'Homepage: Projects to show',
        'How many projects do you want to display in the "Last projects" section on homepage?'
    ];
    const HOME_FEATURED_PROJECTS = [
        'number', 3, null,
        'Homepage: Featured projects to show',
        'How many projects do you want to display in the "Featured projects" section on homepage?'
    ];
    const HOME_LESSONS = [
        Config::FIELD_NUM, 3, null,
        'Homepage: lessons to show',
        'How many lessons do you want to display in the "Last lessons" section on homepage?'
    ];
    const HOME_POSTS = [
        Config::FIELD_NUM, 3, null,
        'Homepage: Posts to show',
        'How many posts do you want to display in the "Last posts" section on homepage?'
    ];

    /**
     * Get the index's value of a given option. The input can be either an array or an index.
     * @param array|string $constant The constant
     * @param string $index The index in the array ('type', 'value', 'label' or 'help')
     * @return null|string|int The index's value
     */
    public static function get($constant, string $index)
    {
        if (is_array($constant)) {
            return self::getIndexValue($constant, $index);
        } else {
            foreach (self::getConstants() as $key => $array) {
                if ($key === $constant) {
                    return self::getIndexValue($array, $index);
                }
            }
        }
        return null;
    }

    /** 
     * Get an array containing all constants: ['CONSTANT1' => [index1, index2, ... ], ... ]
     * @return array  
     */
    public static function getConstants()
    {
        $class = new ReflectionClass(__CLASS__);
        return $class->getConstants();
    }

    /**
     * Get the index's value of a given option. The input must be an array.
     * @param array $constant The constant
     * @param string $index The index in the array ('type', 'value', 'label' or 'help')
     * @return null|string|int The index's value
     */
    private static function getIndexValue(array $constant, string $index): ?string
    {
        $relations = ['type' => 0, 'value' => 1, 'isActive' => 2, 'label' => 3, 'help' => 4];
        foreach ($relations as $name => $int) {
            if ($name === $index) {
                return $constant[$int] ?? null;
            }
        }
        return null;
    }
}
