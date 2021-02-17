<?php

namespace App\Repository;

use App\Entity\EndpointHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EndpointHeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method EndpointHeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method EndpointHeader[]    findAll()
 * @method EndpointHeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndpointHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EndpointHeader::class);
    }
}
