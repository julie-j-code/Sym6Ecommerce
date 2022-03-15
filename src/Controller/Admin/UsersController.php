<?php

namespace App\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(UsersRepository $repo): Response
    {
        $users=$repo->findAll();
        
        return $this->render('admin/index.html.twig', [
            'users'=>$users,
            'controller_name' => 'Gestion des utilisateurs',
        ]);
    }
}
