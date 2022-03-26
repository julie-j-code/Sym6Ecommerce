<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }
    #[Route('/categories/{slug}', name: 'category')]
    public function category(Categories $categories,CategoriesRepository $categoriesRepository,ProductsRepository $productsRepository, $slug): Response
    {
        
        $products = $categories->getProducts();
        $id=$categories->getId();
        // dd($id);
        $allCategories = $categoriesRepository->findAll();
        // dd($allCategories);
        // $childCategories = $categoriesRepository->findBy(['parentId'=>$id]);
        // dd($childCategories);
        // $childProducts = $productsRepository->findBy(['categoriesId'=>$childCategories]);
        // dd($childProducts);
        return $this->render('categories/show.html.twig', [
            // 'childProducts' => $childProducts,
            'allCategories' => $allCategories,
            'id' => $id,
            'products' =>$products,
            'categories'=>$categories,
            'controller_name' => 'Contenu de la Categorie',
        ]);
    }
}
