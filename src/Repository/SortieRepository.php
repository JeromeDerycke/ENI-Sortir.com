<?php

namespace App\Repository;

use App\Controller\SortieController;
use App\Entity\Sortie;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\String_;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function findByFiltres($filtres, $userConnected)
    {

        // D'abord, je créé ma requête
        $qb = $this->createQueryBuilder('s');

        /* Pour tester le nom du campus, tu ne peux malheureusement pas tester s.campus
        * car campus est un objet qui contient plusieurs données.
         * On aimerait pouvoir faire un ->andWhere('s.campus.nom = :nom') mais même ça, ça ne fonctionne pas
         * Dans le cas d'une entité (campus est une entité) il faut faire une jointure avec join
         * Et ton test sur le nom ne peut pas être fait sur $filtres['campus'] mais sur $filtres['campus']->getNom()
         * (Oui, car $filtres['campus'], c'est un objet campus, pas le nom d'un campus. Un petit
         * dd($filtres['campus']) te montre comment est fait l'objet.
         */

        if($filtres['campus'] != null){
            $qb->join('s.campus','campus')
                ->andWhere('campus.nom = :nom')
                ->setParameter('nom', $filtres['campus']->getNom());
        }

        if(!empty($filtres['nom'])){
           $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%'.$filtres['nom'].'%');
        }



        if($filtres['date1']) {
            $qb->andWhere('s.dateHeureDebut >= :date1')
                ->setParameter('date1',$filtres['date1']);
        }


        if($filtres['date2']){
            $qb->andWhere('s.dateHeureDebut <= :date2')
            ->setParameter('date2', $filtres['date2']);
        }


        /* Pour un champ disons plus classique comme une checkbox, c'est plus simple
        * on test si la case est cochée
        */

        if($filtres['organisateur']){

            // on ajoute une condition au query builder (qb) si c'est le cas
            $qb->andWhere('s.organisateur = :orga')
            ->setParameter('orga',$userConnected); // regarde dans les paramètres de la fonction

        }

        if($filtres['inscrit']) {
            $qb->join('s.participants','participant')
                ->andWhere('participant.id = :user')
                ->setParameter('user', $userConnected);
        }

        if($filtres['nonInscrit']) {
           $query = $this-> createQueryBuilder('sort');
           $queryTest = $query ->select('sort')
               ->join('sort.participants', 'part')
               ->andWhere('part.id = :use')
               ->getQuery();

            $qb->andWhere($qb->expr()->notIn('s',$queryTest->getDQL()))
                ->setParameter('use', $userConnected);
        }

        $datePasse = new \DateTime();
        if($filtres['sortiePassees']) {
            $qb->andWhere('s.dateHeureDebut < :datePasse')
                ->setParameter('datePasse', $datePasse);
        }

        $date = new \DateTime();
        $date->modify('-1 month');
        $qb->andWhere('s.dateHeureDebut > :date')
            ->setParameter('date',$date);




        // ce qu'il faut retenir, c'est qu'une entité, c'est pas forcément le nom ou l'id de l'entité
        // il faut comparer ce qui est comparable. Un s.campus, c'est pas le nom du campus mais un objet.
        // Je te laisse avancer sur la suite ;)


        // je retourne mes résultats
        return $qb->getQuery()->getResult();

    }

    public function findBySortieDate()
    {
        $date = new \DateTime();
        $date->modify('-1 month');

        $qb=$this->createQueryBuilder('s');

        $qb->andWhere('s.dateHeureDebut > :date')
            ->setParameter('date',$date)
            ->addOrderBy('s.dateHeureDebut', 'ASC');


        return $qb->getQuery()->getResult();
    }

    public function findBySortieSmarphone($userConnected)
    {
        $date = new \DateTime();
        $date->modify('-1 month');

        $qb=$this->createQueryBuilder('s');

        $qb->andWhere('s.dateHeureDebut > :date')
            ->setParameter('date',$date)
            ->andWhere('s.campus = :campusUser')
            ->setParameter('campusUser', $userConnected)
            ->addOrderBy('s.dateHeureDebut', 'ASC');


        return $qb->getQuery()->getResult();
    }



    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
