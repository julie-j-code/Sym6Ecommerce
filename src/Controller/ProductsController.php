<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Products;
use App\Form\SearchForm;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $repo): Response
    {


        // on initialise les données récupérées via SearchForm
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);

        $products = $repo->findSearch();
        // $products=$repo->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'form' =>$form->createView(),
            'controller_name' => 'Produits au Catalogue',
        ]);
    }

    #[Route('/details/{slug}', name: 'details')]
    public function details(Products $products = null): Response
    {
        // $productName=$products->getName();

        return $this->render('products/details.html.twig', [
            "products" => $products,
            'controller_name' => 'Détails du produit',
        ]);
    }
}
