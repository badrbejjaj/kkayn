<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected $queryName;

    public function __construct(ManagerRegistry $registry, $entityClass, string $queryName = 'qry')
    {
        parent::__construct($registry, $entityClass);

        $this->queryName = $queryName;
    }

    /**
     * @param string $search
     * @return int
     */
    public function getCountAll(?array $search)
    {
        $qb = $this->createQueryBuilder($this->queryName)
            ->select('COUNT(DISTINCT ' . $this->queryName . ') as cnt_' . $this->queryName);

        $qb = $this->queryAll($qb, $search);

        $datas = $qb->getQuery()
            ->getResult();

        return $datas[0]['cnt_' . $this->queryName];
    }

    public function getAll(?int $limit = null, ?int $offset = null, ?array $search = null, ?array $sort = null, ?string $groupBy = null)
    {
        $qb = $this->createQueryBuilder($this->queryName);

        if ($groupBy != null && $groupBy == 'id') {
            $qb->groupBy($this->queryName . '.id');
        }

        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        $qb = $this->queryAll($qb, $search);
        $qb = $this->sortAll($qb, $sort);

        $datas = $qb->getQuery()->getResult();

        return $datas;
    }

    abstract public function queryAll(QueryBuilder $qb, ?array $search);

/*     public function addFilterActive(QueryBuilder $qb)
    {
        return $qb->andWhere($this->queryName . '.active = 1');
    } */

    /**
     * Attention, ne convient que pour les filtres de type int (=) ou string (like), pour le reste faire du code personnalisé
     */
    public function addFilterSearch(QueryBuilder $qb, ?array $search)
    {
        if (isset($search)) {
            $operator = "";
            foreach ($search as $searchKey => $searchValue) {
                if ($search[$searchKey] !== null && !empty($searchValue)) {
                    if (is_numeric($searchValue)) {
                        // si le type de la valeur recherchée est un numérique (int, float, decimal)... on utilisera le =
                        $operator = '=';
                    } else if (is_string($searchValue)) {
                        if (ctype_digit($searchValue)) {
                            // si le string est enfait un int
                            $operator = '=';
                        } else {
                            // on vérifie qu'il n'est pas vide
                            if (isset($search[$searchKey]) && !empty($search[$searchKey])) {
                                $operator = 'like';
                                $searchValue = '%' . $searchValue . '%';
                            }
                        }
                    }

                    $qb->andWhere($this->queryName . '.' . $searchKey . ' ' . $operator . ' :' . $searchKey)
                        ->setParameter($searchKey, $searchValue);
                }
            }
        }

        return $qb;
    }

    protected function sortAll(QueryBuilder $qb, ?array $sort): QueryBuilder
    {
        if (isset($sort)) {
            foreach ($sort as $sortField => $sortDirection) {

                if (!empty($sortDirection) && !empty($sortField)) {
                    $field = $sortField;
                    // si le champs sur lequel on doit appliquer le filtre contient un point, c'est une table de jointure
                    if (strpos($sortField, '.') !== false) {
                        $sortFieldSplit = explode('.', $sortField);

                        $joinTable = $sortFieldSplit[0];
                        $field = $sortFieldSplit[1];
                        $aliasJoinTable = substr($joinTable, 0, 1);

                        // on joint la table
                        $qb->leftJoin($this->queryName . '.' . $joinTable, $aliasJoinTable);
                        // on fait le tri sur la table de jointure
                        $qb->addOrderBy($aliasJoinTable . ' . ' . $field, $sortDirection);
                    } else {
                        // tri classique
                        $qb->addOrderBy($this->queryName . '.' . $field, $sortDirection);
                    }
                }

                // print_r($qb->getQuery()->getSQL());
                // die;
            }
        }
        return $qb;
    }

    public function isFieldUnique(string $field, string $value, array $options, int $id = null): bool
    {
        $args = [
            $field => $value,
        ];

        foreach ($options as $key => $option) {
            $args[$key] = $option;
        }

        $obj = $this->findOneBy($args);

        return $obj === null || $obj->getId() === $id;
    }

}
