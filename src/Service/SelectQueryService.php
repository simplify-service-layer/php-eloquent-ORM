<?php

namespace FunctionalCoding\Illuminate\Service;

use FunctionalCoding\Service;

class SelectQueryService extends Service
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
            'result' => function ($query) {
                $columns = $query->getQuery()->columns;
                $model = $query->getModel();
                $selectQuery = $model->query();
                $query = (clone $query)->select($model->getKeyName());
                $ids = $query->get()->modelKeys();

                $selectQuery->getQuery()->select($columns);
                $selectQuery->whereIn($model->getKeyName(), $ids);

                if (!empty($ids)) {
                    if ('string' == $model->getKeyType()) {
                        foreach ($ids as $i => $id) {
                            $ids[$i] = '\''.$id.'\'';
                        }
                    }

                    $selectQuery->orderByRaw('FIELD('.$model->getKeyName().','.implode(',', $ids).')');
                }

                return $selectQuery;
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
