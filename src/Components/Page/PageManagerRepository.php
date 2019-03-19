<?php


namespace App\Components\Page;


use App\Components\Language\CurrentLanguage;
use App\Components\Language\LanguageManager;
use App\Entity\Page;
use App\Entity\PageData;
use App\Entity\Term;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;

class PageManagerRepository
{
    /**
     * @var PageRepository
     */
    private $repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LanguageManager
     */
    private $languageManager;

    private $_entityName = Page::class;

    /**
     * PageManagerRepository constructor.
     * @param EntityManagerInterface $entityManager
     * @param LanguageManager $languageManager
     */
    public function __construct(EntityManagerInterface $entityManager, LanguageManager $languageManager)
    {
        $this->languageManager = $languageManager;
        $this->em = $entityManager;
        $this->repo = $entityManager->getRepository($this->_entityName);
    }

    public function findByTerms( Term $term, $page = 1, $limit = 10, $language = null ){
        if(is_null($language)){
            $language = CurrentLanguage::$language;
        }
        $qry = $this->em->createQueryBuilder()->select('p')->from($this->_entityName, 'p');
        $qry->leftJoin(PageData::class, 'pd', "WITH" , 'pd.page = p.id' );

        $qry->where('pd.category = :category and pd.language = :language');
        $qry->setParameter('category', $term);
        $qry->setParameter('language', $language);
        $qry->setMaxResults($limit);
        $qry->setFirstResult( ( $limit * $page ) - $limit );//10*2=20 - 10 = 0
        return $qry->getQuery()->getResult();
    }
}