<?php

namespace App\Repository;

use App\Entity\Product;
use App\Services\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Product[]    findBySearch(Search $search)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    
    public function findBySearch(Search $search)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p','c')
            ->join('p.category', 'c');

        if(!empty($search->name)){
            $lower_str = strtolower($search->name);
            $query = $query
                ->andWhere('LOWER(p.name) LIKE :name')
                ->setParameter('name', "%{$lower_str}%");
        }

        if(!empty($search->category)){
            $query = $query
                ->andWhere('p.category IN (:cat)')
                ->setParameter('cat', $search->category);
        }

        // if($search->user){
        //     $query = $query
        //         ->andWhere('p.owner=:user')
        //         ->setParameter('user', $search->user);
        // }
            
        // ->orderBy('p.id', 'ASC')
        // ->setMaxResults(10)
        
        return $query->getQuery()->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
