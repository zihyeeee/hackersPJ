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
}