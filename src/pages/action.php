<?php

use \src\services\S3Service;
use \src\services\OnOffService;
use \src\services\MemberService;
use \src\services\VoteService;
use \src\services\ParticipantService;

if(empty($_POST['action'])){
    echo jsonEncode(['result' => 'fail', 'message' => '잘못된 접근입니다.']);
    exit;
}

$action = $_POST['action'];

switch($action){
    case 'add_participant' :
        $participantService = new ParticipantService();
        if(IS_DEV){
            $s3Service = new S3Service('hackersac-cdn');
        } else {
            $s3Service = new S3Service('adieu2024');
        }

        $team_name = $_POST['team_name'];
        $title = $_POST['title'];
        $image = $_FILES['image'];
        $image_url = '';
        $image_org_name = empty($image['name']) ? '' : $image['name'];

        if(!empty($image)){
            $uploadImage = $s3Service->upload($image);
            $image_url = empty($uploadImage['url']) ? '' : $uploadImage['url'];
        }

        $result = $participantService->addParticipant([
            'team_name' => $team_name, 
            'title' => $title, 
            'image_url' => $image_url, 
            'image_org_name' => $image_org_name
        ]);

        echo jsonEncode($result);
        exit;

    case 'modify_participant' :
        $participantService = new ParticipantService();
        if(IS_DEV){
            $s3Service = new S3Service('hackersac-cdn');
        } else {
            $s3Service = new S3Service('adieu2024');
        }

        $p_id = $_POST['p_id'];
        $team_name = $_POST['team_name'];
        $title = $_POST['title'];
        $image = empty($_FILES['image']) ? '' : $_FILES['image'];
        $original_image = $_POST['original_image'];
        $original_image_name = $_POST['original_image_name'];

        if(empty($original_image) && $image['name']){
            $uploadImage = $s3Service->upload($image);
            $image_url = empty($uploadImage['url']) ? '' : $uploadImage['url'];
            $image_org_name = empty($image['name']) ? '' : $image['name'];
        }else{
            $image_url = $original_image;
            $image_org_name = $original_image_name;
        }

        $result = $participantService->modifyParticipant([
            'p_id' => $p_id,
            'team_name' => $team_name,
            'title' => $title,
            'image_url' => $image_url,
            'image_org_name' => $image_org_name
        ]);

        echo jsonEncode($result);
        exit;

    case 'del_participant' :
        $participantService = new ParticipantService();

        $p_id = $_POST['p_id'];
        $result = $participantService->delParticipant($p_id);
        echo jsonEncode($result);
        exit;

    case 'onoff' :
        $onoff = $_POST['onoff'];
        $onOffService = new OnOffService();
        $result = $onOffService->updateOnOff($onoff);
        echo jsonEncode($result);
        exit;

    case 'login' :
        $user_mobile = $_POST['user_mobile'];

        $memberService = new MemberService();
        $onOffService = new OnOffService();

        // 로그인
        $loginResult = $memberService->login(['user_mobile' => $user_mobile]);
        $onOff = $onOffService->getOnOff();

        // 로그인 성공 시 투표 종료 체크
        if($loginResult['result'] == 'success'){
            // 투표 종료 체크
            $onOffResult = $onOffService->checkOnOff($onOff);
            // 투표 종료 시 종료 메시지 반환
            if($onOffResult['result'] == 'fail'){
                echo jsonEncode($onOffResult);
                exit;
            }
        }

        // 로그인 결과 반환
        echo jsonEncode($loginResult);
        exit;

    case 'vote':
        $member_id = $_SESSION['hackers2024_member_id'];
        $p_id = $_POST['p_id'];
        $vote_type = $_POST['vote_type'];

        // 투표 종료 체크
        $onOffService = new OnOffService();
        $onOffResult = $onOffService->checkOnOff($onOffService->getOnOff());
        if($onOffResult['result'] == 'fail') {
            echo jsonEncode($onOffResult);
            exit;
        }

        $voteService = new VoteService();
        $voteResult = $voteService->vote([
            'member_id' => $member_id,
            'p_id' => $p_id,
            'vote_type' => $vote_type
        ]);

        if($voteResult['result'] == 'success'){
            echo jsonEncode(['result' => 'success', 'p_id' => $p_id, 'vote_type' => $vote_type]);
        }else{
            echo jsonEncode($voteResult);
        }
        exit;

    case 'add_user' :
        $memberService = new MemberService();
        $user_mobile = $_POST['user_mobile'];
        $user_level = $_POST['user_level'];
        $result = $memberService->addUser([
            'user_mobile' => $user_mobile, 
            'user_level' => $user_level
        ]);
        echo jsonEncode($result);
        exit;

    case 'vote_reset' :
        $voteService = new VoteService();
        $result = $voteService->resetVote();
        if($result['success']){
            echo jsonEncode(['result' => 'success', 'message' => '투표 초기화가 완료되었습니다.']);
        }else{
            echo jsonEncode(['result' => 'fail', 'message' => '오류가 발생했습니다.']);
        }
        exit;
}
?>
