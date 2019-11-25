<?php

class Vmw_Wc_Product_Dbo
{
    protected $data;

    public static function create()
    {
        return new static();
    }

    public function setData($key, $value)
    {
        $this->data[] = [
            'name'      => $key,
            'contents'  => $value,
        ];
    }

    public function getData()
    {
        return $this->data;
    }
}
