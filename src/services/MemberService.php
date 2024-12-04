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

    // 유저 추가
    public function addUser($params) {
        $user_mobile = $params['user_mobile'];
        $user_level = $params['user_level'];

        if(!validate_data(['user_mobile' => $user_mobile, 'user_level' => $user_level])){
            return ['result' => 'fail', 'message' => '필수 입력 항목을 확인해주세요.'];
        }

        $exists = $this->memberModel->selectMemberByCp($user_mobile);
        if(!empty($exists['id'])){
            return ['result' => 'fail', 'message' => '이미 존재하는 회원입니다.'];
        }

        $result = $this->memberModel->insertData([
            'hid' => '수동', 
            'cp' => $user_mobile, 
            'user_level' => $user_level
        ]);

        if($result['success']){
            return ['result' => 'success', 'message' => '회원 등록이 완료되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '오류가 발생했습니다.'];
        }
    }
}