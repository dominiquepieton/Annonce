<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
    * @return Annonce[] Returns an array of Annonce objects
    */
    public function findByCategorie($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.categorie = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByTitle($search)
    {
        return $this->createQueryBuilder('a')
            ->select('a.title')
            ->andWhere('a.title LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();

    }


    public function findByUser($search)
    {
        return $this->createQueryBuilder('a')
            ->select('a.user')
            ->andWhere('a.user LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();

    }
    

    public function findAllVisibleQuery(AnnonceSearch $search)
    {
        $query = $this->findAllqb();

        if($search->getTitle())
        {
            $query = $query
                ->andWhere('a.title LIKE :title')
                ->setParameter('title', '%'.$search->getTitle().'%');
        }

        /*if($search->getPrice())
        {
            $query = $query
                ->andWhere('a.user LIKE :user')
                ->setParameter('user', '%'.$search->getUser().'%');
        }*/

        return $query->getQuery()->getResult();
    }


    private function findAllqb() : QueryBuilder
    {
        return $this->createQueryBuilder('a');
            //->andwhere('a.idArticle > 0');

    }

    /*
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
