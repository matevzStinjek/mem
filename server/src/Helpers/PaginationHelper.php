<?php

namespace App\Helpers;

use App\Http\Request;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationHelper {

    const DEFAULT_RESULTS_PER_PAGE = 100;
    const MAX_RESULTS_PER_PAGE = 500;

    public static function returnPage(QueryBuilder $qb, Request $request) {
        $paginator = new Paginator($qb->getQuery());
        $params = $request->params;

        $offset = max($params['page'] ?? 0, 0);
        $resultsPerPage = $params['perPage'] ?? self::DEFAULT_RESULTS_PER_PAGE;
        $resultsPerPage = max(0, min($resultsPerPage, self::MAX_RESULTS_PER_PAGE));

        $paginator->getQuery()->setFirstResult($offset);
        $paginator->getQuery()->setMaxResults($resultsPerPage);

        return $paginator;
    }
}
