<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Repository\BureauRepository;
use App\Repository\PersonneRepository;
use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
//use FPDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use TCPDF;

#[AllowDynamicProperties] class PdfController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, BureauRepository $bureauRepository,PersonneRepository $personneRepository)
    {
        $this->entityManager = $entityManager;
        $this->bureauRepository = $bureauRepository;
        $this->personneRepository = $personneRepository;
    }

    // Cette méthode génère un PDF avec la liste de toutes les personnes
    public function generatePdf(): Response
    {
        // On récupère toutes les personnes de la base de données
        $personnes = $this->entityManager->getRepository(Personne::class)->findAll();

        // On trie les personnes par nom et prénom
        uasort($personnes, function ($a, $b) {
            $result = strcmp($a->getNom(), $b->getNom());
            if ($result === 0) {
                $result = strcmp($a->getPrenom(), $b->getPrenom());
            }
            return $result;
        });

        // On crée une nouvelle instance de FPDF
        $pdf = new TCPDF();


        // On ajoute une nouvelle page
        $pdf->AddPage();
        // Définir la police pour l'en-tête en gras
        $pdf->SetFont('helvetica', 'B', 16); // 'B' pour indiquer la police en gras
        // On ajoute un titre centré
        $pdf->Cell(0, 10, 'Liste des personnes', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(14, 10, 'Index', 1, 0);
        $pdf->Cell(30, 10, 'Nom', 1, 0);
        $pdf->Cell(50, 10, 'Prénom', 1, 0);
        $pdf->Cell(40, 10, 'Téléphone', 1, 0);
        $pdf->Cell(30, 10, 'Statut', 1, 1);
        $pdf->SetFont('helvetica', '', 12);

        // On définit la police et la taille du texte pour les données


        // On ajoute les données pour chaque personne
        $numero = 1;
        foreach ($personnes as $personne) {
            $pdf->Cell(14, 10, $numero, 1, 0);
            $pdf->Cell(30, 10, $personne->getNom(), 1, 0);
            $pdf->Cell(50, 10, $personne->getPrenom(), 1, 0);
            $pdf->Cell(40, 10, $personne->getNumeroTel(), 1, 0);
            $pdf->Cell(30, 10, $personne->getStatut(), 1, 1);
            $numero++;
        }

        // On génère le PDF et on l'envoie au navigateur
        $pdf->Output('liste_des_personnes.pdf', 'D' );

        return new Response();
    }

// Cette méthode génère un PDF avec la liste des personnes regroupées par bureau



    public function generateGroupePdf(): Response
    {
        // Récupération des bureaux uniques
        $bureaux = $this->bureauRepository->findAll();

        // Création d'une nouvelle instance de FPDF
        $pdf = new TCPDF();


        // Ajout d'une nouvelle page
        $pdf->AddPage();

        // Définition de la police et de la taille du texte pour le titre
        $pdf->SetFont('helvetica', 'B', 16); // 'B' pour indiquer la police en gras

        // Ajout d'un titre centré
        $pdf->Cell(0, 10, 'Liste des personnes par groupe', 0, 1, 'C');

        // Définition de la police et de la taille du texte pour les en-têtes de colonne

        //$pdf->SetFont('helvetica', 'B', 12); // 'B' pour indiquer la police en gras
        // Ajout des en-têtes de colonne
        $pdf->Cell(13, 10, 'Index', 1, 0);
        $pdf->Cell(30, 10, 'Nom', 1, 0);
        $pdf->Cell(50, 10, 'Prénom', 1, 0);
        $pdf->Cell(40, 10, 'Téléphone', 1, 0);
        $pdf->Cell(30, 10, 'Statut', 1, 1);
        $pdf->SetFont('helvetica', '', 12); // 'B' pour indiquer la police en gras

        // Définition de la police et de la taille du texte pour les données


        // Initialisation du numéro de ligne

        // Pour chaque bureau
        foreach ($bureaux as $bureau)
        {
            $numero = 1;
            // Récupération des personnes du bureau
            $id_bureau = $bureau->getId();

            // Récupérer les personnes liées à ce bureau
            $personnes = $this->personneRepository->findBy(['bureau' => $id_bureau]);
            $pdf->SetFont('helvetica', 'B', 12); // 'B' pour indiquer la police en gras
            // Ajout du nom du bureau
            $pdf->Cell(0, 10, 'Bureau : ' . $bureau->getNBureau(), 0, 1);
            $pdf->SetFont('helvetica', '', 12); // 'B' pour indiquer la police en gras
            // Pour chaque personne du bureau
            foreach ($personnes as $personne) {
                // Ajout des données de la personne
                $pdf->Cell(13, 10, $numero, 1, 0);
                $pdf->Cell(30, 10, $personne->getNom(), 1, 0);
                $pdf->Cell(50, 10, $personne->getPrenom(), 1, 0);
                $pdf->Cell(40, 10, $personne->getNumeroTel(), 1, 0);
                $pdf->Cell(30, 10, $personne->getStatut(), 1, 1);

                // Incrémentation du numéro de ligne
                $numero++;
            }
        }

        // Génération du PDF et envoi au navigateur
        $pdf->Output('liste_des_personnes_par_groupe.pdf' , 'D');

        return new Response();
    }




}
