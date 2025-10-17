<?php
namespace App\Controller;

use App\Entity\LivresCreation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/livre')]
class bibliothèqueController extends AbstractController
{
    #[Route('/titre/{titre}/{author}', name:'bookTitle')]
    public function bookTitle(string $titre, string $author): Response
    {
        return $this->render('/title/bookTitle.html.twig', [
            'title' => $titre, 
            'author' => $author
        ]);
    }

    #[Route('/create', name:'createBook')]
    public function creationBook(EntityManagerInterface $entityManager): Response
    {
        $livre = new LivresCreation();
        $livre->setLivreId(1);
        $livre->setTitre('Mon premier livre');
        $livre->setDatePublication(new \DateTime());
        $livre->setAvailable(true);
        
        $entityManager->persist($livre);
        $entityManager->flush();
        
        return $this->render('createLivre/createBooks.html.twig', [
            'livre' => $livre
        ]);
    }
}