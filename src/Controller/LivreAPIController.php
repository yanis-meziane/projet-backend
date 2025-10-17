<?php
/*
namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use App\Repository\AuteursRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/api/livres', name: 'api_livre_')]
class LivreApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private LivreRepository $livreRepo,
        private AuteursRepository $auteurRepo,
        private CategoriesRepository $categorieRepo,
        private LoggerInterface $logger
    ) {}

    // CREATE - Créer un livre
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return $this->json(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
            }

            // Validation des champs requis
            if (!isset($data['titre']) || !isset($data['datePublication']) || !isset($data['auteur_id']) || !isset($data['categorie_id'])) {
                return $this->json(['error' => 'Champs requis manquants'], Response::HTTP_BAD_REQUEST);
            }

            // Récupérer l'auteur
            $auteur = $this->auteurRepo->find($data['auteur_id']);
            if (!$auteur) {
                return $this->json(['error' => 'Auteur non trouvé'], Response::HTTP_NOT_FOUND);
            }

            // Récupérer la catégorie
            $categorie = $this->categorieRepo->find($data['categorie_id']);
            if (!$categorie) {
                return $this->json(['error' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setDatePublication(new \DateTime($data['datePublication']));
            $livre->setDisponible($data['disponible'] ?? true);
            $livre->setAuteur($auteur);
            $livre->setCategorie($categorie);

            $this->em->persist($livre);
            $this->em->flush();

            $this->logger->info('Livre créé avec succès', ['livre_id' => $livre->getId()]);

            return $this->json([
                'message' => 'Livre créé avec succès',
                'livre' => [
                    'id' => $livre->getId(),
                    'titre' => $livre->getTitre(),
                    'datePublication' => $livre->getDatePublication()->format('Y-m-d'),
                    'disponible' => $livre->isDisponible(),
                    'auteur' => [
                        'id' => $auteur->getId(),
                        'nom' => $auteur->getNom(),
                        'prenom' => $auteur->getPrenom()
                    ],
                    'categorie' => [
                        'id' => $categorie->getId(),
                        'nom' => $categorie->getNom()
                    ]
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la création du livre', ['error' => $e->getMessage()]);
            return $this->json(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // READ ALL - Récupérer tous les livres
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        try {
            $livres = $this->livreRepo->findAll();

            $data = array_map(function(Livre $livre) {
                return [
                    'id' => $livre->getId(),
                    'titre' => $livre->getTitre(),
                    'datePublication' => $livre->getDatePublication()->format('Y-m-d'),
                    'disponible' => $livre->isDisponible(),
                    'auteur' => [
                        'id' => $livre->getAuteur()->getId(),
                        'nom' => $livre->getAuteur()->getNom(),
                        'prenom' => $livre->getAuteur()->getPrenom()
                    ],
                    'categorie' => [
                        'id' => $livre->getCategorie()->getId(),
                        'nom' => $livre->getCategorie()->getNom()
                    ]
                ];
            }, $livres);

            return $this->json($data, Response::HTTP_OK);

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des livres', ['error' => $e->getMessage()]);
            return $this->json(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // READ ONE - Récupérer un livre par ID
    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): JsonResponse
    {
        try {
            $livre = $this->livreRepo->find($id);

            if (!$livre) {
                return $this->json(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
            }

            return $this->json([
                'id' => $livre->getId(),
                'titre' => $livre->getTitre(),
                'datePublication' => $livre->getDatePublication()->format('Y-m-d'),
                'disponible' => $livre->isDisponible(),
                'auteur' => [
                    'id' => $livre->getAuteur()->getId(),
                    'nom' => $livre->getAuteur()->getNom(),
                    'prenom' => $livre->getAuteur()->getPrenom(),
                    'biographie' => $livre->getAuteur()->getBiographie()
                ],
                'categorie' => [
                    'id' => $livre->getCategorie()->getId(),
                    'nom' => $livre->getCategorie()->getNom(),
                    'description' => $livre->getCategorie()->getDescription()
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération du livre', ['error' => $e->getMessage()]);
            return $this->json(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // UPDATE - Modifier un livre
    #[Route('/{id}', name: 'update', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $livre = $this->livreRepo->find($id);

            if (!$livre) {
                return $this->json(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return $this->json(['error' => 'Données JSON invalides'], Response::HTTP_BAD_REQUEST);
            }

            if (isset($data['titre'])) {
                $livre->setTitre($data['titre']);
            }

            if (isset($data['datePublication'])) {
                $livre->setDatePublication(new \DateTime($data['datePublication']));
            }

            if (isset($data['disponible'])) {
                $livre->setDisponible($data['disponible']);
            }

            if (isset($data['auteur_id'])) {
                $auteur = $this->auteurRepo->find($data['auteur_id']);
                if (!$auteur) {
                    return $this->json(['error' => 'Auteur non trouvé'], Response::HTTP_NOT_FOUND);
                }
                $livre->setAuteur($auteur);
            }

            if (isset($data['categorie_id'])) {
                $categorie = $this->categorieRepo->find($data['categorie_id']);
                if (!$categorie) {
                    return $this->json(['error' => 'Catégorie non trouvée'], Response::HTTP_NOT_FOUND);
                }
                $livre->setCategorie($categorie);
            }

            $this->em->flush();

            $this->logger->info('Livre modifié avec succès', ['livre_id' => $livre->getId()]);

            return $this->json([
                'message' => 'Livre modifié avec succès',
                'livre' => [
                    'id' => $livre->getId(),
                    'titre' => $livre->getTitre(),
                    'datePublication' => $livre->getDatePublication()->format('Y-m-d'),
                    'disponible' => $livre->isDisponible()
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la modification du livre', ['error' => $e->getMessage()]);
            return $this->json(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // DELETE - Supprimer un livre
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $livre = $this->livreRepo->find($id);

            if (!$livre) {
                return $this->json(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $this->em->remove($livre);
            $this->em->flush();

            $this->logger->info('Livre supprimé avec succès', ['livre_id' => $id]);

            return $this->json(['message' => 'Livre supprimé avec succès'], Response::HTTP_OK);

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la suppression du livre', ['error' => $e->getMessage()]);
            return $this->json(['error' => 'Erreur serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}*/