<?php

namespace Maso\Treeify\Classes;

use Illuminate\Support\Collection;
use Maso\Treeify\Abstracts\TreeifyAbstract;

class Treeify extends TreeifyAbstract
{
    /**
     * Constructor
     *
     * @param  bool|null   $onlyTopParents     If true, only top-level parents will be included in the tree.
     * @param  string|null $parentFieldName The name of the field that identifies the parent ID.
     */
    public function __construct(bool|null $onlyTopParents = null, bool|string $parentFieldName = null)
    {
        // Set the parent field name if provided
        !isset($parentFieldName) ?: $this->parentFieldName = $parentFieldName;

        // Set the only parents flag if provided
        !isset($onlyTopParents) ?: $this->onlyTopParents = $onlyTopParents;
    }

    public static function treeify(Collection|array $data, bool|null $onlyTopParents = null, bool|string $parentFieldName = null): Collection
    {
        // Create an instance of Treeify with optional parameters
        $instance = new self($onlyTopParents, $parentFieldName);

        // Process the data to build the tree structure
        $result = $instance->proccess($data);

        return $result;
    }


    protected function proccess(Collection|array $data): Collection
    {
        $result = new Collection();

        // Container to bind parent IDs to their children
        $pIDToChildren = new Collection();

        foreach ($data as $item)
        {
            $parentId = $item->getAttribute($this->parentFieldName);

            if ($parentId)
            {
                if ($pIDToChildren->has($parentId))
                {
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
            // Check if this item is a parent and assign its children to it.
            if ($pIDToChildren->has($item->getKey()))
            {
                $item->setAttribute('children', $pIDToChildren->get($item->getKey()));
            }

            // If sub-parents have to be included, add it to the result
            if (!$this->onlyTopParents)
            {
                $result->push($item);
            }
            // Else if it's the top-level parent, add it to the result
            else if (!$item->getAttribute($this->parentFieldName))
            {
                $result->push($item);
            }
        }

        return $result;
    }
}
