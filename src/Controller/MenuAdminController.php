<?php

namespace App\Controller;

use App\Repository\BureauRepository;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MenuAdminController extends AbstractController
{
    #[Route('/menu', name: 'app_menu_admin')]
    public function index(): Response
    {
        $tableauMenu = [
            [
                "label" => "",
                "route" => "app_menu_admin",
                "image" => "./image/logo2.png"
            ],
            ["label" => "Déconnexion", "route" => "app_menu_admin"]
        ];

        return $this->render('menu_admin/index.html.twig', [
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
            return $this->json(['error' => 'Bureau non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $id_bureau = $bureau->getId();

        // Récupérer les personnes liées à ce bureau
        $personnes = $personneRepository->findBy(['bureau' => $id_bureau]);

        // Construire une liste des détails des personnes
        $personnesDetails = array_map(function($personne) {
            return [
                'id' => $personne->getId(),
                'nom' => $personne->getNom(),
                'prenom' => $personne->getPrenom(),
                'statut' => $personne->getStatut(),
                'date_arrive' => $personne->getDateArrive(),
                'date_depart' => $personne->getDateDepart(),
                'telephone' => $personne->getNumeroTel(),
            ];
        }, $personnes);

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
            return $this->json(['error' => 'Personne non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer le bureau auquel la personne est associée
        $bureau = $personne->getBureau();

        // Vérifier si la personne est associée à un bureau
        if (!$bureau) {
            return $this->json(['error' => 'Personne non associée à un bureau.'], Response::HTTP_NOT_FOUND);
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
            return $this->json(['message' => 'Veuillez entrer un terme de recherche.'], Response::HTTP_BAD_REQUEST);
        }

        // Effectuer la recherche dans la base de données
        $personnes = $personneRepository->search($query);

        // Construire un tableau des résultats de recherche
        $results = array_map(function($personne) {
            return [
                'id' => $personne->getId(),
                'nom' => $personne->getNom(),
                'prenom' => $personne->getPrenom(),
            ];
        }, $personnes);

        // Retourner les résultats de recherche en JSON
        return $this->json($results);
    }
}
