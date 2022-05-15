<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Repository\UsersRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Validator\Constraints\FormValidator;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/password-forgotten', name: 'forgotten_password')]

    public function forgottenPassword(Request $request, UsersRepository $repo, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $manager, SendMailService $mailer): Response
    {


        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on va chercher l'utlisateur par son email
            $user = $repo->findOneByEmail($form->get('email')->getData());
            // si l'utilisateur existe
            if ($user) {
                // On génère un token de reinitialisation
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $manager->flush();

                //    on génère un lien de réinitialisation
                $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // on crée les données du mail
                $context = compact('url', 'user');

                // on envoie le mail
                $mailer->send(
                    'no-reply@symfony-commerce.fr',
                    $user->getEmail(),
                    'réinitialisation de mot de passe',
                    'password-reset',
                    $context
                );

                $this->addFlash(
                    'success',
                    'Email envoyé avec succès'
                );
                return $this->redirectToRoute('app_login');
            }
            $this->addFlash(
                'danger',
                'un problème est survenu'
            );
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset-password-request.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'Reset Password'
        ]);
    }


    #[Route(path: '/reset-password/{token}', name: 'reset_password')]

    public function resetPassword(string $token, UsersRepository $repo, EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {

        // on vérifie si on a ce token dans la base de donnée
        $user = $repo->findOneByResetToken($token);
        if ($user) {
            # code...
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                # code...
                // on efface le token
                $user->setResetToken('');
                // on reset le password
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                   'success',
                   'Votre mot de passe a bien été modifié'
                );

                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset-password.html.twig', [
                'controller_name' => 'Reset Password',
                'form' => $form->createView()

            ]);
        }
        $this->addFlash(
            'danger',
            'jeton invalide'
        );
        return $this->redirectToRoute('app_login');
    }
}
