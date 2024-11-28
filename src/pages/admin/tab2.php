<?php

use src\services\ParticipantService;
use src\services\VoteService;

$participantService = new ParticipantService();
$voteService = new VoteService();

// 참가자 조회
$participants = $participantService->getParticipantList();

// 투표 통계
$voteStatistics = $voteService->getVoteStatistics();
?>

<div class="vote_wrap">
    <table class="vote_list">
        <tr class="vote_list_header">
            <td>번호</td>
            <td>이름</td>
            <td>투표(x3)</td>
            <td>투표(x2)</td>
            <td>투표(x1)</td>
            <td>총 투표수</td>
            <td>순위</td>
        </tr>

        <?php if(!empty($participants)) { 
            foreach($participants as $participant){ 
                $participantId = $participant['p_id'];
                if(!empty($voteStatistics[$participantId])) {
                    $vote_type_1 = $voteStatistics[$participantId]['vote_type_1'];
                    $vote_type_2 = $voteStatistics[$participantId]['vote_type_2'];
                    $vote_type_3 = $voteStatistics[$participantId]['vote_type_3'];
                    $total = $voteStatistics[$participantId]['total'];
                    $rank = $voteStatistics[$participantId]['rank'] + 1;
                } ?>
                <tr>
                    <td><?=$participantId?></td>
                    <td><?=$participant['team_name']?></td>
                    <td><?=$vote_type_1 ?? 0?></td>
                    <td><?=$vote_type_2 ?? 0?></td>
                    <td><?=$vote_type_3 ?? 0?></td>
                    <td><?=$total ?? 0?></td>
                    <td><?=$rank ?? 0?></td>
                </tr>
            <?php } 
        } ?>
    </table>
</div>