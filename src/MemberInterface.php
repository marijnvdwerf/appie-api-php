<?php

namespace Appie;


interface MemberInterface
{
    /**
     * @return string
     */
    public function getMemberID();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return boolean
     */
    public function isAnonymous();
}
