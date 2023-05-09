<?php

namespace App\Controller;

use App\Entity\Seances;

use CalendarBundle\Entity\Event;
use Doctrine\Migrations\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    #[Route('/api/{id}/edit', name: 'api_event_edit', methods: ['PUT'])]
    public function majEvent(?Event $event, Request $request, EntityManagerInterface $manager): Response
    {
        $donnees = json_decode($request->getContent());

        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) 
            // isset($donnees->startTime) && !empty($donnees->startTime) &&
            // isset($donnees->endTime) && !empty($donnees->endTime) 
            // isset($donnees->eventColor) && !empty($donnees->eventColor)

        ) {

            // les donnes sont completes
            $code = 200;

            // on verifie si id existe
            if (!$event) {
                $event = new Events;
                $code = 201;
            }
            // on hydrate l'objet avec donnees
            $event->setTitle($donnees->title);
            $event->setStart(new \DateTime($donnees->start));
            $event->setEnd(new \DateTime($donnees->end));
            // $event->setStartTime(new \DateTime($donnees->startTime));
            // $event->setEndTime($donnees->endTime);
            // $event->setColor($donnees->Color);

            // sauvegarder l'objet Event dans la base de données getDoctrine()->getManager();
           
            $manager->persist($event);
            $manager->flush();

            return new Response('ok', $code);

        } else {
            // les donnees sont incompletes
            return new Response('donnees incompletes', 404);
        }

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
