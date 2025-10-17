<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;

#[Route('/livre')]
class bibliothèqueController extends AbstractController
{
        //Création d'une route pour tester le fonctionnement de base.

        #[Route('/titre/{titre}/{author}', name:'bookTitle')]
            public function bookTitle(string $titre, string $author):Response{
                    return $this->render('/title/bookTitle.html.twig',['title'=>$titre, 'author'=>$author]);
                }

        // Création des livres 

        #[Route('/create',name:'createBook', methods:['POST'])]
                public function creationBook(Request $request, EntityManagerInterface $entityManager):Response{
                        
                }

            
    
}