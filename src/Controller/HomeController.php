<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        // On récupère uniquement les 3 dernières séries
        // tous critères confondus par ID descendant (Du plus grand au plus petit)
        $latestTvShow = $tvShowRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'latestTvShow' => $latestTvShow,
        ]);
    }
}
