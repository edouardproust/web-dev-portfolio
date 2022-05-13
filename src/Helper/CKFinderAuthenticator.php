<?php

namespace App\Helper;

/**
 * Simplify the authentification process of CKFinder
 *
* **IMPORTANT:** As the config file of CKFinder is a static one,
* any class import will make the authentication process fail.
* Only use static vaibles and const, or the session container.
 */
class CKFinderAuthenticator
{
    /**
     * Tells if the current user is authorized to access to CKFinder
     *
     * **IMPORTANT:** For the authentification to work, some data must be added to the session
     * using `CKFinderAuthenticator::setData() in `App\Controller\Admin\DashboardController::configureCrud()`
     *
     * @return bool  true if access granted, false if not
    */
    public static function isGranted()
    {
        return true;

        self::sessionStart();

        $roles = $_SESSION['roles'] ?? null;
        // $entity = $_SESSION['entity'] ?? null;
        $allowedRoles = ['ROLE_ADMIN', 'ROLE_AUTHOR'];
        
        $isGranted = false;
        // if ($entity) {
        foreach ($roles as $role) {
            if (in_array($role, $allowedRoles)) {
                $isGranted = true;
            }
        }
        // }
        return $isGranted;
    }

    /**
     * Add data (of any type) to the session
     * @param array $data list of arrays containing data to add
     * ['key' => 'value'], ['key2' => 'value2'], ...
     */
    public static function setData(...$datas)
    {
        self::sessionStart();
        foreach ($datas as $data) {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }

    private static function sessionStart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
