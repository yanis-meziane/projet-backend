<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Psr\Log\LoggerInterface;

class bibliothèqueController extends AbstractController
{
        #[Route('/livre/titre/{titre}/{author}', name:'bookTitle')]
            public function bookTitle(string $titre, string $author):Response{
                    return $this->render('/title/bookTitle.html.twig',['title'=>$titre, 'author'=>$author]);

                }
    
}