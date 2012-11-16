<?php
namespace Armetiz\FormExtensionBundle\Form\ChoiceList;

use Symfony\Bridge\Doctrine\Form\ChoiceList\ORMQueryBuilderLoader;

use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\Common\Collections\ArrayCollection;

class ORMSimpleLoader extends ORMQueryBuilderLoader {
    /**
     * Contains the entities directly associated to the field.
     * 
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $values;
    
    /**
     * Contains the query builder that builds the query for fetching the
     * entities
     *
     * This property should only be accessed through queryBuilder.
     *
     * @var Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;
    
    public function __construct($queryBuilder, $manager = null, $class = null)
    {
        // If a query builder was passed, it must be a closure or QueryBuilder
        // instance
        if (!($queryBuilder instanceof QueryBuilder || $queryBuilder instanceof \Closure)) {
            throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder or \Closure');
        }

        if ($queryBuilder instanceof \Closure) {
            $queryBuilder = $queryBuilder($manager->getRepository($class));

            if (!$queryBuilder instanceof QueryBuilder) {
                throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
            }
        }

        $this->queryBuilder = $queryBuilder;
    }
    
    public function setValues($values) {
        $this->values = $values;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntities()
    {
        if (null === $this->values) {
            $this->values = new ArrayCollection();
        }
        
        return $this->values;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntitiesByIds($identifier, array $values)
    {
        $qb = clone ($this->queryBuilder);
        $alias = current($qb->getRootAliases());
        $parameter = 'ORMQueryBuilderLoader_getEntitiesByIds_'.$identifier;
        $where = $qb->expr()->in($alias.'.'.$identifier, ':'.$parameter);

        return $qb->andWhere($where)
                  ->getQuery()
                  ->setParameter($parameter, $values, Connection::PARAM_STR_ARRAY)
                  ->getResult();
    }
}
