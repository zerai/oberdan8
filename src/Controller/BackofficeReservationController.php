<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\BackofficeUser;
use App\Form\BackofficeUserType;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/prenotazioni")
 */
class BackofficeReservationController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_reservation_index", methods={"GET"})
     */
    public function index(ReservationRepositoryInterface $repository): Response
    {
        return $this->render('backoffice/reservation/index.html.twig', [
            //'backoffice_reservations' => $repository->findAll(),
            'backoffice_reservations' => $repository->findAllForBackoffice(),
        ]);
    }

//
//    /**
//     * @Route("/new", name="backoffice_user_new", methods={"GET","POST"})
//     */
//    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
//    {
//        $backofficeUser = new BackofficeUser();
//        $form = $this->createForm(BackofficeUserType::class, $backofficeUser);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // encode the plain password
//            $backofficeUser->setPassword(
//                $passwordEncoder->encodePassword(
//                    $backofficeUser,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//            $backofficeUser->setIsVerified(true);
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($backofficeUser);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('backoffice_user_index');
//        }
//
//        return $this->render('backoffice/user/new.html.twig', [
//            'backoffice_user' => $backofficeUser,
//            'form' => $form->createView(),
//        ]);
//    }
//

    /**
     * @Route("/{id}", name="backoffice_reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('backoffice/reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

//
//    /**
//     * @Route("/{id}/edit", name="backoffice_user_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, BackofficeUser $backofficeUser): Response
//    {
//        $form = $this->createForm(BackofficeUserType::class, $backofficeUser);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // encode the plain password
//            $backofficeUser->setPassword(
//                $passwordEncoder->encodePassword(
//                    $backofficeUser,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('backoffice_user_index');
//        }
//
//        return $this->render('backoffice/user/edit.html.twig', [
//            'backoffice_user' => $backofficeUser,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="backoffice_user_delete", methods={"POST"})
//     */
//    public function delete(Request $request, BackofficeUser $backofficeUser): Response
//    {
//        if ($this->isCsrfTokenValid('delete' . $backofficeUser->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($backofficeUser);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('backoffice_user_index');
//    }

    /**
     * @Route("/{id}", name="backoffice_reservation_send_tanks_mail", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('send_tanks_mail' . $reservation->getId()->toString(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->remove($backofficeUser);
            //$entityManager->flush();

            $this->addFlash('success', 'E\' stata inviata la mail di ringraziamento .');
        } else {
            $this->addFlash('danger', 'La mail di ringraziamento non è stata inviata.');
        }

        return $this->redirectToRoute('backoffice_reservation_index');
    }
}
