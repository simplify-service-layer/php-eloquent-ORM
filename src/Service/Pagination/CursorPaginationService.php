<?php

namespace FunctionalCoding\ORM\Eloquent\Service\Pagination;

use FunctionalCoding\Service;

class CursorPaginationService extends Service
{
    public static function getArrBindNames()
    {
        return [];
    }

    public static function getArrCallbacks()
    {
        return [];
    }

    public static function getArrLoaders()
    {
        return [
            'result' => function ($cursor = '', $limit, $orderByArray, $query) {
                $wheres = [];
                $result = [];

                foreach ($orderByArray as $column => $direction) {
                    if (empty($cursor)) {
                        break;
                    }

                    if ('asc' == $direction) {
                        $wheres[] = [$column, '>', $cursor->{$column}];
                    } else {
                        $wheres[] = [$column, '<', $cursor->{$column}];
                    }
                }

                do {
                    $newQuery = clone $query;

                    foreach ($wheres as $i => $where) {
                        if ($where == end($wheres)) {
                            $newQuery->where($where[0], $where[1], $where[2]);
                        } else {
                            $newQuery->where($where[0], '=', $where[2]);
                        }
                    }

                    array_pop($wheres);

                    $list = $newQuery->get();
                    $limit = $limit - count($list);
                    $result = array_merge($result, $list->all());
                } while (0 != $limit && 0 != count($wheres));

                return $query->getModel()->newCollection($result);
            },
        ];
    }

    public static function getArrPromiseLists()
    {
        return [];
    }

    public static function getArrRuleLists()
    {
        return [];
    }

    public static function getArrTraits()
    {
        return [];
    }
}
