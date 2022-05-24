<?php

namespace App\Controller;

use App\Service\PdfService;
use App\Form\LocalizationType;
use App\Form\MailType;
use App\Service\LocalizationService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"get", "post"})
     */
    public function homepage(Request $request,
     LocalizationService $localizationService,
      MailerInterface $mailer
      ): Response
    {
        //Gestion du formulaire concernant la selection d'un zip et d'une ville
        $lat ='48.856614'; 
        $long = '2.3522219';
        $city ='Paris';

        $form = $this->createForm(LocalizationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $zip = $form->get('zip')->getData();
            $city = $form->get('city')->getData();
            $coordinates = $localizationService->getGPSCoordinates($zip, $city);
            if ($coordinates) {
                $lat = ($coordinates['latitude']);
                $long = ($coordinates['longitude']);
            }
        }

        //Formulaire concernant l'envoie d'un mail
        $mailForm = $this->createForm(MailType::class);
        $mailForm->handleRequest($request);
        if ($mailForm->isSubmitted() && $mailForm->isValid()) {
            $mark = htmlspecialchars($mailForm->get('mark')->getData());
            $lat = $mailForm->get('lat')->getData();
            $long = $mailForm->get('long')->getData();
            $city = htmlspecialchars($mailForm->get('city')->getData());
            $from = htmlspecialchars($mailForm->get('mailAddress')->getData());
            $email = (new TemplatedEmail())
            ->from($from)
            ->to('you@example.com')
            ->subject('Votre carte')
            ->htmlTemplate('email/template.html.twig')
            ->context([
                'mark' => $mark,
                'lat' => $lat,
                'long' => $long,
                'city' => ucfirst(strtolower($city)),
            ]);
    
            $mailer->send($email);
            
            return $this->redirectToRoute('homepage');
        }

        //Afficher la page
        return $this->render('default/homepage.html.twig', [
            'form' => $form->createView(),
            'lat' => $lat,
            'long' => $long,
            'city' => $city,
            'mailForm' => $mailForm->createView()
        ]);
    }

    /**
     * @Route("/pdf/{lat}/{long}/{city}", name="toPdf", methods={"get"})
     */
    public function generatePdf(PdfService $pdfService, string $lat, string $long, string $city): Response
    {
        $lat = str_replace('a', '.', $lat);
        $long = str_replace('a', '.', $long);
        $city = ucfirst(urldecode($city));
        $content = "Ville : $city avec comme latitude : $lat et longitude $long";        
        $pdfService->showPdfFile($content);

        return $this->redirectToRoute('homepage');
    }

}
