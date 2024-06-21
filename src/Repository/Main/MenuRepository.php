<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Repository\Main;

use App\Entity\Main\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 *
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function menus()
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where($qb->expr()->isNull('m.parentId'));
        $qb->orderBy('m.orderNum', 'asc');
        return $qb->getQuery()->getResult();
    }
}