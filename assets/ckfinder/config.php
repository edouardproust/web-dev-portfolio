<?php

/*
 * CKFinder Configuration File
 *
 * For the official documentation visit https://ckeditor.com/docs/ckfinder/ckfinder3-php/
 */

/*============================ PHP Error Reporting ====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/debugging.html

// Production

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/*============================ General Settings =======================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config = array();

/*============================ Enable PHP Connector HERE ==============================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_authentication

$config['authentication'] = function () {
    require(dirname(__DIR__, 3) . '/src/Helper/CKFinderAuthenticator.php');
    return App\Helper\CKFinderAuthenticator::isGranted();
};

/*============================ License Key ============================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_licenseKey

$config['licenseName'] = '';
$config['licenseKey']  = '';

/*============================ CKFinder Internal Directory ============================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_privateDir

$config['privateDir'] = array(
    'backend' => 'default',
    'tags'   => '.ckfinder/tags',
    'logs'   => '.ckfinder/logs',
    'cache'  => '.ckfinder/cache',
    'thumbs' => '.ckfinder/cache/thumbs',
);

/*============================ Images and Thumbnails ==================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_images

$config['images'] = array(
    'maxWidth'  => 1600,
    'maxHeight' => 1200,
    'quality'   => 80,
    'sizes' => array(
        'small'  => array('width' => 480, 'height' => 320, 'quality' => 80),
        'medium' => array('width' => 600, 'height' => 480, 'quality' => 80),
        'large'  => array('width' => 800, 'height' => 600, 'quality' => 80)
    )
);

/*=================================== Backends ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_backends

$config['backends'][] = array(
    'name'         => 'default',
    'adapter'      => 'local',
    'baseUrl'      => '/uploads/ckfinder',
//  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
    'chmodFiles'   => 0777,
    'chmodFolders' => 0755,
    'filesystemEncoding' => 'UTF-8',
);

/*================================ Resource Types =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_resourceTypes

$config['defaultResourceTypes'] = 'Images, Scripts, Documents, Videos, Audio clips';

$config['resourceTypes'][] = array(
    'name'              => 'Images',
    'directory'         => 'images',
    'maxSize'           => '800K',
    'allowedExtensions' => 'ico,webp,bmp,gif,jpeg,jpg,png,svg,tif,tiff',
    'backend'           => 'default'
);
$config['resourceTypes'][] = array(
    'name'              => 'Scripts',
    'directory'         => 'scripts',
    'maxSize'           => '10M',
    'allowedExtensions' => 'htm,html,css,js,php,sql,txt,phps',
    'backend'           => 'default'
);

// More extensions (activate them with the 'defaultResourceTypes' option above)

$config['resourceTypes'][] = array(
    'name'              => 'Documents', // Single quotes not allowed.
    'directory'         => 'documents',
    'maxSize'           => '800K',
    'allowedExtensions' => 'csv,doc,docx,odt,ods,pdf,xls,xlsx',
    'backend'           => 'default'
);
$config['resourceTypes'][] = array(
    'name'              => 'Videos',
    'directory'         => 'videos',
    'maxSize'           => '4M',
    'allowedExtensions' => 'mp4,webm',
    'backend'           => 'default'
);
$config['resourceTypes'][] = array(
    'name'              => 'Audio clips',
    'directory'         => 'audio-clips',
    'maxSize'           => '2M',
    'allowedExtensions' => 'mid,mp3,wav',
    'backend'           => 'default'
);
$config['resourceTypes'][] = array(
    'name'              => 'Archives',
    'directory'         => 'archives',
    'maxSize'           => '4M',
    'allowedExtensions' => 'rar,zip,7z,gz,gzip,rar,tar,tgz',
    'backend'           => 'default'
);

/*================================ Access Control =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

$config['roleSessionVar'] = 'CKFinder_UserRole';

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl
$config['accessControl'][] = array(
    'role'                => '*',
    'resourceType'        => '*',
    'folder'              => '/',

    'FOLDER_VIEW'         => true,
    'FOLDER_CREATE'       => true,
    'FOLDER_RENAME'       => true,
    'FOLDER_DELETE'       => true,

    'FILE_VIEW'           => true,
    'FILE_CREATE'         => true,
    'FILE_RENAME'         => true,
    'FILE_DELETE'         => false,

    'IMAGE_RESIZE'        => true,
    'IMAGE_RESIZE_CUSTOM' => true
);


/*================================ Other Settings =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config['overwriteOnUpload'] = false;
$config['checkDoubleExtension'] = true;
$config['disallowUnsafeCharacters'] = false;
$config['secureImageUploads'] = true;
$config['checkSizeAfterScaling'] = true;
$config['htmlExtensions'] = array('html', 'htm', 'xml', 'js');
$config['hideFolders'] = array('.*', 'CVS', '__thumbs');
$config['hideFiles'] = array('.*');
$config['forceAscii'] = false;
$config['xSendfile'] = false;

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_debug
$config['debug'] = true;

/*==================================== Plugins ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_plugins

$config['pluginsDirectory'] = __DIR__ . '/plugins';
$config['plugins'] = array();

/*================================ Cache settings =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_cache

$config['cache'] = array(
    'imagePreview' => 24 * 3600,
    'thumbnails'   => 24 * 3600 * 365,
    'proxyCommand' => 0
);

/*============================ Temp Directory settings ================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_tempDirectory

$config['tempDirectory'] = sys_get_temp_dir();

/*============================ Session Cause Performance Issues =======================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

$config['sessionWriteClose'] = true;

/*================================= CSRF protection ===================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_csrfProtection

$config['csrfProtection'] = true;

/*===================================== Headers =======================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_headers

$config['headers'] = array();

/*============================== End of Configuration =================================*/

// Config must be returned - do not change it.
return $config;
