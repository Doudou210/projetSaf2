<?php

namespace App\Controller;

use App\Entity\Register;
use App\Form\LogRegType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginPageController extends AbstractController
{
    #[Route('/registerPage', name: 'registerPage')]
    public function create(Request $request, ManagerRegistry $mr): Response
    {
        $user = new Register();
        $form = $this->createForm(LogRegType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user= $form->getData();
            $en = $mr->getManager();
            $en ->persist($user);
            $en->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render('loginpage/index.html.twig', [
            "form" =>$form->createView()
        ]);
    }

    #[Route("/profils/{id}/edit", name:"profil")]
    public function profil(): Response{
        // $form = $this->createForm(LogRegType::class){
        //     return $this->render("article/");
        // };
    }
    
    #[Route("/login", name:"login")]
    public function login(): Response{
        
    }
}
