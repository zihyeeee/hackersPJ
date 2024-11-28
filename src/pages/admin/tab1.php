<?php

use src\services\ParticipantService;
use src\services\OnOffService;

$participantService = new ParticipantService();
$onOffService = new OnOffService();

// 참가자 조회
$participants = $participantService->getParticipantList();

// 투표창 설정 조회
$onoff = $onOffService->getOnOff();
?>

<div class="onoff_wrap">
    <div>투표창 설정</div>
    <div class="onoff_wrap_inner">
        <label><input type="radio" name="onoff" value="1" <?=$onoff == 1 ? 'checked' : ''?> onclick="onoff('1')">시작</label>
        <label><input type="radio" name="onoff" value="2" <?=$onoff == 2 ? 'checked' : ''?> onclick="onoff('2')">종료</label>
    </div>
</div>

<div class="participant_wrap">
    <div>참가자 설정</div>
    <table class="participant_list">   
        <tr class="participant_list_header">
            <td>번호</td>
            <td>이름</td>
            <td>참가곡명</td>
            <td colspan="2">이미지</td>
            <td>관리</td>
        </tr>
        <?php if(!empty($participants)) { 
            // 데이터 보안
            $participants = escapeHtmlData($participants);

            // 데이터 출력
            foreach($participants as $participant) { ?>
                <tr id="modify_<?=$participant['p_id']?>">
                    <td><?=$participant['p_id']?></td>
                    <td><input type="text" name="team_name" value="<?=$participant['team_name']?>"></td>
                    <td><input type="text" name="title" value="<?=$participant['title']?>"></td>
                    <td>
                        <input type="file" name="image" value="<?=$participant['image_url']?>">
                        <input type="hidden" name="original_image" value="<?=$participant['image_url']?>">
                        <input type="hidden" name="original_image_name" value="<?=$participant['image_org_name']?>">
                    </td>
                    <td>
                        <?php if(!empty($participant['image_url'])){ ?>
                            <img src="<?=$participant['image_url']?>" width="100" height="100" alt="<?=$participant['image_org_name']?>">
                            <div><?=$participant['image_org_name']?></div>
                        <?php } ?>
                    </td>
                    <td>
                        <button onclick="modify_participant(<?=$participant['p_id']?>)">수정</button>
                        <button onclick="del_participant(<?=$participant['p_id']?>)">삭제</button>
                    </td>
                </tr>
            <?php } 
        } ?>
    </table>

    <div class="add_participant_title">참가자 추가</div>
    <table class="participant_list">   
        <tr class="participant_list_header">
            <td>이름</td>
            <td>참가곡명</td>
            <td>이미지</td>
            <td>관리</td>
        </tr>
        <tr id="new">
            <td><input type="text" name="team_name" value=""></td>
            <td><input type="text" name="title" value=""></td>
            <td><input type="file" name="image" value=""></td>
            <td><button onclick="add_participant()">추가</button></td>
        </tr>
    </table>
</div>
