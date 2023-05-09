<?php

namespace App\Controller;

use App\Entity\Seances;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/calendar', name: 'app_calendar')]
class CalendarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'app_calendar_index')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

    #[Route('/events', name: 'app_calendar_events')]
    public function events(Request $request): JsonResponse
    {
        $seances = $this->entityManager->getRepository(Seances::class)->findAll();
        $events = [];
    
        foreach ($seances as $s) {
            $event = [
                // 'id' => $s->getId(),
                // 'title' => $s->getLibelle()." ".$s->getDate()->format('Y-m-d'),
                // 'start' => $s->getDate()->format('Y-m-d').'T'.$s->getHeureDebut()->format('H:i:s'),
                // 'end'=> $s->getDate()->format('Y-m-d').'T'.$s->getHeureFin()->format('H:i:s')
                'id' => $s->getId(),
                'title' => $s->getLibelle()." ".$s->getDate()->format('Y-m-d'),
                'start' => $s->getDate()->format('Y-m-d'),
                'end'=> $s->getDate()->format('Y-m-d'),
                'startTime' => $s->getHeureDebut()->format('H:i'),
                'endTime' => $s->getHeureFin()->format('H:i'),
                // 'color' => $s->getCoursId()->getUtilisateur()->getColor(),
                

                
            ];
            array_push($events, $event);
        }
    
        $response = new JsonResponse($events);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }





	/**
	 * @return mixed
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}
	
	/**
	 * @param mixed $entityManager 
	 * @return self
	 */
	public function setEntityManager($entityManager): self {
		$this->entityManager = $entityManager;
		return $this;
	}
}