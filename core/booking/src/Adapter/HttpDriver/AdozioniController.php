<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Infrastructure\Framework\Form\AdozioniReservationType;
use LeanpubBookClub\Application\UpdateSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdozioniController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(AdozioniReservationType::class);

        $form->handleRequest($request);

        //var_dump($request->files);

        if ($form->isSubmitted() && $form->isValid()) {

############################ inizio single file upload
            /** @var UploadedFile $adozioniFile */
            $adozioniFile = $form->get('adozioni')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($adozioniFile) {
                $originalFilename = pathinfo($adozioniFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $adozioniFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $adozioniFile->move(
                        $this->getParameter('adozioni_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $e;
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                //$product->setBrochureFilename($newFilename);
            }
            var_dump($adozioniFile);

            die;
            ############################ fine single file upload
            $formData = $form->getData();

            var_dump($formData);
            die;

            ############################ Execute Application/domain logic here

//            $this->application->updateSession(
//                new UpdateSession($sessionId, $formData['description'], $formData['urlForCall'])
//            );
            ############################ Execute Application/domain logic here

            return $this->redirectToRoute('home_oberdan');
        }

        return $this->render('@booking/Static/reservation-adozioni.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
