<?php

namespace App\Controller\Admin;

use App\Service\CKFinderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class FileController extends AbstractDashboardController
{
    private $cKFinderService;
    private $urlGenerator;
    private $adminContext;

    public function __construct(
        CKFinderService $cKFinderService,
        AdminUrlGenerator $urlGenerator,
        AdminContextProvider $adminContext
    ) {
        $this->cKFinderService = $cKFinderService;
        $this->urlGenerator = $urlGenerator;
        $this->adminContext = $adminContext;
    }
    private $easyAdminService;
  
    /**
     * @Route("/admin/files/purge", name="admin_files_purge")
     */
    public function purge(Request $request): Response
    {
        $this->cKFinderService->purgeLocalFiles();
        return $this->redirect($request->headers->get('referer'));
    }
}
