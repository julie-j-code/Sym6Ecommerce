<?php

namespace App\Controller;

use App\Repository\OrdersRepository;
use App\Repository\UsersRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'profile_')]
#[IsGranted('ROLE_USER', message: 'No access! Get out!')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UsersRepository $repo): Response
    {

        $user= $this->getUser();
        // $user = $repo->findBy($this->getUser());
        return $this->render('profile/index.html.twig', [
            'user'=>$user,
            'controller_name' => 'Votre Profil',
        ]);
    }

    #[Route('/orders', name: 'orders')]
    public function orders(OrdersRepository $repo): Response
    {
        $orders=$repo->findBy(['users'=> $this->getUser()]);

        return $this->render('profile/orders.html.twig', [
            'orders'=>$orders,
            'controller_name' => 'Vos commandes',
        ]);
    }


}
