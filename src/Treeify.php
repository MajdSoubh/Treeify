<?php

namespace Maso\Treeify;

use Illuminate\Support\Collection;

class Treeify
{
    /**
     * The name of the field that holds the parent ID.
     *
     * @var string
     */
    private $parentFieldName = 'parent_id';

    /**
     * Whether to include only top-level parents in the resulting tree.
     *
     * @var bool
     */
    private $onlyTopParents = true;

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

    /**
     * Create a tree structure from the given data.
     *
     * @param  \Illuminate\Support\Collection|array $data            The data to be treeified, as a collection or array.
     * @param  bool|null                            $onlyTopParents     If true, only top-level parents will be included in the tree.
     * @param  string|null                          $parentFieldName The name of the field that identifies the parent ID.
     * @return \Illuminate\Support\Collection                          The resulting tree structure.
     */
    public static function treeify(Collection|array $data, bool|null $onlyTopParents = null, bool|string $parentFieldName = null): Collection
    {
        // Create an instance of Treeify with optional parameters
        $instance = new self($onlyTopParents, $parentFieldName);

        // Process the data to build the tree structure
        $result = $instance->proccess($data);

        return $result;
    }

    /**
     * Process the data to build a tree structure.
     *
     * @param  \Illuminate\Support\Collection|array $data The data to be processed into a tree structure.
     * @return \Illuminate\Support\Collection        The resulting tree structure.
     */
    private function proccess(Collection|array $data): Collection
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
                    return true; // This line seems to be erroneous, typically this should not be here.

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
