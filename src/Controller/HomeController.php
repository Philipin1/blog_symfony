<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // On cherche le dernier article inséré en base de données en utilisant le respository
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $dernierArticle = $doctrine->getRepository(Article::class)->findOneBy([], 
    ["dateDeCreation" => "Desc"]);
        return $this->render('home/index.html.twig', [
            'dernierArticle' => $dernierArticle
        ]);
    }
}
