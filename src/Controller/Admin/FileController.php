<?php

namespace App\Controller\Admin;

use App\Service\CKFinderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class FileController extends AbstractDashboardController
{
    private $cKFinderService;

    public function __construct(CKFinderService $cKFinderService)
    {
        $this->cKFinderService = $cKFinderService;
    }
  
    /**
     * @Route("/admin/files/purge", name="admin_files_purge")
     */
    public function purge(Request $request): Response
    {
        $this->cKFinderService->purgeLocalFiles();
        return $this->redirect($request->headers->get('referer'));
    }
}
