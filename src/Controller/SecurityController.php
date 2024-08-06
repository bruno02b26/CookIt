<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }
    #[Route('/login-email', name: 'app_login-email')]
    public function loginEmail(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_email.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \Exception('Exception in app_logout');
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('security/register.html.twig');
    }

    #[Route('/register-email', name: 'app_register-email')]
    public function registerEmail(): Response
    {
        return $this->render('security/register_email.html.twig');
    }

    #[Route('/register_email', name: 'app_register_email', methods: ['POST'])]
    public function registerEmailPost(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            $name = $request->request->get('name');
            $surname = $request->request->get('surname');
            $userType = $entityManager->getRepository(UserType::class)->find(2);

            if ($password !== $confirmPassword) {
                return $this->render('security/register.html.twig', [
                    'error' => 'Passwords do not match',
                ]);
            }

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                return new JsonResponse(['error' => 'User with this email already exists'], Response::HTTP_CONFLICT);
            }

            $user = new User($name, $surname, $email, '', $userType);

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $session->getFlashBag()->add('success', 'Registration successful. Please log in.');

            return new JsonResponse(['redirect' => $this->generateUrl('app_login-email')]);
        }

        return $this->render('security/register_email.html.twig');
    }

    #[Route('/login_email', name: 'app_login_email', methods: ['POST'])]
    public function loginEmailPost(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, FormLoginAuthenticator $formLoginAuthenticator): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->render('security/login_email.html.twig', [
                'email' => $email,
                'error' => 'Invalid email or password.'
            ]);
        }

        return $userAuthenticator->authenticateUser($user, $formLoginAuthenticator, $request);
    }
}
