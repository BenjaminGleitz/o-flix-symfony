<?php

namespace App\Controller\Backoffice;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/backoffice/character", name="backoffice_character_")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CharacterRepository $characterRepository): Response
    {
        return $this->render('backoffice/character/index.html.twig', [
            'controller_name' => 'CharacterController',
            'characters' => $characterRepository->findAll(),
        ]);
    }

    // ?################################ SHOW ################################

    /**
     * @Route("/show/{id}", name="show")
     *
     * @param integer $id
     * @param CharacterRepository $characterRepository
     * @return Response
     */
    public function show(int $id, CharacterRepository $characterRepository)
    {
        $character = $characterRepository->find($id);

        if (!$character) {
            throw $this->createNotFoundException('La catégorie ' . $id . ' n\'existe pas');
        }

        return $this->render('backoffice/character/show.html.twig', [
            'character' => $character
        ]);
    }

    // ?################################ ADD ################################

    /**
     * Permet de créér une nouvelle catégorie
     * 
     * @Route("/add", name="add")
     *
     * @return void
     */
    public function add(Request $request, SluggerInterface $slugger, ImageUploader $imageUploader)
    {

        // Etape 1 : on instancie un objet vide
        $character = new Character();

        // Etape 2 : on "instancie" le formtype
        // Et on lie l'instance $character à notre formulaire
        $form = $this->createForm(CharacterType::class, $character);

        // Etape 4 ou 2.5 : on réceptionne les données issues du formulaire
        // qui sont ensuite injecté dans l'objet $character
        $form->handleRequest($request);

        // Etape 5 ou 2.6 : on vérifie qu'on est bien dans le cas d'une soumission
        // de formulaire, avant de sauvegarder
        if ($form->isSubmitted() && $form->isValid()) {
            // On effectue l'upload du fichier grâce au service ImageUploader
            $newFilename = $imageUploader->upload($form, 'imgupload');

            // on met à jour la propriété image 
            if ($newFilename) {
                $character->setImage($newFilename);
            }

            // On sauvegarde le personnage en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($character);
            $entityManager->flush();

            return $this->redirectToRoute('backoffice_character_index', [], Response::HTTP_SEE_OTHER);
        }

        // Etape 3 : on affiche le formulaire dans la vue
        // templates/character/add.html.twig
        return $this->render('backoffice/character/add.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    // ?################################ EDIT ################################

    /**
     * Permet de mettre à jour une catégorie
     * 
     * @Route("/{id}/edit", name="edit")
     *
     * @return void
     */
    public function edit(int $id, Request $request, CharacterRepository $characterRepository, ImageUploader $imageUploader)
    {
        // Etape 1 : on récupère la catégorie dont l'ID est $id
        $character = $characterRepository->find($id);

        // Etape 2 : on "instancie" le formtype
        // Et on lie l'instance $character à notre formulaire
        $form = $this->createForm(CharacterType::class, $character);

        // Etape 4 ou 2.5 : on réceptionne les données issues du formulaire
        // qui sont ensuite injecté dans l'objet $character
        $form->handleRequest($request);

        // Etape 5 ou 2.6 : on vérifie qu'on est bien dans le cas d'une soumission
        // de formulaire, avant de sauvegarder
        if ($form->isSubmitted() && $form->isValid()) {
            // On effectue l'upload du fichier grâce au service ImageUploader
            $newFilename = $imageUploader->upload($form, 'imgupload');

            // on met à jour la propriété image 
            if ($newFilename) {
                $character->setImage($newFilename);
            }
            // On créé la nouvelle catégorie
            $em = $this->getDoctrine()->getManager();
            // Persist n'est pas nécessaire dans le cas d'une MAJ
            // $em->persist($character);
            $em->flush();

            // Petit message flash
            $this->addFlash('success', 'La catégorie ' . $character->getFirstname() . $character->getLastname() . ' a bien été mise à jour');

            // On redirige vers la catégorie éditée
            return $this->redirectToRoute('backoffice_character_index', ['id' => $id]);
        }

        // Etape 3 : on affiche le formulaire dans la vue
        // templates/character/add.html.twig
        return $this->render('backoffice/character/edit.html.twig', [
            'formView' => $form->createView(),
            'character' => $character
        ]);
    }

    // ?################################ DELETE ################################

    /**
     * Suppression d'une catégorie
     * 
     * @Route("/{id}/delete", name="delete")
     *
     * @return void
     */
    public function delete(int $id, CharacterRepository $characterRepository)
    {
        $character = $characterRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($character);
        $em->flush();

        // Petit message flash
        $this->addFlash('success', 'La catégorie ' . $character->getFirstname() . $character->getLastname() . ' a bien été supprimée');

        // On redirige vers la liste des catégories
        return $this->redirectToRoute('backoffice_character_index');
    }
}
