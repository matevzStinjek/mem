<?php

namespace App\Helpers;

use Doctrine\ORM\QueryBuilder;

class SortingHelper {

    public static function orderByRules(QueryBuilder $qb, array $params, array $rules) {
        $sortingParams = self::mapRules($params);

        foreach ($sortingParams as $prop => $direction) {
            if (array_key_exists($prop, $rules)) {
                $qb = $rules[$prop]($qb, $direction);
            }
        }
        return $qb;
    }
    
    private static function mapRules($params) {
        $sortingParams = isset($params['sort']) ? explode(',', $params['sort']) : [];

        $rules = [];
        foreach ($sortingParams as $param) {
            $direction = strpos($param, '-') === 0 ? 'ASC' : 'DESC';
            $param = str_replace('-', '', $param);
            $rules[$param] = $direction;
        }
        return $rules;
    }
}
