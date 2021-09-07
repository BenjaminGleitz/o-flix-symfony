<?php

namespace App\Controller\Api\V1;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/characters", name="api_v1_characters_")
 */
class CharacterController extends AbstractController
{
    //? INDEX

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CharacterRepository $characterRepository): Response
    {
        // On récupère les personnages stockées en BDD
        $characters = $characterRepository->findAll();
        // dd($characters);

        // On retourne la liste au format JSON
        // Pour résoudre le bug : Reference circular
        return $this->json($characters, 200, [], [
            // Cette entrée au Serialiser de transformer les objets
            // en JSON, en allant chercher uniquement les propriétés
            // taggées avec le nom tvshow_list
            'groups' => 'characters_list'
        ]);
    }

    // ? SHOW

    /**
     * Retourne les informations d'un personnage en fonction de son ID
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function show(int $id, CharacterRepository $characterRepository)
    {
        // On récupère un personnage en fonction de son id
        $character = $characterRepository->find($id);

        // Si le personnage n'existe pas, on retourne une erreur 404
        if (!$character) {
            return $this->json([
                'error' => 'Le personnage ' . $id . ' n\'existe pas'
            ], 404);
        }

        // On retourne le résultat au format JSON
        return $this->json($character, 200, [], [
            'groups' => 'characters_list'
        ]);
    }

    // ? ADD

    /**
     * Permet d'ajouter un personnage
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
        // - On indique le format d'arrivé après conversion (objet de type Character)
        // - On indique le format de départ : on veut passer de json vers un objet
        $character = $serializer->deserialize($jsonData, Character::class, 'json');

        // On sauvegarde
        $em = $this->getDoctrine()->getManager();
        $em->persist($character);
        $em->flush();

        return $this->json($character, 201);    
    }

    // ? DELETE


    /**
     * Permet de supprimer une catégorie
     * 
     * @Route("/{id}", name="delete", methods={"DELETE"})
     *
     * @param integer $id
     * @param CharacterRepository $characterRepository
     * @return void
     */
    public function delete($id,  CharacterRepository $characterRepository) {
        
        // On recupere la série à supprimer
        $characterToDelete = $characterRepository->find($id);

        // Cas ou la série à supprimer n'existe pas
        // Si la série n'existe pas, on retourne une erreur 404
        if (!$characterToDelete) {
            return $this->json([
                'error' => 'Le personnage ' . $id . ' n\'existe pas'
            ], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($characterToDelete);
        $em->flush();

        return $this->json($characterToDelete, 204, [], [
            'groups' => 'characters_delete'
        ]
    );
    }
}
