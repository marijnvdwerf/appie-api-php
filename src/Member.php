<?php

namespace Appie;


class Member implements MemberInterface
{
    private $ID;
    private $password;

    function __construct($id, $password)
    {
        $this->ID = $id;
        $this->password = $password;
    }

    public function getMemberID()
    {
        return $this->ID;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isAnonymous()
    {
        return false;
    }
}
