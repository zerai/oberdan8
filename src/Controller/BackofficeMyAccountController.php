<?php declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeMyAccountController extends AbstractController

{
    /**
     * @Route("/admin/my-account", name="backoffice_my_account")
     */
    public function index(LoggerInterface $logger)
    {
        $logger->debug('Checking account page for ' . $this->getUser()->getEmail());
        return $this->render('backoffice/my-account/index.html.twig', [
            'account' => $this->getUser()
        ]);
    }

//    /**
//     * @Route("/api/account", name="api_account")
//     */
//    public function accountApi()
//    {
//        $user = $this->getUser();
//
//        return $this->json($user, 200, [], [
//            'groups' => ['main'],
//        ]);
//    }
}
