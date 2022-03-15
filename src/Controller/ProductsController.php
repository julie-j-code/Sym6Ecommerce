<?php

namespace App\Controller;

use App\Entity\Products;
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
        
        $products=$repo->findAll();

        return $this->render('products/index.html.twig', [
            'products'=>$products,
            'controller_name' => 'Produits au Catalogue',
        ]);
    }

    #[Route('/details/{slug}', name: 'details')]
    public function details(Products $products=null): Response
    {
        // $productName=$products->getName();
        
        return $this->render('products/details.html.twig', [
            "products"=>$products,
            'controller_name' => 'Détails du produit',
        ]);
    }
}
