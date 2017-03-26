<?php

class nflDBhandler
{
    protected $dbinstance;



    public function __construct(PDO $dbinstance) // passed a pdo DB file to construct the object
    {
        $this->dbinstance = $dbinstance;  //each object's functions should apply to that object and not others
    }

    public function getPlayer()
    {
        $query = $this->dbinstance->prepare();
        $query->bindValue();
        $query->execute();
    }

    public function getPlaysFromPlayer()
    {
        $query = $this->dbinstance->prepare();
        $query->bindValue();
        $query->execute();
    }
}
?>