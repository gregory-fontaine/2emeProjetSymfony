<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Form\PresenceType;
use App\Repository\CoursRepository;
use App\Repository\SeancesRepository;
use App\Repository\PresenceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/presence')]
#[IsGranted('ROLE_USER')]
class PresenceController extends AbstractController
{
    /**
     * Fonction pour afficher la liste des cours
     *
     * @param CoursRepository $coursRepository
     * @return Response
     */
    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('presence/listeCours.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }
    
    /**
     * fonction pour afficher la liste des séances d'un cours en particulier
     *
     * @param PresenceRepository $presenceRepository
     * @return Response
     */
    
    #[Route('/list/{id}', name: 'app_cours/seances_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function indexSeance(int $id, SeancesRepository $seancesRepository): Response
    {
        //On veut les séances du cours dont l'id est dans l'url
        $seances = $seancesRepository->findBy(['cours_id' => $id]);       

        //on envoie le nouveau tableau au front
        return $this->render('presence/listeSeances.html.twig', [
            'seances' => $seances,
        ]);
    }

    /**
     * fonction pour afficher la liste des présences d'une séance en particuliers
     */
    #[Route('/seances/{id}', name: 'app_seances/presences_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function indexPresence(int $id, PresenceRepository $presenceRepository): Response
    {
        //On veut les séances du cours dont l'id est dans l'url
        $presences = $presenceRepository->findBy(['seances' => $id]);       

        //on envoie le nouveau tableau au front
        return $this->render('presence/listPresences.html.twig', [
            'presences' => $presences,
        ]);
    }

    //  /**
    //  * fonction pour afficher la liste des inscrits à un cours particulier
    //  *
    //  * @param PresenceRepository $presenceRepository
    //  * @return Response
    //  */
    
    //  #[Route('/list/{id}', name: 'app_presence_list', methods: ['GET'])]
    //  #[IsGranted('ROLE_USER')]
    //  public function indexPresence(PresenceRepository $presenceRepository, int $id, SeancesRepository $seancesRepository): Response
    //  {
    //      //On veut les séances du cours dont l'id est dans l'url
    //      $seances = $seancesRepository->findBy(['cours_id' => $id]);
 
    //      //On va chercher les présences parmis cette sélection de séances
    //      $presences = [];
    //      foreach ($seances as $seance) {
    //          $seancePresences = $presenceRepository->findBy(['seances' => $seance->getId()]);
    //          $presences = array_merge($presences, $seancePresences);
    //      }
 
    //      //on envoie le nouveau tableau au front
    //      return $this->render('presence/index.html.twig', [
    //          'presences' => $presences,
    //      ]);
    //  }

    // #[Route('/new', name: 'app_presence_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, PresenceRepository $presenceRepository): Response
    // {
    //     $presence = new Presence();
    //     $form = $this->createForm(PresenceType::class, $presence);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $presenceRepository->save($presence, true);

    //         return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('presence/new.html.twig', [
    //         'presence' => $presence,
    //         'form' => $form,
    //     ]);
    // }

    // /**
    //  * Fonction pour voir un objet présence
    //  */
    // #[Route('/{id}', name: 'app_presence_show', methods: ['GET'])]
    // public function show(Presence $presence): Response
    // {
    //     return $this->render('presence/show.html.twig', [
    //         'presence' => $presence,
    //     ]);
    // }

    /**
     * Fonction pour Modifier un objet présence
     */
    #[Route('/{id}/edit', name: 'app_presence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presence $presence, PresenceRepository $presenceRepository): Response
    {
        //idSeance correspond à l'id de la séance à laquel l'objet présence est associé, utile pour rediriger
        $idSeance = $presence->getSeances()->getId();

        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presenceRepository->save($presence, true);

            return $this->redirectToRoute('app_seances/presences_list', ['id'=>$idSeance], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('presence/edit.html.twig', [
            'presence' => $presence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presence_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Presence $presence, PresenceRepository $presenceRepository): Response
    {

        //idSeance correspond à l'id de la séance à laquel l'objet présence est associé, utile pour rediriger
        $idSeance = $presence->getSeances()->getId();

        if ($this->isCsrfTokenValid('delete'.$presence->getId(), $request->request->get('_token'))) {
            $presenceRepository->remove($presence, true);
        }

        return $this->redirectToRoute('app_seances/presences_list', ['id'=>$idSeance], Response::HTTP_SEE_OTHER);
    }
}
