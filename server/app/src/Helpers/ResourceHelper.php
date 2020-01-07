<?php

namespace App\Helpers;

use App\Exceptions\UserException;

class ResourceHelper {

    public static function mapValues($entity, array $resourceMap, $fields = null) {
        $fields = $fields ?? array_keys($resourceMap);
        $fields = array_intersect($fields, array_keys($resourceMap)); // remove invalid fields

        error_log(json_encode($fields));

        $ret = [];
        foreach($fields as $field) {
            $ret[$field] = $resourceMap[$field]($entity);
        }

        if (empty($ret)) {
            throw new UserException('Specify at least one valid field.');
        }
        return $ret;
    }
}
