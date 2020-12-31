<?php

class News
{

    public function getDetailNews($country, $no)
    {
        $db = new DB();
        $query = "SELECT
                    nw_no, 
                    nw_topfix, 
                    nw_type, 
                    nw_title_$country, 
                    nw_content_$country, 
                    mb_id,";
        if ($country == 'en') {
            $query .= "FROM_UNIXTIME(nw_datetime,'%Y-%m-%d %H:%i:%S') AS nw_datetime,
                       FROM_UNIXTIME(nw_updatetime,'%Y-%m-%d %H:%i:%S') AS nw_updatetime,
                       FROM_UNIXTIME(nw_deletetime,'%Y-%m-%d %H:%i:%S') AS nw_deletetime";
        } else {
            $query .= "(SELECT DATE_ADD(FROM_UNIXTIME(nw_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(nw_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_updatetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(nw_deletetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_deletetime";
        }
        $query .= " FROM _news
                WHERE nw_deletetime IS NULL
                AND nw_title_$country IS NOT NULL
                AND nw_content_$country IS NOT NULL
                AND nw_no = $no";
        return $db->fetchAll($query);
    }

    public function getNewsTopList($country, $type = 'all')
    {
        $db = new DB();
        $query = "SELECT
                    nw_no, 
                    nw_topfix, 
                    nw_type, 
                    nw_title_$country, 
                    nw_content_$country, 
                    mb_id,";
        if ($country == 'en') {
            $query .= "FROM_UNIXTIME(nw_datetime,'%Y-%m-%d %H:%i:%S') AS nw_datetime,
                       FROM_UNIXTIME(nw_updatetime,'%Y-%m-%d %H:%i:%S') AS nw_updatetime,
                       FROM_UNIXTIME(nw_deletetime,'%Y-%m-%d %H:%i:%S') AS nw_deletetime";
        } else {
            $query .= "(SELECT DATE_ADD(FROM_UNIXTIME(nw_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(nw_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_updatetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(nw_deletetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_deletetime";
        }
        $query .= " FROM _news
                WHERE nw_deletetime IS NULL
                AND nw_title_$country IS NOT NULL
                AND nw_content_$country IS NOT NULL 
                AND nw_topfix = 'true' ";
        if ($type != 'all')
            $query .= "AND nw_type = '$type'";
        $query .= "ORDER BY nw_datetime DESC ";
        return $db->fetchAll($query);
    }

    public function getNewsList($country, $type = 'all', $limit = '')
    {
        $db = new DB();
        $query = "SELECT
                    nw_no, 
                    nw_topfix, 
                    nw_type, 
                    nw_title_$country, 
                    nw_content_$country, 
                    mb_id,";
        if ($country == 'en') {
            $query .= "FROM_UNIXTIME(nw_datetime,'%Y-%m-%d %H:%i:%S') AS nw_datetime,
                       FROM_UNIXTIME(nw_updatetime,'%Y-%m-%d %H:%i:%S') AS nw_updatetime,
                       FROM_UNIXTIME(nw_deletetime,'%Y-%m-%d %H:%i:%S') AS nw_deletetime";
        } else {
            $query .= "(SELECT DATE_ADD(FROM_UNIXTIME(nw_datetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_datetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(nw_updatetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_updatetime,
                       (SELECT DATE_ADD(FROM_UNIXTIME(nw_deletetime,'%Y-%m-%d %H:%i:%S'), INTERVAL 9 HOUR)) AS nw_deletetime";
        }
        $query .= " FROM _news
                WHERE nw_deletetime IS NULL
                AND nw_title_$country IS NOT NULL
                AND nw_content_$country IS NOT NULL 
                AND nw_topfix = 'false' ";
        if ($type != 'all')
            $query .= "AND nw_type = '$type'";
        $query .= "ORDER BY nw_datetime DESC ";
        if ($limit) {
            $query .= "LIMIT ${limit}";
        }
        return $db->fetchAll($query);
    }

    public function updateNews($data)
    {
        $db = new DB();
        $nw_no = $data['nw_no'];
        $data = array_diff_key($data, array('nw_no' => ""));
        $updateQuery = '';
        for ($i = 0; $i < count($data); $i++) {
            $update = (array_values($data)[$i] != NULL) ?
                "'" . array_values($data)[$i] . "'" : 'NULL ';
            $updateQuery .=
                ($i == 0 || $i == count($data) ? '' : ',') . array_keys($data)[$i] . " = " . $update;
        }
        $query = "UPDATE
                    _news
                SET ";
        $query .= $updateQuery;
        $query .= " WHERE nw_no = '$nw_no'";
        return $db->execute($query);
    }

    public function insertNews($nw_type, $mb_id, $nw_title_ko, $nw_title_en, $nw_title_ja, $nw_title_ch, $nw_content_ko,
                               $nw_content_en, $nw_content_ja, $nw_content_ch, $nw_datetime, $nw_topfix)
    {
        $db = new db();
        $query = "INSERT INTO
                    _news 
                SET nw_type = '{$nw_type}',
                    mb_id = '{$mb_id}',
                    nw_datetime = '{$nw_datetime}'
                ";

        $query .= trim($nw_title_ko) != '' ? ", nw_title_ko = '{$nw_title_ko}'" : '';
        $query .= trim($nw_title_en) != '' ? ", nw_title_en = '{$nw_title_en}'" : '';
        $query .= trim($nw_title_ja) != '' ? ", nw_title_ja = '{$nw_title_ja}'" : '';
        $query .= trim($nw_title_ch) != '' ? ", nw_title_ch = '{$nw_title_ch}'" : '';
        $query .= trim($nw_content_ko) != '' ? ", nw_content_ko = '{$nw_content_ko}'" : '';
        $query .= trim($nw_content_en) != '' ? ", nw_content_en = '{$nw_content_en}'" : '';
        $query .= trim($nw_content_ja) != '' ? ", nw_content_ja = '{$nw_content_ja}'" : '';
        $query .= trim($nw_content_ch) != '' ? ", nw_content_ch = '{$nw_content_ch}'" : '';
        $query .= trim($nw_topfix) != '' ? ", nw_topfix = '{$nw_topfix}'" : '';

        return $db->execute($query);
    }

}