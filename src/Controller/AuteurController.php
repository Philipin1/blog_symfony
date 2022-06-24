<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    #[Route('/auteur', name: 'app_auteur')]
    public function allAuteur(ManagerRegistry $doctrine): Response
    {

        $auteurs = $doctrine->getRepository(Auteur::class)->findAll();

        //dd($auteurs)


        return $this->render('auteur/allAuteurs.html.twig', [
            'auteurs' => $auteurs
        ]);
    }
/**
 * @Route("/ajout-route") name="ajout_route)
 */
public function ajout(ManagerRegistry $doctrine, Request $request)
{

    // on crée un objet article
    $auteur = new Auteur();
   // On crée le formulaire en liant le formType à l'objet créée
    $form = $this->createForm(AuteurType::class, $auteur);
   // on donne accès aux données du formulaire pour la validation des données
    $form->handleRequest($request);
    // si le formulaire est valide
    if ( $form->isSubmitted() && $form->isValid())
    {
    // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
        $auteur->setDateDeCreation(new DateTime("now"));
    // on récupère le manager du doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($auteur);
        // on envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_auteurs");
    }

    return $this->render("auteur/formulaire.html.twig", [
        'formAuteur' => $form->createView()
    ]);
} 

 /**
  * @Route("/update-auteur/{id})",name="auteur_update")
  */
  public function update(ManagerRegistry $doctrine, $id, Request $request)
 {
    // On récupère 
    $auteur = $doctrine->getRepository(Auteur::class)->find($id);

  //dd($auteur);

   // On crée le formulaire en liant le formType à l'objet créée
    $form = $this->createForm(AuteurType::class, $auteur);
   // on donne accès aux données du formulaire pour la validation des données
    $form->handleRequest($request);
    // si le formulaire est valide
    if ( $form->isSubmitted() && $form->isValid())
    {
    // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
        $auteur->setDateDeModification(new DateTime("now"));
    // on récupère le manager du doctrine
        $manager = $doctrine->getManager();
        // on persist l'objet
        $manager->persist($auteur);
        // on envoie en bdd
        $manager->flush();

        return $this->redirectToRoute("app_auteurs");
    }

    return $this->render("auteur/formulaire.html.twig", [
        'formAuteur' => $form->createView()
    ]);
} 

/**
 * @Route("/delete_auteur_{id}", name="auteur_delete")
 */
public function delete($id, ManagerRegistry $doctrine)
{
   // On récupère l'auteur à supprimer
   $auteur = $doctrine->getRepository(Auteur::class)->find($id);
   // on récupère le manager de doctrine
   $manager = $doctrine->getManager();
   // on prépare la suppression de l'auteur
   $manager->remove($auteur);
   // on execute l'action (suppression)
   $manager->flush();

   return $this->redirectToRoute("app_auteurs");
}

/**
 * @route("/auteur_{id}", name="app_article")
 */
public function showAuteur($id, ManagerRegistry $doctrine)
{
    $auteur = $doctrine->getRepository(Auteur:: class)->find($id);

    return $this->render("auteur/unAuteur.html.twig", [
        'auteur' => $auteur
    ]);


}
     
}


