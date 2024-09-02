<?php

namespace Maso\Treeify\Abstracts;

use Illuminate\Support\Collection;

abstract class TreeifyAbstract
{
    /**
     * The name of the field that holds the parent ID.
     *
     * @var string
     */
    protected $parentFieldName = 'parent_id';

    /**
     * Whether to include only top-level parents in the resulting tree.
     *
     * @var bool
     */
    protected $onlyTopParents = true;

    /**
     * Create a tree structure from the given data.
     *
     * @param  \Illuminate\Support\Collection|array $data            The data to be treeified, as a collection or array.
     * @param  bool|null                            $onlyTopParents     If true, only top-level parents will be included in the tree.
     * @param  string|null                          $parentFieldName The name of the field that identifies the parent ID.
     * @return \Illuminate\Support\Collection                          The resulting tree structure.
     */
    public abstract static function treeify(Collection|array $data, bool|null $onlyTopParents = null, bool|string $parentFieldName = null): Collection;

    /**
     * Process the data to build a tree structure.
     *
     * @param  \Illuminate\Support\Collection|array $data The data to be processed into a tree structure.
     * @return \Illuminate\Support\Collection        The resulting tree structure.
     */
    protected abstract function proccess(Collection|array $data): Collection;
}
