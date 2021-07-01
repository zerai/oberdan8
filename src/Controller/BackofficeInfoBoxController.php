<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\InfoBox;
use App\Form\InfoBoxType;
use App\Repository\InfoBoxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/info-box")
 */
class BackofficeInfoBoxController extends AbstractController
{
    /**
     * @Route("/manager", name="backoffice_info_box_manager", methods={"GET"})
     * @Route("/", name="backoffice_info_box_index", methods={"GET"})
     */
    public function manager(InfoBoxRepository $infoBoxRepository): Response
    {
        $defaultInfoBox = $infoBoxRepository->findDefaultInfoBox();
        //dd($defaultInfoBox);
        return $this->render('backoffice/info-box/manager.html.twig', [
            //            'info_box' => $infoBoxRepository->findOneBy(['active' => true]),
            'info_box' => $infoBoxRepository->findDefaultInfoBox(),
        ]);
    }

    /**
     * @Route("/new_embedded", name="backoffice_info_box_new_embedded", methods={"GET","POST"})
     */
    public function newEmbedded(Request $request): Response
    {
        $infoBox = new InfoBox();
        $form = $this->createForm(InfoBoxType::class, $infoBox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoBox->setDefaultBox(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($infoBox);
            $entityManager->flush();

            return $this->redirectToRoute('backoffice_info_box_manager');
        }

        return $this->render('backoffice/info-box/_new_embedded.html.twig', [
            'info_box' => $infoBox,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit_embedded", name="backoffice_info_box_edit_embedded", methods={"GET","POST"})
     */
    public function editEmbedded(Request $request, InfoBox $infoBox): Response
    {
        $form = $this->createForm(InfoBoxType::class, $infoBox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_info_box_manager');
        }

        return $this->render('backoffice/info-box/_edit_embedded.html.twig', [
            'info_box' => $infoBox,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="backoffice_info_box_reset", methods={"POST"})
//     */
//    public function reset(Request $request, InfoBox $infoBox): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$infoBox->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($infoBox);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('backoffice_info_box_manager');
//    }


//    /**
//     * @Route("/", name="backoffice_info_box_index", methods={"GET"})
//     */
//    public function index(InfoBoxRepository $infoBoxRepository): Response
//    {
//        return $this->render('backoffice/info-box/index.html.twig', [
//            'info_boxes' => $infoBoxRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="backoffice_info_box_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $infoBox = new InfoBox();
        $form = $this->createForm(InfoBoxType::class, $infoBox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoBox->setDefaultBox(true);

            $entityManager = $this->getDoctrine()->getManager();

            //delete all info-box

            $entityManager->createQuery(
                'DELETE FROM App\Entity\InfoBox '
            )->execute();

            $entityManager->persist($infoBox);
            $entityManager->flush();

            return $this->redirectToRoute('backoffice_info_box_manager');
        }

        return $this->render('backoffice/info-box/new.html.twig', [
            'info_box' => $infoBox,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="backoffice_info_box_show", methods={"GET"})
//     */
//    public function show(InfoBox $infoBox): Response
//    {
//        return $this->render('backoffice/info-box/show.html.twig', [
//            'info_box' => $infoBox,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="backoffice_info_box_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InfoBox $infoBox): Response
    {
        $form = $this->createForm(InfoBoxType::class, $infoBox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //return $this->redirectToRoute('backoffice_info_box_index');
            return $this->redirectToRoute('backoffice_info_box_manager');
        }

        return $this->render('backoffice/info-box/edit.html.twig', [
            'info_box' => $infoBox,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_info_box_delete", methods={"POST"})
     */
    public function delete(Request $request, InfoBox $infoBox): Response
    {
        if ($this->isCsrfTokenValid('delete' . $infoBox->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($infoBox);
            $entityManager->flush();
        }

        //return $this->redirectToRoute('backoffice_info_box_index');
        return $this->redirectToRoute('backoffice_info_box_manager');
    }
}
