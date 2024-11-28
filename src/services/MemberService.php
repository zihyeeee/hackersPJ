<?php

namespace src\services;

use src\models\MemberModel;

class MemberService
{
    private $memberModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
    }

    // 로그인
    public function login($params) {
        $user_mobile = $params['user_mobile'];

        if(!validate_data(['user_mobile' => $user_mobile])){
            return ['result' => 'fail', 'message' => '휴대폰 번호를 입력해주세요.'];
        }

        $result = $this->memberModel->selectMemberByCp($user_mobile);

        if($result){
            $_SESSION['hackers2024_member_id'] = $result['id'];
            $_SESSION['hackers2024_member_cp'] = $result['cp'];
            $_SESSION['hackers2024_member_user_level'] = $result['user_level'];
            return ['result' => 'success', 'member_id' => $result['id'], 'message' => '로그인이 완료되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '일치하는 휴대폰 번호가 없습니다.'];
        }
    }
}