<?php

namespace App\DataFixtures;

use App\Config;
use ReflectionClass;
use App\DataFixtures\AbstractFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Fixtures group: 'prod'
 *
 * Define website options (AdminOption) to be saved in database
 *
 * - Constant are saved in the order you listed them in the class (from top to bottom)
 *
 * - You must set them using this pattern:
 *       >**`const MY_CONSTANT = [type, value, isActive, label, help]`**
 *
 * - The type is mandatory. Other options are optionnal:
 *       >**`const MY_CONSTANT = [type]`** will work, but `const MY_CONSTANT = []` won't.
 *
 * - Array index:
 *       >- **0 => type** | The type of the field
 *          (eg. `Config::FIELD_TEXT`, `Config::FIELD_BOOL`, `Config::FIELD_URL`,
 *          `Config::FIELD_EMAIL` or `Config::FIELD_NUMBER`)
 *       >- **1 => value** | The default value of the option (for all type of fields except 'boolean').
 *          For 'boolean' fields: always set this to null
 *       >- **2 => isActive** | The default value of the option (for 'boolean' field type only).
 *          Accepted values: null, true or false. Set always to null for non-boolean fields types.
 *       >- **3 => label** | Custom label on top of the field (if null, it will)
 *       >- **4 => help** | Help message below the field
 */
class AdminOptions extends AbstractFixtures implements FixtureGroupInterface
{

    // Template:
    //
    // const SITE_NAME =  [
    //     'type' => Config::FIELD_TEXT,
    //     'value' => null,
    //     'isActive' => null,
    //     'label' => '',
    //     'help' => ''
    // ];

    const SITE_NAME =  [
        'type' => Config::FIELD_TEXT,
        'value' => '{edouard<b>proust</b>}',
        'isRequired' => true,
        'label' => 'Site: Title',
        'help' => 'The site\'s name, displayed on top, and on several locations of the website and in emails.' .
            ' This text will replace the logo if none has been defined.'
    ];
    const SITE_DOMAIN = [
        'type' => Config::FIELD_TEXT,
        'value' => 'edouardproust.dev',
        'isRequired' => true,
        'label' => 'Site: Domaine name',
        'help' => 'The website domaine name (without "https://www"). Eg. "mysite.com"'
    ];
    const SITE_LOGO = [
        'type' => Config::FIELD_TEXT,
        'isUploadable' => true,
        'label' => 'Site: Logo',
        'help' => 'An image that reflects you website concept. It is displayed on several locations of the wesite.'
    ];
    const SITE_LOGO_HEIGHT = [
        'type' => Config::FIELD_NUM,
        'value' => 40,
        'isRequired' => true,
        'label' => 'Site: Logo height',
        'help' => 'Defines the height of the header logo in pixels (max. 65px: all values bigger will apply 65px height).'
    ];
    const SITE_FAVICON = [
        'type' => Config::FIELD_TEXT,
        'file' => 'favicon.ico',
        'isUploadable' => true,
        'label' => 'Site: Favicon',
        'help' => 'The icon displayed in the browser tab. Recommended size: 16x16. Allowed extensions: .png, .jpg, .ico'
    ];

    const CONTACT_NAME = [
        'type' => Config::FIELD_TEXT,
        'value' => 'Edouard Proust',
        'isRequired' => true,
        'label' => 'Contact: Website owner\'s name',
        'help' => 'The name displayed in the "from" field in the emails you send.'
    ];
    const CONTACT_EMAIL = [
        'type' => Config::FIELD_EMAIL,
        'value' => 'contact@edouardproust.dev',
        'label' => 'Contact: Email',
        'isRequired' => true,
        'help' => 'The email the visitors will use to send you emails. ' .
            'It is displayed on Contact page and in the "from" field of the email you send.'
    ];
    const CONTACT_PHONE = [
        'type' => Config::FIELD_TEXT,
        'value' => '(+48) 727 775 824',
        'label' => 'Contact: Phone',
        'help' => 'The phone number the visitors can use to call you or your company. ' .
            'It is displayed on Contact page.'
    ];
    const CONTACT_ADDRESS = [
        'type' => Config::FIELD_TEXT,
        'value' => 'Helclów 9/5A, 31-148 Kraków (Polska)',
        'label' => 'Contact: Address',
        'help' => 'The address of your company / where you live. It is displayed on Contact page.'
    ];

    const SOCIAL_LINKEDIN = [
        'type' => Config::FIELD_URL,
        'value' => 'https://fr.linkedin.com/in/edouardproust',
        'label' => 'Social: LinkedIn profile',
        'help' => 'Link to you LinkedIn profile (eg. https://www.linkedin.com/pub/dir/myname)'
    ];
    const SOCIAL_GITHUB = [
        'type' => Config::FIELD_URL,
        'value' => 'https://github.com/edouardproust',
        'label' => 'Social: GitHub profile',
        'help' => 'Link to you LinkedIn profile (eg. https://github.com/myname)'
    ];
    const SOCIAL_STACKOVERFLOW = [
        'type' => Config::FIELD_URL,
        'value' => 'https://stackoverflow.com/users/13865643/edouard?tab=profile',
        'label' => 'Social: StackOverflow profile',
        'help' => 'Link to you LinkedIn profile (eg. https://stackoverflow.com/users/12345678/myname)'
    ];

    const ABOUT_CV = [
        'type' => Config::FIELD_TEXT,
        'isUploadable' => true,
        'label' => 'About: CV',
        'help' => 'Upload your Summary for recruiters.'
    ];

    const NOTIFICATION_NEW_COMMENT = [
        'type' => Config::FIELD_BOOL,
        'label' => 'Notification: New comment published',
        'help' => 'Do you want to receive an email each time a comment is published ' .
            'by a visitor on a project, post or lesson? ' .
            '(You need to validate comments before them to be published on the website.'
    ];
    const NOTIFICATION_NEW_AUTHOR = [
        'type' => Config::FIELD_BOOL,
        'isActive' => true,
        'label' => 'Notification: New author registration',
        'help' => 'Do you want to receive an email each time a visitor submit a registration to become an author? ' .
            '(You need to validate them before they can write lessons or posts.)'
    ];

    const SHOW_COMMENTS_ON_PROJECT = [
        'type' => Config::FIELD_BOOL,
        'isActive' => true,
        'label' => 'Comments: Show on Projects',
        'help' => 'Do you want to show comments at the bottom on a project page, and allow visitors to write one?'
    ];
    const SHOW_COMMENTS_ON_LESSON = [
        'type' => Config::FIELD_BOOL,
        'isActive' => true,
        'label' => 'Comments: Show on Lessons',
        'help' => 'Do you want to show comments at the bottom on a lesson page, and allow visitors to write one?'
    ];
    const SHOW_COMMENTS_ON_POST = [
        'type' => Config::FIELD_BOOL,
        'isActive' => true,
        'label' => 'Comments: Show on Posts',
        'help' => 'Do you want to show comments at the bottom on a post page, and allow visitors to write one?'
    ];

    const PROJECTS_PER_PAGE = [
        'type' => Config::FIELD_NUM,
        'value' => 6,
        'isRequired' => true,
        'label' => 'Collection: Projects per page',
        'help' => 'How many projects do you want to display per collection page?'
    ];
    const LESSONS_PER_PAGE = [
        'type' => Config::FIELD_NUM,
        'value' => 6,
        'isRequired' => true,
        'label' => 'Collection: Lessons per page',
        'help' => 'How many lessons do you want to display per collection page?'
    ];
    const POSTS_PER_PAGE = [
        'type' => Config::FIELD_NUM,
        'value' => 6,
        'isRequired' => true,
        'label' => 'Collection: posts per page',
        'help' => 'How many posts do you want to display per collection page?'
    ];

    const HOME_PROJECTS = [
        'type' => Config::FIELD_NUM,
        'value' => 3,
        'isRequired' => true,
        'label' => 'Homepage: Projects to show',
        'help' => 'How many projects do you want to display in the "Last projects" section on homepage?'
    ];
    const HOME_FEATURED_PROJECTS = [
        'type' => Config::FIELD_NUM,
        'value' => 9,
        'isRequired' => true,
        'label' => 'Homepage: Featured projects to show',
        'help' => 'How many projects do you want to display in the "Featured projects" section on homepage?'
    ];
    const HOME_LESSONS = [
        'type' => Config::FIELD_NUM,
        'value' => 3,
        'isRequired' => true,
        'label' => 'Homepage: lessons to show',
        'help' => 'How many lessons do you want to display in the "Last lessons" section on homepage?'
    ];
    const HOME_POSTS = [
        'type' => Config::FIELD_NUM,
        'value' => 3,
        'isRequired' => true,
        'label' => 'Homepage: Posts to show',
        'help' => 'How many posts do you want to display in the "Last posts" section on homepage?'
    ];

    public static function getGroups(): array
    {
        return ['prod', 'dev'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->runAndPersistAll([
            'createAdminOptions'
        ]);
        $manager->flush();
    }

    /**
     * Retrieve a specific value of an option.
     * @param array|string $constant The option's name (like 'SITE_NAME', 'SITE_LOGO', 'CONTACT_PHONE', etc.)
     * @param string $index The index in the array (like 'type', 'value', 'label', 'help', etc.)
     * @return null|string|int The index's value. Or null if the index doesn't exist for this option
     */
    public static function get($optionName, string $index)
    {
        $optionData = constant('self::' . $optionName);
        return $optionData[$index] ?? null;
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
}
