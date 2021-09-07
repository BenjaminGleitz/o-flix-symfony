<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository, TvShowRepository $tvShowRepository): Response
    {

        $categories = $categoryRepository->findAll();
        $tvShows = $tvShowRepository->findAll();


        return $this->render('category/index.html.twig', [
            'categoryName' => 'Derniers Ajouts',
            'categories' => $categories, 
            'tvShows' => $tvShows
        ]);
    }

    /**
     * Affiche les détails d'un article en fonction de son ID
     *
     * @Route("/category/{id}", name="category_tvshow", requirements={"id":"\d+"})
     *
     * @return Response
     */
    public function show(int $id, CategoryRepository $repositoryCategory, TvShowRepository $tvShowRepository): Response
    {
        // On récupère les informations de l'article dont L'ID est égal à $id
        // La méthode find($id) Retourne :
        // - Les informations de l'article si celui-ci existe
        // - null si l'article n'existe pas en BDD
        $categories = $repositoryCategory->findAll();
        $categoryById = $repositoryCategory->find($id);
        $tvShows = $tvShowRepository->findAll();

        dump($categories);

        // Si l'article n'existe pas
        if (!$categoryById) {
            throw $this->createNotFoundException("L'article dont l'id est $id n'existe pas");
        }
        
        // Si l'article existe...on l'affiche à partir de la vuecategory
        // show.html.twig
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
            'categoryById' => $categoryById,
            'tvShows' => $tvShows
        ]);
    }
}
