<?php

namespace App\Controller\Admin;

use App\Demo;
use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use League\Glide\ServerFactory;
use League\Glide\Responses\SymfonyResponseFactory;

#[Route('/admin/recipes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{
    public function __construct(private RecipeRepository $recipeRepository)
    {

    }

    #[Route('/', name: 'index')]
//    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
//    public function index(Request $request, EntityManagerInterface $em): Response
    public function index(EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
//        dd($em->getRepository(Recipe::class));
//        $platPrincipal = $categoryRepository->findOneBy(['slug' => 'plat-principal']);
//        $pates = $this->recipeRepository->findOneBy(['slug' => 'pates-bolognaise']);
//        $pates->setCategory($platPrincipal);
//        $em->flush();

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
//        dd($recipes[5]->getCategory());
//        $recipes[5]->getCategory()->getName();
//        dd($recipes[5]->getCategory());

        $recipes = $em->getRepository(Recipe::class)->findWithDurationLowerThan(10);

//        $category = (new Category())
//            ->setUpdatedAt(new \DateTimeImmutable())
//            ->setCreatedAt(new \DateTimeImmutable())
//            ->setSlug('demo')
//            ->setName('demo');
//        $recipes[1]->setCategory($category);

//        $em->persist($category);
//        $em->flush();

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }


    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée !');

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form,
            'recipe' => ['title' => 'New recipe', 'content' => 'Ajouter un nouveau recette!']
        ]);

    }

    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em, UploaderHelper $helper): Response
    {
//        dd($helper->asset($recipe));
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($form->get('thumbnailFile')->getData());
//            dd($file->getClientOriginalName(), $file->getClientOriginalExtension());
//            dd($filename);
//            dd($this->getParameter('kernel.build_dir'));
//            dd($this->getParameter('kernel.project_dir'));

//            /**
//             * @var UploadedFile $file
//             */
//            $file = $form->get('thumbnailFile')->getData();
//            $filename = $recipe->getId() . '.' . $file->getClientOriginalExtension();
//            $file->move($this->getParameter('kernel.project_dir') . '/public/recipes/recipes', $filename);
//            $recipe->setThumbnail($filename);

            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifié');

            return $this->redirectToRoute('admin.recipe.index', [
//                'id' => $recipe->getId(),
            ]);
        }
//        return $this->render('admin/recipe/edit.html.twig', [
//            'form' => $form,
//            'recipe' => $recipe
//        ]);

//        $server = ServerFactory::create([
//            'response' => new SymfonyResponseFactory(),
//        ]);

        return $this->render('admin/recipe/edit.html.twig', [
            'form' => $form,
            'recipe' => $recipe
        ]);
    }

    #[Route('/{id}', name: 'remove', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function remove(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();

        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }

    //    #[Route('/recipes/{id}/edit', name: 'recipe.edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
//    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em, FormFactoryInterface $formFactory): Response
//    {
//        $form = $formFactory->create(RecipeType::class, $recipe);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em->flush();
//            $this->addFlash('success', 'La recette a bien été modifié');
//
//            return $this->redirectToRoute('recipe.index', [
//                'id' => $recipe->getId(),
//            ]);
//        }
//
//        return $this->render('recipe/edit.html.twig', [
//            'form' => $form,
//            'recipe' => $recipe
//        ]);
//    }

//    #[Route('/demo')]
//    public function demo(ValidatorInterface $validator)
//    {
//        $recipe = new Recipe();
//        $errors = $validator->validate($recipe);
//        dd((string)$errors);
//    }

//    #[Route('/recipes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-_]+'])]
//    public function show(Request $request, string $slug, int $id): Response
//    {
//        $recipe = $this->repository->find($id);
//
//        if ($recipe->getSlug() != $slug) {
//            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
//        }
//        return $this->render('recipe/show.html.twig', [
//            'recipe' => $recipe,
//        ]);
//    }
//    #[Route('/demo')]
//    public function demo(Demo $demo)
//    {
//        dd($demo);
//    }
}
