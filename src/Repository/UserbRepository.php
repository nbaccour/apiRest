<?php

namespace App\Repository;

use App\Entity\Clientb;
use App\Entity\Userb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Userb|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userb|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userb[]    findAll()
 * @method Userb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserbRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userb::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Userb) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param Clientb $clientb
     * @return array
     */
    public function findByCustomer(Clientb $clientb): array
    {

        $query = $this->createQueryBuilder('u')
            ->where('u.clientb = :clientb')
            ->setParameter('clientb', $clientb)
            ->getQuery()
            ->getResult();

        if (is_array($query) and $query[0] instanceof Userb) {
            return $query;
        }
        return [];
//        throw new EntityNotFoundException("Liste des utilisateurs introuvable !");
    }

    /**
     * @param Clientb $clientb
     * @param $id
     * @return array
     */
    public function findByCustomerAndUser(Clientb $clientb, $id): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.clientb = :clientb AND u.id = :id')
            ->setParameter('clientb', $clientb)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if (is_array($query) and count($query) !== 0 and $query[0] instanceof Userb) {
            return $query;
        }
        return [];

//        throw new EntityNotFoundException("Utilisateur introuvable !");
    }

    // /**
    //  * @return Userb[] Returns an array of Userb objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Userb
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
