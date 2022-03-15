<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Users;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Votre Profil',
        ]);
    }

    #[Route('/orders/{id}', name: 'orders')]
    public function orders(OrdersRepository $repo, Users $user=null): Response
    {
        
        if($user){
            $orders=$repo->findAll($user);
        }
        return $this->render('profile/index.html.twig', [
            'orders'=>$orders,
            'controller_name' => 'Vos commandes',
        ]);
    }


}
