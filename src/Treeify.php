<?php

namespace MS\Treeify;

use Illuminate\Support\Collection;

class Treeify
{

    private $parentFieldName = 'parent_id';

    private $onlyParents = true;

    public function __construct($onlyParents = null, $parentFieldName = null)
    {
        !isset($parentFieldName) ?: $this->parentFieldName = $parentFieldName;
        !isset($onlyParents) ?: $this->onlyParents = $onlyParents;
    }
    public static function treeify($data, $onlyParents = null, $parentFieldName = null)
    {
        $instance = new self($onlyParents, $parentFieldName);

        $result = $instance->proccess($data);

        return $result;
    }

    private function proccess($data)
    {
        $result = new Collection();

        // Container to bound between parents id and it's children
        $pIDToChildren = new Collection();
        foreach ($data as $item)
        {
            $parentId = $item->getAttribute($this->parentFieldName);

            if ($parentId)
            {

                if ($pIDToChildren->has($parentId))
                {
                    return true;

                    $pIDToChildren->get($parentId)->push($item);
                }
                else
                {
                    $pIDToChildren->put($parentId, new Collection([$item]));
                }
            }
        }

        foreach ($data as $item)
        {

            // Check if this item is a parent and assign it's children to it.
            if ($pIDToChildren->has($item->getKey()))
            {

                $item->setAttribute('children', $pIDToChildren->get($item->getKey()));
            }

            // If sub-parents have to be included also add it to the result
            if (!$this->onlyParents)
            {
                $result->push($item);
            }
            // else if it's the top parent then add it to the result.
            else if (!$item->getAttribute($this->parentFieldName))
            {

                $result->push($item);
            }
        }

        return $result;
    }
}
