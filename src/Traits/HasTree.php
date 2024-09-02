<?php

namespace Maso\Treeify\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maso\Treeify\Classes\Treeify;

trait HasTree
{
    /**
     * Scope a query to transform a flat list of models into a hierarchical tree structure.
     *
     * This scope method uses the `Treeify` class to convert a flat new Collectionion of models into
     * a hierarchical tree structure based on parent-child relationships. It can be applied
     * to a query either on a specific model instance (to build a tree starting from that model)
     * or on the entire set of models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $onlyTopParents  If true, only top-level parents will be included in the resulting tree.
     * @return \Illuminate\Support\new Collectionion  The resulting tree structure.
     */
    public function scopeTreeify(Builder $query, bool $onlyTopParents = true): Collection
    {
        // Determine the parent field name, defaulting to 'parent_id'
        $parentFieldName = property_exists($this, 'parentFieldName') ? $this->parentFieldName : 'parent_id';

        // Determine if the current model instance is loaded with data
        $modelId = $this->exists ? $this->getKey() : false;

        $data = [];
        $result = null;

        if ($modelId)
        {
            // If the model instance exists, start building the tree from this model
            $data = new Collection([$this]);
            $parentIds = [$modelId];

            while (count($parentIds))
            {
                // Fetch descendants of the current model
                $descendants = self::whereIn($parentFieldName, $parentIds)->get();
                $data = $data->merge($descendants);
                $parentIds = $descendants->pluck($this->getKey())->all();
            }
        }
        else
        {
            // If no specific model instance, build the tree for all models
            $data = $this->all();
        }

        // Use the Treeify class to build the tree structure
        $result = Treeify::treeify($data, $onlyTopParents, $parentFieldName);

        return $result;
    }
}
