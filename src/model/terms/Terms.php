<?php

class Terms
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function getTerms($country, $type)
    {
        $query = "SELECT *,";
        if ($country == 'en') {
            $query .= "FROM_UNIXTIME(te_datetime,'%Y-%m-%d %H:%i:%S') AS te_datetime,
                       FROM_UNIXTIME(te_updatetime,'%Y-%m-%d %H:%i:%S') AS te_updatetime";
        } else {
            $query .= "(SELECT DATE_ADD(FROM_UNIXTIME(te_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS te_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(te_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS te_updatetime";
        }
        $query .= " FROM _terms WHERE te_title = '{$type}'";
        return $this->db->fetchAll($query);
    }
}