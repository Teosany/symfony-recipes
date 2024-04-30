<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{

    #[Route('/recettes', name: 'recipe.index')]
//    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    public function index(Request $request, EntityManagerInterface $em): Response
    {
//        dd($em->getRepository(Recipe::class));


        $recipes = $em->getRepository(Recipe::class)->findWithDurationLowerThan(10);
//        $recipes[0]->setTitle('Pates bolo3gn');

//        $recipe = new Recipe();
//        $recipe->setTitle('rec2')
//            ->setSlug('sdf')
//            ->setContent('sucré')
//            ->setCreatedAt(new \DateTimeImmutable())
//            ->setUpdatedAt(new \DateTimeImmutable());

//        $em->remove($recipes[0]);
//        $em->persist($recipes[0]);
//        $em->persist($recipe);
//        $em->flush();

//        $recipes = $em->getRepository(Recipe::class)->findWithDurationLowerThan(10);

//        dd($em->getRepository(Recipe::class)->findTotalDuration());
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-_]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);

        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recettes/{id}/edit', name: 'recipe.edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }
        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe
        ]);
    }
}
