<?php declare(strict_types=1);

namespace App\Controller;

use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin/mailer")
 */
class BackofficeMailManagerController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_mailer_manager_index", methods={"GET"})
     */
    public function index(ReservationRepositoryInterface $repository): Response
    {
        return $this->render('backoffice/mail-manager/index.html.twig', [//'backoffice_reservations' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/send-example/tanks-mail", name="backoffice_mailer_manager_example_send_tanks_mail", methods={"GET"})
     */
    public function delete(Request $request, UserInterface $user, MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
            ->from(new Address('memu.system@medicalmundi.com'))
            ->to(new Address($user->getUsername()))
            ->subject('Oberdan 8 - Grazie!!')
            ->htmlTemplate('@booking/email/for-clients/tanks_mail.html.twig')
            ->context([
                'firstName' => 'Mario',
                'lastName' => 'Rossi',
            ])
        ;

        $mailer->send($email);

        $this->addFlash('success', 'Email template inviato all\'indirizzo: ' . $user->getUsername());

        return $this->redirectToRoute('backoffice_mailer_manager_index');
    }
}
