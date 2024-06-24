<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Repository\Main;

use App\Entity\Main\User;
use App\Exception\SqlException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function userPyramid(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = "
            WITH RECURSIVE user_pyramid AS (
                SELECT id, username, parent_id, account_type, code
                FROM user
                WHERE id = :id
                UNION ALL
                SELECT s.id, s.username, s.parent_id, s.account_type, s.code
                FROM user s
                         INNER JOIN user_pyramid ds ON s.id = ds.parent_id
            )
            SELECT * FROM user_pyramid ORDER BY account_type DESC;
        ";
        try {
            $rs = $conn->executeQuery($sql, ['id' => $user->getId()])->fetchAllAssociative();
            $rs = array_column($rs, 'username');
            array_unshift($rs, 'admin');
            return array_unique($rs);
        } catch (Exception $exception) {
            throw new SqlException($exception->getMessage(), ['id' => $user->getId()], $sql);
        }
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
