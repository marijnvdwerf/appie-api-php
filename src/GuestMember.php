<?php

namespace Appie;

class GuestMember implements MemberInterface
{
    private $memberID;

    function __construct()
    {
        $this->memberID = 40000000 + rand(0, 700000);
    }

    public function getMemberID()
    {
        return $this->memberID;
    }

    public function getPassword()
    {
        return '';
    }

    public function isAnonymous()
    {
        return true;
    }
}
