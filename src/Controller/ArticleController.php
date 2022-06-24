<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController

{
        #[Route('/articles', name: 'app_articles')]
    public function allArticles(ManagerRegistry $doctrine): Response

    {
        $articles = $doctrine->getRepository(Article::class)->findAll();

   // dd($articles);

        return $this->render('article/allArticles.html.twig', [
            'articles' => $articles
        ]);
    }
/**
 * @route("/ajout-article"), name="ajout_article)
 */
public function ajout(ManagerRegistry $doctrine, Request $request)
{ 
    // on crée un objet article
    $article = new Article();
   // On crée le formulaire en liant le formType à l'objet créée
    $form = $this->createForm(ArticleType::class, $article);
   // on donne accès aux données du formulaire pour la validation des données
    $form->handleRequest($request);
    // si le formulaire est valide
    if ( $form->isSubmitted() && $form->isValid())
    {
    // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
        $article->setDateDeCreation(new DateTime("now"));
    // on récupère le manager du doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($article);
        // on envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_articles");
    }

    return $this->render("article/formulaire.html.twig", [
        'formArticle' => $form->createView()
    ]);
} 

 /**
  * @Route("/update-article/{id})",name="article_update")
  */
  public function update(ManagerRegistry $doctrine, $id, Request $request)
 {
    // On récupère 
    $article = $doctrine->getRepository(Article::class)->find($id);

  //dd($article);

   // On crée le formulaire en liant le formType à l'objet créée
    $form = $this->createForm(ArticleType::class, $article);
   // on donne accès aux données du formulaire pour la validation des données
    $form->handleRequest($request);
    // si le formulaire est valide
    if ( $form->isSubmitted() && $form->isValid())
    {
    // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
        $article->setDateDeModification(new DateTime("now"));
    // on récupère le manager du doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($article);
        // on envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_articles");
    }

    return $this->render("article/formulaire.html.twig", [
        'formArticle' => $form->createView()
    ]);
} 

/**
 * @Route("/delete_article_{id}", name="article_delete")
 */
public function delete($id, ManagerRegistry $doctrine)
{
   // On récupère l'article à supprimer
   $article = $doctrine->getRepository(Article::class)->find($id);
   // on récupère le manager de doctrine
   $manager = $doctrine->getManager();
   // on prépare la suppression de l'article
   $manager->remove($article);
   // on execute l'action (suppression)
   $manager->flush();

   return $this->redirectToRoute("app_articles");
}

/**
 * @route("/article_{id}", name="app_article")
 */
public function showArticle($id, ManagerRegistry $doctrine)
{
    $article = $doctrine->getRepository(Article:: class)->find($id);

    return $this->render("article/unArticle.html.twig", [
        'article' => $article
    ]);


}
     
}


