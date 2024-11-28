<?php

namespace src\models;

use src\models\BaseModel;

class ParticipantModel extends BaseModel
{
    public function selectParticipantList() {
        $qry = "SELECT p_id, team_name, title, image_url, image_org_name FROM event_hackers_participant";
        $this->db_slave->prepare($qry);
        $result = $this->db_slave->stmt_execute('all');
        return $result;
    }

    public function insertData($params) {
        $qry = "INSERT INTO event_hackers_participant (team_name, title, image_url, image_org_name, reg_date) VALUES (?, ?, ?, ?, NOW())";
        $this->db->prepare($qry);
        $this->db->stmt_bind_param("ssss", [$params['team_name'], $params['title'], $params['image_url'], $params['image_org_name']]);
        $result = $this->db->stmt_execute('insert');   
        return $result;
    }

    public function updateData($params) {
        $qry = "UPDATE event_hackers_participant SET team_name = ?, title = ?, image_url = ?, image_org_name = ? WHERE p_id = ?";
        $this->db->prepare($qry);
        $this->db->stmt_bind_param("ssssi", [$params['team_name'], $params['title'], $params['image_url'], $params['image_org_name'], $params['p_id']]);
        $result = $this->db->stmt_execute('update');
        return $result;
    }

    public function deleteData($p_id) {
        $qry = "DELETE FROM event_hackers_participant WHERE p_id = ?";
        $this->db->prepare($qry);
        $this->db->stmt_bind_param("i", [$p_id]);
        $result = $this->db->stmt_execute('delete');
        return $result;
    }
}