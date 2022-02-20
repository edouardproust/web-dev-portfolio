<?php

namespace App\Service;

use App\Repository\AdminOptionRepository;

class AdminOptionService
{

    private $adminOptionRepository;

    public function __construct(AdminOptionRepository $adminOptionRepository)
    {
        $this->adminOptionRepository = $adminOptionRepository;
    }

    /**
     * Get the value of an AdminOption stored in database
     * @param string $constant 'constant' field of the admin_option table in database'
     * @return string 
     */
    public function get(string $constant)
    {
        return $this->adminOptionRepository
            ->findOneByConstant($constant)
            ->getValue();
    }
}
