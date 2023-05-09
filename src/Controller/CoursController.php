<?php

namespace App\Controller;

use App\Entity\Seances;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cours')]
#[IsGranted('ROLE_USER')]
class CoursController extends AbstractController
{

    /**
     * fonction pour afficher la liste des cours
     *
     * @param CoursRepository $coursRepository
     * @return Response
     */
    #[Route('/', name: 'app_cours_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    /**
     * fonction pour créer un cours
     *
     * @param Request $request
     * @param CoursRepository $coursRepository
     * @return Response
     */
    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, CoursRepository $coursRepository, EntityManagerInterface $entityManager): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des dates de début et de fin de la période sélectionnée dans le formulaire
            $dateDebut = $form->get('date_debut')->getData();
            $dateFin = $form->get('date_fin')->getData();
            $heureDebut = $form->get('heure_debut')->getData();
            $heureFin = $form->get('heure_fin')->getData();
            $libelle = $form->get('libelle')->getData();
            $description = $form->get('description')->getData();
            $jour = $form->get('date_hebdomadaire')->getData();


// Récupération du jour de la semaine sélectionné dans le formulaire
$jourSemaine = $form->get('date_hebdomadaire')->getData();
            
           // Création des séances de cours pour chaque jour de la période sélectionnée, en recherchant la première occurrence du jour de la semaine sélectionné à partir de la date de début
$seances = array();
$dateCourante = $dateDebut;
while ($dateCourante <= $dateFin) {
    // Recherche la première occurrence du jour de la semaine sélectionné à partir de la date courante
    while (intval($dateCourante->format('N')) != $jourSemaine) {
        $dateCourante->modify('+1 day');
    }
    $dateOk = clone $dateCourante;
    $seance = new Seances();
    $seance->setDate($dateOk);
    $seance->setHeureDebut($heureDebut);
    $seance->setHeureFin($heureFin);
    $seance->setCoursId($cour);
    $seance->setDescription($description);
    $seance->setLibelle($libelle);
    $seance->setJour($jourSemaine);
    $entityManager->persist($seance);
    $seances[] = $seance;

    // Ajoute 1 semaine à la date courante
    $dateCourante = clone $dateCourante->modify('+1 week');
}

            // Lie le cours aux séances créées et enregistre le tout dans la base de données
            $cour->setSeances($seances);
            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/new.html.twig', [
            'cour' => $cour,
            'coursForm' => $form,
        ]);
    }

    /**
     * fonction pour afficher les détails d'un cours
     */
    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Cours $cour): Response
    {
        $utilisateurNom = $cour->getUtilisateurNom();
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'utilisateurNom' => $utilisateurNom,
        ]);
    }

    /**
     * fonction pour update un cours
     */
    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === cour.getUtilisateur() or is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->save($cour, true);

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/edit.html.twig', [
            'cour' => $cour,
            'editCoursForm' => $form,
        ]);
    }

    /**
     * fonction pour supprimer un cours
     */
    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_USER') and user === cour.getUtilisateur() or is_granted('ROLE_ADMIN')")]
    public function delete(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cour->getId(), $request->request->get('_token'))) {
            $coursRepository->remove($cour, true);
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }
}
