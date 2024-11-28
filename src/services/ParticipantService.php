<?php

namespace src\services;

use src\models\ParticipantModel;

class ParticipantService
{
    private $participantModel;

    public function __construct()
    {
        $this->participantModel = new ParticipantModel();
    }

    // 참가자 등록
    public function addParticipant($params)
    {
        $team_name = $params['team_name'];
        $title = $params['title'];
        $image_url = $params['image_url'];
        $image_org_name = $params['image_org_name'];

        if(!validate_data(['team_name' => $team_name, 'title' => $title, 'image_url' => $image_url, 'image_org_name' => $image_org_name])){
            return ['result' => 'fail', 'message' => '모든 항목을 입력해주세요.'];
        }

        $result = $this->participantModel->insertData([
            'team_name' => $team_name, 
            'title' => $title, 
            'image_url' => $image_url, 
            'image_org_name' => $image_org_name
        ]);

        if($result['success']){
            return ['result' => 'success', 'no' => $result['insertId'], 'message' => '참가자 등록이 완료되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '오류가 발생했습니다.'];
        }
    }

    // 참가자 수정
    public function modifyParticipant($params) {
        $p_id = $params['p_id'];
        $team_name = $params['team_name'];
        $title = $params['title'];
        $image_url = $params['image_url'];
        $image_org_name = $params['image_org_name'];

        if(!validate_data(['p_id' => $p_id])){
            return ['result' => 'fail', 'message' => '잘못된 접근입니다.'];
        }

        if(!validate_data(['team_name' => $team_name, 'title' => $title, 'image_url' => $image_url, 'image_org_name' => $image_org_name])){
            return ['result' => 'fail', 'message' => '모든 항목을 입력해주세요.'];
        }

        $result = $this->participantModel->updateData([
            'p_id' => $p_id,
            'team_name' => $team_name,
            'title' => $title,
            'image_url' => $image_url,
            'image_org_name' => $image_org_name
        ]);

        if($result['success']){
            return ['result' => 'success', 'message' => '참가자 수정이 완료되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '오류가 발생했습니다.'];
        }
    }

    // 참가자 삭제
    public function delParticipant($p_id) {
        if(!validate_data(['p_id' => $p_id])){
            return ['result' => 'fail', 'message' => '잘못된 접근입니다.'];
            exit;
        }

        $result = $this->participantModel->deleteData($p_id);

        if($result['success']){
            return ['result' => 'success', 'message' => '참가자 삭제가 완료되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '오류가 발생했습니다.'];
        }
    }

    // 참가자 조회
    public function getParticipantList() {
        return $this->participantModel->selectParticipantList();
    }
}