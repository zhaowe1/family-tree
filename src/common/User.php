<?php

namespace FamilyTree\common;

class User
{
    const MAN = 1;
    const WOMAN = 2;

    public $id;
    public $name;
    public $sex;
    public $birthday;
    public $f_id = 0;
    public $m_id = 0;

    public function __construct($userData)
    {
        foreach ($userData as $k => $v) {
            $this->$k = $v;
        }
    }
}