<?php

namespace MS\Treeify;


class Treeify
{

    private $parentFieldName = 'parent_id';

    private $onlyParents = true;

    public function __construct($parentFieldName = null, $onlyParents = null)
    {
        !$parentFieldName ?? $this->parentFieldName = $parentFieldName;
        !$onlyParents ?? $this->$onlyParents = $onlyParents;
    }

    public function __invoke($data)
    {

        $result = $this->proccess($data);

        return $result;
    }

    public static function treeify($data, $onlyParents = null, $parentFieldName = null)
    {
        $instance = new self($parentFieldName);


        !isset($parentFieldName) ?: $instance->parentFieldName = $parentFieldName;
        !isset($onlyParents) ?: $instance->onlyParents = $onlyParents;

        $result = $instance->proccess($data);

        return $result;
    }

    private function proccess($data)
    {

        $result = collect();

        foreach ($data as $item)
        {
            $children = $this->getChildren($data, $item->id);

            if (count($children))
            {
                $item->setAttribute('children', $children);

                $result->add($item);

                continue;
            }
            if (!$this->onlyParents)
            {

                $result->add($item);
            }
        }

        return $result;
    }
    private function getChildren($data, $id)
    {
        $result = collect();

        foreach ($data as $item)
        {
            if ($item->getAttribute($this->parentFieldName) === $id)
            {
                $result->add($item);
            }
        }

        return $result;
    }
}
