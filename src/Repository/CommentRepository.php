<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;
use App\Entity\Page;

class CommentRepository extends EntityRepository {

  public function findLastComments( Page $page, $limit = 20 ){
    $qry = $this->createQueryBuilder('c');
    $qry->where('c.page = :page');
    $qry->setParameter('page', $page);
    $qry->setMaxResults($limit);
    $qry->orderBy('c.id' , 'DESC');
    return $qry->getQuery()->getResult();
  }

  public function findComments(Page $page, $pager = 1, $limit = 10 ){
    $qry = $this->createQueryBuilder('c');
    $qry->where('c.page = :page')->setParameter('page', $page);
    $qry->setMaxResults($limit);
    $qry->setFirstResult( ( $limit * $pager ) - $limit );//10*2=20 - 10 = 0
    return $qry->getQuery()->getResult();
  }


  public function countComments( Page $page ){
    $qry = $this->createQueryBuilder('c')->select('count(c.id)');
    $qry->where('c.page = :page')->setParameter('page', $page);
    $result = $qry->getQuery()->getOneOrNullResult();//[ 1 => 3]
    return $result ? array_shift($result) : 0;
  }
}