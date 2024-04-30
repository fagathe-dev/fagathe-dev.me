<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AdminLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'auth_login';
    public const TARGET_PATH = 'admin_default';
    private $flashes;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
    ) {
        $this->flashes = (new Session)->getFlashBag();
    }

    /**
     * @param Request $request
     * 
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') && $request->getPathInfo() === $this->getLoginUrl($request);
    }

    /**
     * @param Request $request
     * 
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');
        $password = $request->request->get('password', '');
        $rememberMe = $request->request->get('_remember_me', '');
        $csrfToken = $request->request->get('_csrf_token', '');

        $badges = [new CsrfTokenBadge('authenticate', $csrfToken)];

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);
        
        if ($rememberMe && $rememberMe === "true") {
            $badges = [...$badges, (new RememberMeBadge)->enable()];
        }

        return new Passport(
            new UserBadge($username, fn (string $identifier) => $this->userRepository->findUserByEmailOrUsername($identifier)),
            new PasswordCredentials($password),
            $badges
        );
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * 
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate(self::TARGET_PATH));
    }

    /**
     * @param Request $request
     * 
     * @return string
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
