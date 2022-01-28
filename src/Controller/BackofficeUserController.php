<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\BackofficeUser;
use App\Form\BackofficeUserType;
use App\Repository\BackofficeUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class BackofficeUserController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_user_index", methods={"GET"})
     */
    public function index(BackofficeUserRepository $backofficeUserRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'backoffice_users' => $backofficeUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $backofficeUser = new BackofficeUser();
        $form = $this->createForm(BackofficeUserType::class, $backofficeUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $backofficeUser->setPassword(
                $passwordEncoder->encodePassword(
                    $backofficeUser,
                    $form->get('plainPassword')->getData()
                )
            );
            $backofficeUser->setRoles(['ROLE_ADMIN']);
            $backofficeUser->setIsVerified(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($backofficeUser);
            $entityManager->flush();

            return $this->redirectToRoute('backoffice_user_index');
        }

        return $this->render('backoffice/user/new.html.twig', [
            'backoffice_user' => $backofficeUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_user_show", methods={"GET"})
     */
    public function show(BackofficeUser $backofficeUser): Response
    {
        return $this->render('backoffice/user/show.html.twig', [
            'backoffice_user' => $backofficeUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, BackofficeUser $backofficeUser): Response
    {
        $form = $this->createForm(BackofficeUserType::class, $backofficeUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $backofficeUser->setPassword(
                $passwordEncoder->encodePassword(
                    $backofficeUser,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_user_index');
        }

        return $this->render('backoffice/user/edit.html.twig', [
            'backoffice_user' => $backofficeUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_user_delete", methods={"POST"})
     */
    public function delete(Request $request, BackofficeUser $backofficeUser): Response
    {
        if ($this->isCsrfTokenValid('delete' . $backofficeUser->getId(), (string) $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($backofficeUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backoffice_user_index');
    }
}
