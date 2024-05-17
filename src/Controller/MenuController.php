<?php

namespace App\Controller;

use App\Repository\BureauRepository;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends AbstractController
{
    #[Route('/', name: 'menu')]
    public function index(): Response
    {
        $tableauMenu = [
            [
                "label" => "",
                "route" => "menu",
                "image" => "./image/logo2.png"
            ],
            ["label" => "Connexion", "route" => "menu"]
        ];

        return $this->render('base.html.twig', [
            'tableauMenu' => $tableauMenu,
        ]);
    }

    #[Route('/get_bureau_details/{n_bureau}', name: 'get_bureau_details')]
    public function getBureauDetails(string $n_bureau, BureauRepository $bureauRepository, PersonneRepository $personneRepository): JsonResponse
    {
        // Récupérer les détails du bureau à partir du nom du bureau
        $bureau = $bureauRepository->findOneBy(['n_bureau' => $n_bureau]);

        // Vérifier si le bureau existe
        if (!$bureau) {
            throw $this->createNotFoundException('Bureau non trouvé.');
        }
        $id_bureau = $bureau->getId();

        // Récupérer les personnes liées à ce bureau
        $personnes = $personneRepository->findBy(['bureau' => $id_bureau]);

        // Construire une liste des détails des personnes
        $personnesDetails = [];
        if ($personnes) {
            foreach ($personnes as $personne) {
                $personnesDetails[] = [
                    'id' => $personne->getId(),
                    'nom' => $personne->getNom(),
                    'prenom' => $personne->getPrenom(),
                    'statut' => $personne->getStatut(),
                    'date_arrive' => $personne->getDateArrive(),
                    'date_depart' => $personne->getDateDepart(),
                    'telephone' => $personne->getNumeroTel(),
                ];
            }
        }

        // Retourner les détails du bureau et des personnes en JSON
        return $this->json([
            'bureau' => [
                'id' => $bureau->getId(),
                'nombrePlace' => $bureau->getNombPlace(),
                'nomBureau' => $bureau->getNBureau(),
            ],
            'personnes' => $personnesDetails,
        ]);
    }


    #[Route('/get_person_details/{personId}', name: 'get_person_details')]
    public function getPersonDetails(int $personId, PersonneRepository $personneRepository): JsonResponse
    {
        // Récupérer les détails de la personne à partir de son ID
        $personne = $personneRepository->find($personId);

        // Vérifier si la personne existe
        if (!$personne) {
            throw $this->createNotFoundException('Personne non trouvée.');
        }

        // Récupérer le bureau auquel la personne est associée
        $bureau = $personne->getBureau();

        // Vérifier si la personne est associée à un bureau
        if (!$bureau) {
            throw $this->createNotFoundException('Personne non associée à un bureau.');
        }

        // Récupérer le nom du bureau
        $nomBureau = $bureau->getNBureau();

        // Retourner les détails de la personne en JSON, y compris le nom du bureau
        return $this->json([
            'id' => $personne->getId(),
            'nom' => $personne->getNom(),
            'prenom' => $personne->getPrenom(),
            'statut' => $personne->getStatut(),
            'date_arrive' => $personne->getDateArrive(),
            'date_depart' => $personne->getDateDepart(),
            'telephone' => $personne->getNumeroTel(),
            'nombureau' => $nomBureau,
        ]);
    }

    #[Route('/search_personnes', name: 'search_personnes')]
    public function searchPersonnes(Request $request, PersonneRepository $personneRepository): JsonResponse
    {
        // Récupérer le texte de recherche depuis la requête
        $query = $request->query->get('query');

        // Vérifier si le texte de recherche est vide
        if (empty($query)) {
            return new JsonResponse(['message' => 'Veuillez entrer un terme de recherche.'], Response::HTTP_BAD_REQUEST);
        }

        // Effectuer la recherche dans la base de données
        $personnes = $personneRepository->search($query);

        // Construire un tableau des résultats de recherche
        $results = [];
        foreach ($personnes as $personne) {
            $results[] = [
                'id' => $personne->getId(),
                'nom' => $personne->getNom(),
                'prenom' => $personne->getPrenom(),
                // Ajoutez d'autres champs si nécessaire
            ];
        }

        // Retourner les résultats de recherche en JSON
        return new JsonResponse($results);
    }


}
