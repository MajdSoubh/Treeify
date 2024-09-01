<?php

namespace MS\Treeify;



trait HasTree
{


    private function buildTree($onlyParents = true)
    {


        $parentFieldName = property_exists($this, 'parentFieldName') ? $this->parentFieldName : 'parent_id';

        $modelId = $this->getAttribute('id');

        $result = null;

        if ($modelId)
        {
            $data = $this->where($parentFieldName, $modelId)->get();

            $data->add($this);

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

    public  function __call($name, $args)
    {


        if ($name === 'asTree')
        {

            return call_user_func_array([$this, 'buildTree'], $args);
        }
        return  parent::__call($name, $args);
    }
}
