<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produits;
use App\Entity\Categories;

//--------------------------------
// Fichier créé par Jules Lajoie
// 7 mars 2024
//--------------------------------   

class BaseController extends AbstractController
{
    //-------------------------------------
    //
    //-------------------------------------
    // Deuxième route pour détails
    #[Route('/details/{id}', name: 'details')]
    public function details(ManagerRegistry $doctrine, $id): Response
    {
        $manager = $doctrine->getManager();

        $produit = $manager->getRepository(Produits::class)->find($id);

        return $this->render('detailsProduit.html.twig',
        ['produit' => $produit ]);


    }

    //-------------------------------------
    //
    //-------------------------------------

    // Première route
    #[Route('/', name: 'accueil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getManager()
        ->getRepository(Produits::class)
        ->findAll();

        $categories = $doctrine->getManager()
        ->getRepository(Categories::class)
        ->findAll();

        // Faire ici quand click sur une catégorie


        return $this->render('accueil.html.twig',
        ['tabProduits' => $produits, 'tabCategories' => $categories ]);
    }


    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/filtre', name:'accueilFiltre')]
    public function accueilFiltre(ManagerRegistry $doctrine, Request $request)
    {

        $tabProduits = $doctrine->
                             getManager()->
                             getRepository(Produits::class)->
                             findAll();

        $categories = $doctrine->getManager()
                             ->getRepository(Categories::class)
                             ->findAll();

        // Récup à partir du $_GET                            
        $texteRecherche = $request->query->get('texteRecherche');
        $idCategorie = $request->query->get('categorie');


        if (strlen($texteRecherche) > 0 )
        {
            $tabProduits = $this->AppliqueCritere($tabProduits, $texteRecherche);
            if (count($tabProduits) == 0)
            {
                $this->addFlash("notice", "Aucune produit n'a un nom contenant '$texteRecherche'");
            }
        }

        if (isset($idCategorie))
        {
            if($idCategorie == 5)
            {
                $this->addFlash("notice", "Les produits sont à venir");
            }
           if ($idCategorie > "0" )
           {
            $categorie = $doctrine->
                      getManager()->
                      getRepository(Categories::class)->
                      find($idCategorie);

            $tabProduits = $categorie->getProduits();
           }
           else if($idCategorie == -1)
           {
            $categories = $doctrine->getManager()
            ->getRepository(Categories::class)
            ->findAll();
            

           }
        }


        return $this->render('accueil.html.twig', ['tabProduits' => $tabProduits,
                                                    'tabCategories' => $categories  
                                                ]);
    }

    //--------------------------------------
    //
    //--------------------------------------
    private function AppliqueCritere($tabProduits, $crit)
    {
        $tabTmp = [];
        foreach($tabProduits as $unChomeur)
        {
            if ( stripos( $unChomeur->getNom(), $crit) !== false)
            {
                $tabTmp[] = $unChomeur;
            }
        }
        return $tabTmp;
    }

    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/contact', name: 'contact')]
    public function contact(ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();

        return $this->render('contact.html.twig', []);
    }


}



