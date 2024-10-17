<?php

namespace App\Controller;

// Fournit un ensemble de methode qui pourrait être utile comme rediriger
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    //Les methodes renvoies toujours une Response
    //On peut definir les route ici ou soit dans config/routes
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        return $this->render("home/index.html.twig");
    }
}
