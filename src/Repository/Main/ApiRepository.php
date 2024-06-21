<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Repository\Main;

use App\Entity\Main\Api;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Api>
 *
 * @method Api|null find($id, $lockMode = null, $lockVersion = null)
 * @method Api|null findOneBy(array $criteria, array $orderBy = null)
 * @method Api[]    findAll()
 * @method Api[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Api::class);
    }
}
