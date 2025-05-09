<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\InfoBox;
use App\Form\InfoBoxType;
use App\Repository\InfoBoxRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        return $this->render('backoffice/info-box/manager.html.twig', [
            'info_box' => $infoBoxRepository->findDefaultInfoBox(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_info_box_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $infoBox = new InfoBox();
        $form = $this->createForm(InfoBoxType::class, $infoBox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoBox->setDefaultBox(true);

            //delete all info-box

            $entityManager->createQuery(
                'DELETE FROM App\Entity\InfoBox '
            )->execute();

            $entityManager->persist($infoBox);
            $entityManager->flush();

            $this->addFlash('success', 'Nuovo Info box creato.');

            return $this->redirectToRoute('backoffice_info_box_manager');
        }

        return $this->render('backoffice/info-box/new.html.twig', [
            'info_box' => $infoBox,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_info_box_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InfoBox $infoBox, EntityManagerInterface $entityManager): Response
    {
        /**
         * TODO: add func. test
         */
        $form = $this->createForm(InfoBoxType::class, $infoBox);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * TODO: add try/catch block
             */
            $entityManager->flush();

            $this->addFlash('success', 'Info box modificato.');

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
    public function delete(Request $request, InfoBox $infoBox, EntityManagerInterface $entityManager): Response
    {
        /**
         * TODO: add func test
         */
        if ($this->isCsrfTokenValid('delete' . (string) $infoBox->getId(), (string) $request->request->get('_token'))) {
            /**
             * TODO: add try/catch block
             */
            $entityManager->remove($infoBox);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Info box eliminato.');

        return $this->redirectToRoute('backoffice_info_box_manager');
    }
}
