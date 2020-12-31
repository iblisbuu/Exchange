<?php


class Access
{
    public function insertAccess($mb_id,$ac_location,$ac_device,$ac_result)
    {
        $db = new db();
        $query = "INSERT INTO
                    _access (mb_id,ac_ip,ac_location,ac_device,ac_result,ac_datetime)
                VALUES (
                    '$mb_id',
                    '" . $_SERVER["REMOTE_ADDR"] . "',
                    '$ac_location',
                    '$ac_device',
                    '$ac_result',
                    '" . time() . "'
                )";
        return $db->execute($query);
    }

    public function getAccess($mb_id,$fromRecord,$limits)
    {
        $db = new db();
        $query = "SELECT * FROM _access WHERE mb_id = '$mb_id' order by ac_datetime desc LIMIT $fromRecord,$limits";
        return $db->fetchAll($query);
    }

    public function getAccessCount($mb_id)
    {
        $db = new db();
        $query = "SELECT COUNT(*) AS cnt FROM _access WHERE mb_id = '$mb_id' order by ac_datetime desc";
        return $db->fetchAll($query);
    }

}