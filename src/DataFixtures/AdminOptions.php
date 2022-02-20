<?php

namespace App\DataFixtures;

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
 * >'type' = The type of the field (eg. 'text', 'boolean', 'url', 'email' or 'number')
 * 'value' = The default value of thie option
 * 'label' = Custom label on top of the field (if null, it will)
 * 'help' = Help message below the field
 */
class AdminOptions
{
    const SITE_NAME = ['text', 'Edouard Proust Portfolio'];
    const SITE_DOMAIN = ['text', 'edouardproust.dev'];

    const CONTACT_NAME = ['text', 'Edouard Proust'];
    const CONTACT_EMAIL = ['email', 'contact@edouardproust.dev'];
    const CONTACT_PHONE = ['text', '(+48) 727 775 824'];
    const CONTACT_ADDRESS = ['text', 'Helclów 9/5A, 31-148 Kraków (Polska)'];

    const SOCIAL_LINKEDIN = ['url', '#'];
    const SOCIAL_GITHUB = ['url', '#'];
    const SOCIAL_STACKOVERFLOW = ['url', '#'];

    const PROJECTS_PER_PAGE = ['number', 6];
    const LESSONS_PER_PAGE = ['number', 6];
    const POSTS_PER_PAGE = ['number', 6];

    const HOME_PROJECTS = ['number', 3];
    const HOME_FEATURED_PROJECTS = ['number', 3];
    const HOME_LESSONS = ['number', 3];
    const HOME_POSTS = ['number', 3];

    const NOTIFICATION_NEW_COMMENT = ['boolean', null, true];
    const NOTIFICATION_NEW_AUTHOR = ['boolean', null, true];
    const SHOW_COMMENTS_ON_POST = ['boolean', null, true];

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
