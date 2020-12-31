<?php

class Faq
{

    public function insertFaqCategory($data)
    {
        $db = new DB();
        $query = "INSERT INTO _faq_category
                SET ";
        $insertData = '';
        for ($i = 0; $i < count($data); $i++) {
            $key = array_keys($data)[$i];
            $value = array_values($data)[$i];
            $comma = ($i == 0) ? ' ' : ',';
            $insertData .= $comma . " {$key} = \"{$value}\"";
        }
        $query .= $insertData;
        return $db->execute($query);
    }

    public function insertFaq($data)
    {
        $db = new DB();
        $query = "INSERT INTO _faq
                SET faq_type = '{$data['faq_type']}'";
        unset($data['faq_type']);
        $insertData = '';
        for ($i = 0; $i < count($data); $i++) {
            $key = array_keys($data)[$i];
            $value = str_replace('\'', '\\\'', array_values($data)[$i]);
            $value = str_replace('"', '\\\"', $value);
            $insertData .= ", {$key} = '{$value}'";
        }
        $query .= $insertData;
        return $db->execute($query);
    }

    public function getFaqCategory($country)
    {
        $db = new DB();
        $query = "SELECT
                    fc_no,
                    fc_name_{$country},";
        if ($country == 'en') {
            $query .= "FROM_UNIXTIME(fc_datetime,'%Y-%m-%d %H:%i:%S') AS fc_datetime,
                       FROM_UNIXTIME(fc_updatetime,'%Y-%m-%d %H:%i:%S') AS fc_updatetime";
        } else {
            $query .= "(SELECT DATE_ADD(FROM_UNIXTIME(fc_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS fc_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(fc_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS fc_updatetime";
        }
        $query .= " FROM _faq_category
                WHERE fc_deletetime IS NULL
                AND fc_name_$country IS NOT NULL";
        return $db->fetchAll($query);
    }

    public function getFaqList($country, $type)
    {
        $db = new DB();
        $query = "SELECT
                    faq_no,
                    faq_q_{$country},
                    faq_a_{$country},";
        if ($country == 'en') {
            $query .= "FROM_UNIXTIME(faq_datetime,'%Y-%m-%d %H:%i:%S') AS faq_datetime,
                       FROM_UNIXTIME(faq_updatetime,'%Y-%m-%d %H:%i:%S') AS faq_updatetime";
        } else {
            $query .= "(SELECT DATE_ADD(FROM_UNIXTIME(faq_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS faq_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(faq_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS faq_updatetime";
        }
        $query .= " FROM _faq
                WHERE faq_deletetime IS NULL
                AND faq_q_{$country} IS NOT NULL
                AND faq_a_{$country} IS NOT NULL";
        if ($type) {
            $query .= " AND faq_type = {$type}";
        }
        return $db->fetchAll($query);
    }

    public function getAllFaqList($country, $search = '')
    {
        $db = new DB();
        $query = "SELECT
                    fc_no,
                    fc_name_{$country}
                FROM _faq_category
                WHERE fc_deletetime IS NULL AND fc_name_$country IS NOT NULL";

        $listQuery = "SELECT
                    faq_no,
                    faq_q_{$country},
                    faq_a_{$country},";
        if ($country == 'en') {
            $listQuery .= "FROM_UNIXTIME(faq_datetime,'%Y-%m-%d %H:%i:%S') AS faq_datetime,
                       FROM_UNIXTIME(faq_updatetime,'%Y-%m-%d %H:%i:%S') AS faq_updatetime";
        } else {
            $listQuery .= "(SELECT DATE_ADD(FROM_UNIXTIME(faq_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS faq_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(faq_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS faq_updatetime";
        }
        $listQuery .= " FROM _faq
                WHERE faq_deletetime IS NULL
                AND faq_q_{$country} IS NOT NULL
                AND faq_a_{$country} IS NOT NULL
                AND (1) ";
        if ($search) {
            $listQuery .= " AND (faq_q_{$country} LIKE '%{$search}%' OR faq_a_{$country} LIKE '%{$search}%')";
        }

        return $db->multiReformFetch($query, array('faqList'), array($listQuery), array(array('faq_type', 'fc_no')));
    }
}