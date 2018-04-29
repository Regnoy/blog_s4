<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;
use App\Entity\Term;

class PageRepository extends EntityRepository {


  public function findPages( $page = 1, $limit = 10 ){
    $qry = $this->createQueryBuilder('p');
    $qry->setMaxResults($limit);
    $qry->setFirstResult( ( $limit * $page ) - $limit );//10*2=20 - 10 = 0
    return $qry->getQuery()->getResult();
  }

  public function findByTerms( Term $term, $page = 1, $limit = 10 ){

    $qry = $this->createQueryBuilder('p');
    $qry->where('p.category = :category');
    $qry->setParameter('category', $term);
    $qry->setMaxResults($limit);
    $qry->setFirstResult( ( $limit * $page ) - $limit );//10*2=20 - 10 = 0
    return $qry->getQuery()->getResult();
  }
  public function countPageByTerms( Term $term){
    $qry = $this->createQueryBuilder('p')->select('count(p.id)');
    $qry->where('p.term' , $term);
    $result = $qry->getQuery()->getOneOrNullResult();//[ 1 => 3]
    return $result ? array_shift($result) : 0;
  }


  public function countPage(){
    $qry = $this->createQueryBuilder('p')->select('count(p.id)');
    $result = $qry->getQuery()->getOneOrNullResult();//[ 1 => 3]
    return $result ? array_shift($result) : 0;
  }

  public function findByWord( $word ){
    $qry = $this->createQueryBuilder('p')->where('p.body LIKE :word');
    $qry->setParameter('word' , '%'.$word.'%');
    $qry->setMaxResults(20);
    return $qry->getQuery()->getResult();
  }

}