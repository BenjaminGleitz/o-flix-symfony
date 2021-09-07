<?php

namespace App\Controller\Api\V1;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/v1/categories", name="api_v1_categories_")
 */
class CategoryController extends AbstractController
{

    //? INDEX

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        // On récupère les séries stockées en BDD
        $categories = $categoryRepository->findAll();
        // dd($categories);

        // On retourne la liste au format JSON
        // Pour résoudre le bug : Reference circular
        return $this->json($categories, 200, [], [
            // Cette entrée au Serialiser de transformer les objets
            // en JSON, en allant chercher uniquement les propriétés
            // taggées avec le nom tvshow_list
            'groups' => 'categories_list'
        ]);
    }

    // ? SHOW

    /**
     * Retourne les informations d'une série en fonction de son ID
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function show(int $id, CategoryRepository $categoryRepository)
    {
        // On récupère une série en fonction de son id
        $category = $categoryRepository->find($id);

        // Si la série n'existe pas, on retourne une erreur 404
        if (!$category) {
            return $this->json([
                'error' => 'La catégorie ' . $id . ' n\'existe pas'
            ], 404);
        }

        // On retourne le résultat au format JSON
        return $this->json($category, 200, [], [
            'groups' => 'categories_list'
        ]);
    }

    // ? ADD

    /**
     * Permet d'ajouter une catégorie
     * 
     * @Route("/", name="add", methods={"POST"})
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return void
     */
    public function edit(Request $request, SerializerInterface $serializer) {

        // On récupere le JSON
        $jsonData = $request->getContent();

        // 2) On transforme le json en objet : desérialisation
        // - On indique les données à transformer (desérialiser)
        // - On indique le format d'arrivé après conversion (objet de type TvShow)
        // - On indique le format de départ : on veut passer de json vers un objet
        $category = $serializer->deserialize($jsonData, Category::class, 'json');

        // On sauvegarde
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->json($category, 201);    
    }

    // ? DELETE


    /**
     * Permet de supprimer une catégorie
     * 
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @param integer $id
     * @param CategoryRepository $categoryRepository
     * @return void
     */
    public function delete($id,  CategoryRepository $categoryRepository) {
        
        // On recupere la série à supprimer
        $categoryToDelete = $categoryRepository->find($id);

        // Cas ou la série à supprimer n'existe pas
        // Si la série n'existe pas, on retourne une erreur 404
        if (!$categoryToDelete) {
            return $this->json([
                'error' => 'La categorie ' . $id . ' n\'existe pas'
            ], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($categoryToDelete);
        $em->flush();

        return $this->json($categoryToDelete, 204, [], [
            'groups' => 'category_delete'
        ]
    );
    }
}
