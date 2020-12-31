<?php

class Member
{

    /**
     * 회원조회 ( mb_no 또는 mb_id )
     * @param string $type mb_no 또는 mb_id
     * @param string $value 검색할 값
     * @return array|bool|mixed
     */
    public function getMember($type, $value)
    {
        $db = new db();

        $query = "SELECT * FROM _members WHERE ";
        $query .= " $type = '$value'"; // where

        $result = $db->fetchAll($query);
        if (!empty($result))
            $result = $result[0];
        return $result;
    }

    /**
     * 회원 Sequence 조회
     * @param $type
     * @param $value
     * @return bool
     */
    public function getMemberSeq($type, $value)
    {
        $db = new db();
        $query = "SELECT mb_no FROM _members WHERE $type = '$value'";
        $result = $db->fetchAll($query);
        return (count($result)) ? $result[0]->mb_no : false;
    }

    /**
     * 회원 가입
     * @param $mb_id
     * @param $mb_password
     * @return bool
     */
    public function insertMember($mb_id, $mb_password, $mb_marketing)
    {
        $nowTime = time();
        $db = new db();
        $query = "INSERT INTO _members SET mb_id = '{$mb_id}', mb_password = '{$mb_password}', mb_datetime = '{$nowTime}', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_marketing = '{$mb_marketing}'";

        // 테스트위해 각 포인트를 지급함
        //$query .= ", mb_btc = '10000000', mb_eth = '10000000', mb_fvc = '10000000', mb_rbto = '10000000', mb_usdt = '10000000'";

        return $db->execute($query);
    }

    /**
     * 비밀번호 변경
     * @param $mb_id
     * @param $mb_password
     * @return bool
     */
    public function updateMemberPassword($mb_id, $mb_password)
    {
        $db = new db();
        $query = "UPDATE _members SET 
                    mb_password = '$mb_password' 
                WHERE mb_id = '$mb_id'";
        return $db->execute($query);
    }

    /**
     * 회원 정보 변경
     * @param $data
     */
    public function updateMember($data)
    {
        $db = new db();
        $mb_id = $data['mb_id'];
        $data = array_diff_key($data, array('mb_id' => ''));
        $updateQuery = '';
        for ($i = 0; $i < count($data); $i++) {
            $update = (array_values($data)[$i] != NULL) ?
                "'" . array_values($data)[$i] . "'" : 'NULL ';
            $updateQuery .=
                ($i == 0 || $i == count($data) ? '' : ',') . array_keys($data)[$i] . " = " . $update;
        }
        $query = "UPDATE
                    _members
                SET ";
        $query .= $updateQuery;
        $query .= " WHERE mb_id = '$mb_id'";
        return $db->execute($query);
    }

}