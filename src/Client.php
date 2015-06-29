<?php

namespace Appie;

class Client
{
    private $name;
    private $version;
    private $digestKey;

    public function  __construct($name, $version, $digestKey)
    {
        $this->name = $name;
        $this->version = $version;
        $this->digestKey = $digestKey;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getDigestKey()
    {
        return $this->digestKey;
    }
}
