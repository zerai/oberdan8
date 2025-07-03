<?php declare(strict_types=1);

namespace App\Controller;

use App\Form\BackofficeUserChangePasswordType;
use App\Repository\BackofficeUserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeMyAccountController extends AbstractController
{
    #[Route(path: '/admin/my-account', name: 'backoffice_my_account')]
    public function index(LoggerInterface $logger): Response
    {
        $logger->debug('Checking account page for ' . $this->getUser()->getEmail());

        $user = $this->getUser();

        $form = $this->createForm(BackofficeUserChangePasswordType::class, $user);
        return $this->render('backoffice/my-account/index.html.twig', [
            'account' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/admin/my-account/change-password', name: 'backoffice_my_account_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, BackofficeUserRepository $backofficeUserRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(BackofficeUserChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData();

            $backofficeUser = $backofficeUserRepository->findOneBy([
                'email' => $user->getUserIdentifier(),
            ]);

            $backofficeUser->setPassword(
                $passwordHasher->hashPassword(
                    $backofficeUser,
                    $newPassword
                )
            );

            $backofficeUserRepository->save($backofficeUser);

            $this->addFlash('success', 'La tua password è stata modificata!');

            return $this->redirectToRoute('backoffice_my_account');
        }

        return $this->render('backoffice/my-account/change-password.html.twig', [
            'account' => $user,
            'form' => $form->createView(),
        ]);
    }
}
