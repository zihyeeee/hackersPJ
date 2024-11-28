<?php

namespace src\models;

use src\models\BaseModel;

class VoteModel extends BaseModel
{
    // 전체 참가자별 투표 수
    public function countGroupByParticipant() {
        $qry =  "SELECT p_id, vote_type, COUNT(*) AS vote_count FROM event_hackers_vote GROUP BY p_id, vote_type";
        $this->db_slave->prepare($qry);
        $votes = $this->db_slave->stmt_execute('all');
        return $votes;
    }

    // 회원별 투표 조회
    public function selectListByMemberId($member_id) {
        $qry = "SELECT p_id, vote_type FROM event_hackers_vote WHERE member_id = ?";
        $this->db_slave->prepare($qry);
        $this->db_slave->stmt_bind_param("i", $member_id);
        $votes = $this->db_slave->stmt_execute('all');
        return $votes;
    }

    public function insertData($data) {
        $qry = "INSERT INTO event_hackers_vote (member_id, p_id, vote_type, reg_date) VALUES (?, ?, ?, NOW())";
        $this->db->prepare($qry);
        $this->db->stmt_bind_param("iii", [$data['member_id'], $data['p_id'], $data['vote_type']]);
        return $this->db->stmt_execute('insert');
    }
}