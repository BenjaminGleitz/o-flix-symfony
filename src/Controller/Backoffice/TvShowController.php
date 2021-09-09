<?php

namespace App\Controller\Backoffice;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/backoffice/tvshow", name="backoffice_tvshow_", requirements={"id":"\d+"})
 */
class TvShowController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        return $this->render('backoffice/tv_show/index.html.twig', [
            'tv_shows' => $tvShowRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tvShow = new TvShow();
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tvShow);
            $entityManager->flush();

            return $this->redirectToRoute('backoffice_tvshow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/tv_show/new.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @Route("/{slug}", name="slug")
     */
    public function show(TvShow $tvShow): Response
    {
        return $this->render('backoffice/tv_show/show.html.twig', [
            'tv_show' => $tvShow,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TvShow $tvShow, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère le title de la série
            // pour le transformer en slug
            $title = $tvShow->getTitle();
            $slug = $slugger->slug(strtolower($title));
            $tvShow->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            // Petit message flash
            $this->addFlash('success', 'La catégorie ' . $tvShow->getTitle() . ' a bien été mise à jour');

            return $this->redirectToRoute('backoffice_tvshow_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/tv_show/edit.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, TvShow $tvShow): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tvShow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tvShow);
            $entityManager->flush();

            // Petit message flash
            $this->addFlash('success', 'La catégorie ' . $tvShow->getTitle() . ' a bien été supprimé');
        }

        return $this->redirectToRoute('backoffice_tvshow_index', [], Response::HTTP_SEE_OTHER);
    }
}
