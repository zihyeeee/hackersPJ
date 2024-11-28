<?php

namespace src\models;

use src\models\BaseModel;

class OnOffModel extends BaseModel
{
    public function getOnOff()
    {
        $qry = "SELECT onoff FROM event_hackers_onoff";
        $this->db_slave->prepare($qry);
        $result = $this->db_slave->stmt_execute('row');

        return $result;
    }

    public function updateOnOff($onoff)
    {
        $qry = "UPDATE event_hackers_onoff SET onoff = ?";
        $this->db->prepare($qry);
        $this->db->stmt_bind_param("i", [$onoff]);
        $result = $this->db->stmt_execute('update');

        return $result;
    }
}