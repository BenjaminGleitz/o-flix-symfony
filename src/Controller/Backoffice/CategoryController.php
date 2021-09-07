<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/backoffice/category", name="backoffice_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('backoffice/category/index.html.twig', [
            'controller_name' => 'CategoryController',
                'categories' => $categoryRepository->findAll(),
        ]);
    }

    // ?################################ SHOW ################################

    /**
     * @Route("/show/{id}", name="show")
     *
     * @param integer $id
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function show(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('La catégorie ' . $id . ' n\'existe pas');
        }

        return $this->render('backoffice/category/show.html.twig', [
            'category' => $category
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
    public function add(Request $request)
    {
        // Etape 1 : on instancie un objet vide
        $category = new Category();

        // Etape 2 : on "instancie" le formtype
        // Et on lie l'instance $category à notre formulaire
        $form = $this->createForm(CategoryType::class, $category);

        // Etape 4 ou 2.5 : on réceptionne les données issues du formulaire
        // qui sont ensuite injecté dans l'objet $category
        $form->handleRequest($request);

        // Etape 5 ou 2.6 : on vérifie qu'on est bien dans le cas d'une soumission
        // de formulaire, avant de sauvegarder
        if ($form->isSubmitted() && $form->isValid()) {
            // On créé la nouvelle catégorie
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            // Petit message flash
            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été créée');

            // On redirige vers la liste des catégories
            return $this->redirectToRoute('backoffice_category_index');
        }

        // Etape 3 : on affiche le formulaire dans la vue
        // templates/category/add.html.twig
        return $this->render('backoffice/category/add.html.twig', [
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
    public function edit(int $id, Request $request, CategoryRepository $categoryRepository)
    {
        // Etape 1 : on récupère la catégorie dont l'ID est $id
        $category = $categoryRepository->find($id);

        // Etape 2 : on "instancie" le formtype
        // Et on lie l'instance $category à notre formulaire
        $form = $this->createForm(CategoryType::class, $category);

        // Etape 4 ou 2.5 : on réceptionne les données issues du formulaire
        // qui sont ensuite injecté dans l'objet $category
        $form->handleRequest($request);

        // Etape 5 ou 2.6 : on vérifie qu'on est bien dans le cas d'une soumission
        // de formulaire, avant de sauvegarder
        if ($form->isSubmitted() && $form->isValid()) {
            // On créé la nouvelle catégorie
            $category->setUpdatedAt(new DateTimeImmutable());
            $em = $this->getDoctrine()->getManager();
            // Persist n'est pas nécessaire dans le cas d'une MAJ
            // $em->persist($category);
            $em->flush();

            // Petit message flash
            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été mise à jour');

            // On redirige vers la catégorie éditée
            return $this->redirectToRoute('backoffice_category_index', ['id' => $id]);
        }

        // Etape 3 : on affiche le formulaire dans la vue
        // templates/category/add.html.twig
        return $this->render('backoffice/category/edit.html.twig', [
            'formView' => $form->createView(),
            'category' => $category
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
    public function delete(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        // Petit message flash
        $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été supprimée');

        // On redirige vers la liste des catégories
        return $this->redirectToRoute('backoffice_category_index');
    }
}
