<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Inscrit;
use App\Entity\Presence;
use App\Form\InscritType;
use App\Repository\CoursRepository;
use App\Repository\InscritRepository;
use App\Repository\PresenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/inscrit')]
#[IsGranted('ROLE_USER')]
class InscritController extends AbstractController
{
    /**
     * Fonction pour afficher la liste des cours
     *
     * @param CoursRepository $coursRepository
     * @return Response
     */
    #[Route('/', name: 'app_inscrit_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('inscrit/listeCours.html.twig', [
            'cours' => $coursRepository->findAll(),           
        ]);
    }

    /**
     * fonction pour afficher la liste des inscrits à un cours particulier
     *
     * @param InscritRepository $inscritRepository
     * @return Response
     */
    
    #[Route('/list/{id}', name: 'app_inscrit_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function indexInscrit(InscritRepository $inscritRepository): Response
    {
        return $this->render('inscrit/index.html.twig', [
            'inscrits' => $inscritRepository->findAll(),
        ]);
    }

    /**
     * Fonction pour créer un inscrit mais en assignant par défaut l'id du cours présent dans l'url
     */
    #[Route('/new/{id}', name: 'app_inscrit_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, InscritRepository $inscritRepository, int $id, EntityManagerInterface $entityManager): Response
    {    
        //l'entitée Manager va chercher l'id du Cours qui est présent dans l'url         
        $cours = $entityManager->getRepository(Cours::class)->find($id);
        
        // Compte le nombre le nombre d'objets inscrits qui sont associé à l'objet cours dont l'id est dans l'url (c'est à dire lnombre d'élèves inscrits à ce cours)
        $nbInscrits = $inscritRepository->count(['cours' => $cours]);
        
        //Compare le nombre max d'élèves du cours dont l'id est présente dans l'url avec le nb d'inscrits
        if ($nbInscrits >= $cours->getMaxEleves()) {
            $this->addFlash('error', 'Le cours est complet');            
            return $this->redirectToRoute('app_inscrit_list', ['id'=>$id]);
        }

        //On crée un nouvel objet inscrit et on lui assigne directement l'objet cours (que l'on a récuppéré précédemment avec l'id dans l'url)
        $inscrit = new Inscrit();
        $inscrit->setCours($cours);

        $form = $this->createForm(InscritType::class, $inscrit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscritRepository->save($inscrit, true);
            
            //partie pour générer la table des présences dès ques les élèves s'inscrivent

            //récuppération des données lors de la création de l'inscrit
            //Les élèves inscrits sont de base noté en absent pour chaque cours (ils doivent modifier leur statut à posteriori)            
            $validationPressence = 0;
            $eleve = $form->get('eleve')->getData();
            //Ici le controlleur récuppère une collection d'objets Seances associé à l'objet Cours
            $seances = $inscrit->getCours()->getSeances();

            //Création des objets Presences pour chaque Seances de la collection
            foreach ($seances as $seance) {
                $presence = new Presence();
                $presence->setValidationPresence($validationPressence);
                $presence->setEleve($eleve);
                $presence->setSeances($seance);

                // sauvegarde de l'objet Presence dans la base de données
                $entityManager->persist($presence);
            }               
            $entityManager->flush();

            //'id' permet de rediriger après l'inscription sur la page du cours sur lequel l'utilisateur était précédemment
            return $this->redirectToRoute('app_inscrit_list', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscrit/new.html.twig', [
            'inscrit' => $inscrit,
            'form' => $form,           

        ]);
    }

    /**
     * Fonction pour afficher les détails d'un inscrit
     */
    #[Route('/{id}', name: 'app_inscrit_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Inscrit $inscrit): Response
    {
        return $this->render('inscrit/show.html.twig', [
            'inscrit' => $inscrit,
        ]);
    }

    /**
     * Fonction pour modifier un inscrit
     */
    #[Route('/{id}/edit', name: 'app_inscrit_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Inscrit $inscrit, InscritRepository $inscritRepository, int $id, PresenceRepository $presenceRepository): Response
    {
        //On récuppère l'id de l'élève qui était inscrit pour le moment (avant l'update), cette varaible est utile pour la modification de la table Presence
        $eleveInscrit = $inscrit->getEleve()->getId();

        //création du formulaire
        $form = $this->createForm(InscritType::class, $inscrit);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $inscritRepository->save($inscrit, true);

            //partie pour modifié également l'élève dans la table Presence dont les cours sont associés

            //récuppération des données lors de la modification de l'inscrit
            $eleve = $form->get('eleve')->getData();            
            $seances = $inscrit->getCours()->getSeances();
            

            //On parcours les séances associés à ce cour            
            foreach ($seances as $seance) {
                $presences = $seance->getPresences();
                //on parcours les présences associés à ces séances
                foreach ($presences as $presence) {
                    //On identifie les élèves inscrit aux séances
                    $elevePresence = $presence->getEleve()->getId();
                    
                    //Si il s'agit d'une séance dont l'élève inscrit est celui qu'on souhaite modifier, alors l'update se réalise
                    if ($elevePresence == $eleveInscrit) {
                        $presence->setEleve($eleve);
                        $presenceRepository->save($presence, true);
                    }
                }
            }            
            

            return $this->redirectToRoute('app_inscrit_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscrit/edit.html.twig', [
            'inscrit' => $inscrit,
            'form' => $form,
        ]);
    }

    /**
     * Fonction pour supprimer un inscrit
     */
    #[Route('/{id}', name: 'app_inscrit_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Inscrit $inscrit, InscritRepository $inscritRepository, PresenceRepository $presenceRepository): Response
    {
        //On récuppère l'id de l'élève qui était inscrit, cette varaible est utile pour la modification de la table Presence
        $eleveInscrit = $inscrit->getEleve()->getId();

        if ($this->isCsrfTokenValid('delete'.$inscrit->getId(), $request->request->get('_token'))) {
            $inscritRepository->remove($inscrit, true);

            //partie pour supprimer également l'élève dans la table Presence dont les cours sont associés

            //récuppération des données lors de la suppresion de l'inscrit
            $seances = $inscrit->getCours()->getSeances();

            //On parcours les séances associés à ce cour
            foreach ($seances as $seance) {
                $presences = $seance->getPresences();
                //on parcours les présences associés à ces séances
                foreach ($presences as $presence) {
                    //On identifie les élèves inscrit aux séances
                    $elevePresence = $presence->getEleve()->getId();
                    
                    //Si il s'agit d'une séance ayant le même élève alors on supprime la séance
                    if ($elevePresence == $eleveInscrit) {                        
                        $presenceRepository->remove($presence, true);
                    }
                }
            }
            //Si problème rajouter ?
            // $entityManager->flush();

        }

        return $this->redirectToRoute('app_inscrit_index', [], Response::HTTP_SEE_OTHER);
    }
}
