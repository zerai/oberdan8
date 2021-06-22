<?php

namespace App\Controller;

use App\Entity\BackofficeUser;
use App\Form\BackofficeUserType;
use App\Repository\BackofficeUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/user")
 */
class BackofficeUserController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_user_index", methods={"GET"})
     */
    public function index(BackofficeUserRepository $backofficeUserRepository): Response
    {
        return $this->render('backoffice_user/index.html.twig', [
            'backoffice_users' => $backofficeUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $backofficeUser = new BackofficeUser();
        $form = $this->createForm(BackofficeUserType::class, $backofficeUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($backofficeUser);
            $entityManager->flush();

            return $this->redirectToRoute('backoffice_user_index');
        }

        return $this->render('backoffice_user/new.html.twig', [
            'backoffice_user' => $backofficeUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_user_show", methods={"GET"})
     */
    public function show(BackofficeUser $backofficeUser): Response
    {
        return $this->render('backoffice_user/show.html.twig', [
            'backoffice_user' => $backofficeUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BackofficeUser $backofficeUser): Response
    {
        $form = $this->createForm(BackofficeUserType::class, $backofficeUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_user_index');
        }

        return $this->render('backoffice_user/edit.html.twig', [
            'backoffice_user' => $backofficeUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_user_delete", methods={"POST"})
     */
    public function delete(Request $request, BackofficeUser $backofficeUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$backofficeUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($backofficeUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backoffice_user_index');
    }
}
