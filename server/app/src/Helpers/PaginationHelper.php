<?php

namespace App\Helpers;

use App\Http\Request;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationHelper {

    const DEFAULT_RESULTS_PER_PAGE = 100;

    public static function returnPage(QueryBuilder $qb, Request $request) {
        $params = $request->params;
        $paginator = new Paginator($qb->getQuery());

        $offset = max($params['page'] ?? 0, 0);
        $resultsPerPage = max($params['perPage'] ?? self::DEFAULT_RESULTS_PER_PAGE, 0);

        $paginator->getQuery()->setFirstResult($offset);
        $paginator->getQuery()->setMaxResults($resultsPerPage);

        return $paginator;
    }
}
