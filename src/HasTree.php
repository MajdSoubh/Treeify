<?php

namespace MS\Treeify;

use Illuminate\Database\Eloquent\Builder;

trait HasTree
{


    public function scopeTreeify(Builder $query, $onlyParents = true)
    {

        $parentFieldName = property_exists($this, 'parentFieldName') ? $this->parentFieldName : 'parent_id';

        $modelId = $this->exists ? $this->getKey() : false;


        // return $query;

        $result = null;

        if ($modelId)
        {
            $data = collect([$this]);
            $parentIds = [$modelId];
            while (count($parentIds))
            {

                $decendents = self::whereIn($parentFieldName, $parentIds)->get();
                $data = $data->merge($decendents);
                $parentIds = $decendents->pluck('id');
            }

            $result = Treeify::treeify($data, $onlyParents, $parentFieldName);

            if (count($result) == 0)
            {
                $result->add($this);
            }
        }
        else
        {
            $data = $this->all();

            $result =  Treeify::treeify($data, $onlyParents, $parentFieldName);
        }

        return $result;
    }
}
