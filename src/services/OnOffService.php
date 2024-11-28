<?php

namespace src\services;

use src\models\OnOffModel;

class OnOffService
{
    private $onOffModel;

    public function __construct()
    {
        $this->onOffModel = new OnOffModel();
    }

    // 관리자 투표 종료 체크
    public function checkOnOff($onOff) {
        if($onOff == '2' && $_SESSION['hackers2024_member_user_level'] == '1'){
            session_unset();
            session_destroy();
            return ['result' => 'fail', 'message' => '종료된 투표입니다.'];
        } else {
            return ['result' => 'success', 'message' => '투표 진행중입니다.'];
        }
    }

    public function getOnOff()
    {
        $result = $this->onOffModel->getOnOff();
        return $result['onoff'];
    }

    public function updateOnOff($onoff)
    {
        if(!validate_data(['onoff' => $onoff])){
            return ['result' => 'fail', 'message' => '잘못된 접근입니다.'];
        }

        $result = $this->onOffModel->updateOnOff($onoff);

        if($result['success']){
            return ['result' => 'success', 'message' => '온오프 상태가 변경되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '오류가 발생했습니다.'];
        }
    }
}