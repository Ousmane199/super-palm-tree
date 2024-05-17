<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/controller/personne')]
class AdminControllerPersonneController extends AbstractController
{
    #[Route('/', name: 'app_admin_controller_personne_index', methods: ['GET'])]
    public function index(PersonneRepository $personneRepository): Response
    {
        $personnes = $personneRepository->findAll();

        return $this->render('admin_controller_personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }
    #[Route('/listeIndex', name: 'app_admin_controller_personne_liste_index', methods: ['GET'])]
    public function listeIndex(PersonneRepository $personneRepository): Response
    {
        $personnes = $personneRepository->findAll();

        return $this->render('admin_controller_personne/listeIndex.html.twig', [
            'personnes' => $personnes,
        ]);
    }

    #[Route('/new', name: 'app_admin_controller_personne_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $personne = new Personne();
    $form = $this->createForm(PersonneType::class, $personne);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier si le bureau a atteint sa capacité maximale
        $bureau = $personne->getBureau();
        if ($bureau) {
            $repository = $entityManager->getRepository(Personne::class);
            $queryBuilder = $repository->createQueryBuilder('p');
            $queryBuilder
                ->where('p.bureau = :bureau')
                ->setParameter('bureau', $bureau);
            $query = $queryBuilder->getQuery();
            $countPersons = count($query->getResult());//On compte le nombre de bureau 
    
            if ($countPersons >= $bureau->getNombPlace()) {
                $this->addFlash('error', 'Le bureau a atteint sa capacité maximale de personnes.');
                return $this->redirectToRoute('app_admin_controller_personne_index');
            }
        }
    
        $entityManager->persist($personne);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_admin_controller_personne_index', [], Response::HTTP_SEE_OTHER);
    }
    

    return $this->render('admin_controller_personne/new.html.twig', [
        'personne' => $personne,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_admin_controller_personne_show', methods: ['GET'])]
    public function show(Personne $personne): Response
    {
        return $this->render('admin_controller_personne/show.html.twig', [
            'personne' => $personne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_controller_personne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_controller_personne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_controller_personne/edit.html.twig', [
            'personne' => $personne,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_controller_personne_delete', methods: ['POST'])]
    public function delete(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($personne);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_admin_controller_personne_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
