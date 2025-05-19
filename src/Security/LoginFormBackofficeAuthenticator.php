<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\BackofficeUser;
use App\Repository\BackofficeUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormBackofficeAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private BackofficeUserRepository $backofficeUserRepository;

    private RouterInterface $router;

    private EntityManagerInterface $entityManager;

    /**
     * LoginFormBackofficeAuthenticator constructor.
     */
    public function __construct(BackofficeUserRepository $backofficeUserRepository, RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->backofficeUserRepository = $backofficeUserRepository;
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('backoffice_dashboard'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('backoffice_login');
    }

    public function authenticate(Request $request): Passport
    {
        // TODO: Implement authenticate() method.
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                // optionally pass a callback to load the User manually
                $user = $this->entityManager
                    ->getRepository(BackofficeUser::class)
                    ->findOneBy([
                        'email' => $userIdentifier,
                    ]);
                if (! $user) {
                    throw new UserNotFoundException();
                }
                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge(
                    'authenticate',
                    $request->request->get('_csrf_token')
                ),
                (new RememberMeBadge())->enable(),
            ]
        );
    }
}
