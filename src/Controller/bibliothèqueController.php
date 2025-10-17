<?php
namespace App\Controller;

use App\Entity\LivresCreation;
use App\Entity\Auteurs;
use App\Entity\Categories;
use App\Entity\Users;
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

    #[Route('/create/book', name:'createBook')]
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
    #[Route('/create/authors', name:'createAuthors')]
        public function creationAuthors(EntityManagerInterface $entityManager): Response{
            $authors = new Auteurs();
            $authors -> setNom("Rowling");
            $authors -> setPrenom("JK");
            $authors -> setBiographie("Petite biographie");
            $authors -> setDateNaissance("31/07/1965");

            $entityManager->persist($authors);
            $entityManager->flush();

            return $this->render('auteurs/auteurs.html.twig', [
            'authors' => $authors]);
        }

        #[Route('/create/categories', name:'CategoryBooksName')]
        public function creationCategory(EntityManagerInterface $entityManager):Response{
            $category = new Categories();
            $category -> setNom('SF');
            $category -> setDescription('Une categorie parfaite pour tous les âges ! Aussi bien pour les petits que pour les grands !');

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->render('category/createCategory.html.twig',['category'=> $category]);
        }
        
         #[Route('/create/users', name:'createUsers')]
        public function creationUsers(EntityManagerInterface $entityManager):Response{
            $users = new Users();
            $users -> setPrenom('Yanis');
            $users -> setNom('MEZIANE');
            
            $entityManager->persist($users);
            $entityManager->flush();

            return $this->render('users/createUser.html.twig',['users'=> $users]);
        }
    }
