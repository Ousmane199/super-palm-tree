<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }
    /**
     * Recherche les personnes dont le nom ou le prénom correspond à la requête.
     *
     * @param string $query Le texte de recherche
     * @return Personne[] Les personnes correspondantes
     */
    public function search(string $query): array
    {
        // Nettoyer et normaliser la requête
        $query = trim($query); // Supprimer les espaces superflus au début et à la fin
        $query = preg_replace('/\s+/', ' ', $query); // Remplacer les espaces multiples par un seul espace

        // Diviser la chaîne de requête en nom et prénom
        $parts = explode(' ', $query);
        $nom = isset($parts[0]) ? trim($parts[0]) : '';
        $prenom = isset($parts[1]) ? trim($parts[1]) : '';

        // Requête pour le nom
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.nom) LIKE :nom')
            ->setParameter('nom', strtolower($nom) . '%');

        // Si le prénom est fourni, ajoutez une condition pour le prénom
        if ($prenom !== '') {
            $queryBuilder->andWhere('LOWER(p.prenom) LIKE :prenom')
                ->setParameter('prenom', strtolower($prenom) . '%');
        } else {
            // Sinon, recherchez également par prénom si le prénom n'est pas fourni
            $queryBuilder->orWhere('LOWER(p.prenom) LIKE :nom')
                ->setParameter('nom', strtolower($nom) . '%');
        }

        // Exécutez la requête et retournez les résultats
        return $queryBuilder->getQuery()->getResult();
    }




    //    /**
    //     * @return Personne[] Returns an array of Personne objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Personne
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
