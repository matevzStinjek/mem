<?php

namespace App\Helpers;

use Doctrine\ORM\QueryBuilder;

class FilteringHelper {

    public static function applyRules(QueryBuilder $qb, array $params, array $rules) {
        foreach ($params as $key => $value) {
            if (array_key_exists($key, $rules)) {
                $qb = $rules[$key]($qb, $value);
            }
        }
        return $qb;
    }
}
