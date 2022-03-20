<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;
    private $security;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Security $security
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function handle(
        Request $request,
        AccessDeniedException $accessDeniedException
    ): ?Response {
        /** @var User $user */
        $user = $this->security->getUser();
        return new RedirectResponse(
            $this->urlGenerator->generate('user_show', ['id' => $user->getId()])
        );
    }
}
