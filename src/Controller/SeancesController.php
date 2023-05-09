<?php

namespace App\Controller;

use App\Entity\Seances;
use App\Form\SeancesType;
use App\Repository\SeancesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seances')]
class SeancesController extends AbstractController
{
    #[Route('/', name: 'app_seances_index', methods: ['GET'])]
    public function index(SeancesRepository $seancesRepository): Response
    {
        return $this->render('seances/index.html.twig', [
            'seances' => $seancesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_seances_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SeancesRepository $seancesRepository): Response
    {
        $seance = new Seances();
        $form = $this->createForm(SeancesType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seancesRepository->save($seance, true);

            return $this->redirectToRoute('app_seances_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seances/new.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seances_show', methods: ['GET'])]
    public function show(Seances $seance): Response
    {
        return $this->render('seances/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seances_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seances $seance, SeancesRepository $seancesRepository): Response
    {
        $form = $this->createForm(SeancesType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seancesRepository->save($seance, true);

            return $this->redirectToRoute('app_seances_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seances/edit.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seances_delete', methods: ['POST'])]
    public function delete(Request $request, Seances $seance, SeancesRepository $seancesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
            $seancesRepository->remove($seance, true);
        }

        return $this->redirectToRoute('app_seances_index', [], Response::HTTP_SEE_OTHER);
    }
}
