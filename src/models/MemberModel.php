<?php

namespace src\models;

use src\models\BaseModel;

class MemberModel extends BaseModel
{
    public function selectMemberByCp($cp) {
        $qry = "SELECT id, cp, user_level FROM event_hackers_member WHERE cp = ?";
        $this->db_slave->prepare($qry);
        $this->db_slave->stmt_bind_param("s", $cp);
        $result = $this->db_slave->stmt_execute('row');

        return $result;
    }

    public function insertData($params) {
        $qry = "INSERT INTO event_hackers_member (hid, cp, user_level) VALUES (?, ?, ?)";
        $this->db->prepare($qry);
        $this->db->stmt_bind_param("ssi", [$params['hid'], $params['cp'], $params['user_level']]);
        $result = $this->db->stmt_execute('insert');

        return $result;
    }
}