<?php

namespace src\services;

use src\models\VoteModel;

class VoteService
{
    private $voteModel;

    public function __construct()
    {
        $this->voteModel = new VoteModel();
    }

    // 투표 초기화
    public function resetVote() {
        return $this->voteModel->deleteAll();
    }

    // 투표 통계
    public function getVoteStatistics() {
        $votes = [];
        $votesCount = $this->voteModel->countGroupByParticipant();
        $voteTypePoints = [
            1 => 3,
            2 => 2,
            3 => 1
        ];
        
        // 가중치 계산
        foreach ($votesCount as $vote) {
            $participantId = $vote['p_id'];
            $voteCount = $vote['vote_count'] * $voteTypePoints[$vote['vote_type']];
            $votes[$participantId][$vote['vote_type']] = $voteCount;
        }
        
        // 총점 계산
        $totalVotes = [];
        foreach ($votes as $participantId => $vote) {
            $totalVotes[$participantId] = array_sum($vote);
        }
        
        // 순위 계산
        $sortedVotes = $totalVotes;
        arsort($sortedVotes);
        $ranks = [];
        $currentRank = 0;
        $previousScore = null;
        
        foreach ($sortedVotes as $participantId => $score) {
            if ($previousScore === null || $score < $previousScore) {
                $currentRank++;
            }
            $ranks[$participantId] = $currentRank;
            $previousScore = $score;
        }

        // 최종 결과 조합
        $result = [];
        foreach ($votes as $participantId => $voteData) {
            $result[$participantId] = [
                'vote_type_1' => $voteData['1'] ?? 0,
                'vote_type_2' => $voteData['2'] ?? 0,
                'vote_type_3' => $voteData['3'] ?? 0,
                'total' => $totalVotes[$participantId],
                'rank' => $ranks[$participantId]
            ];
        }
        
        return $result;
    }

    // 참가자 조회
    public function getVoteListByMemberId($member_id) {
        if(!$member_id){
            return [];
        }
        return $this->voteModel->selectListByMemberId($member_id);
    }

    // 투표
    public function vote($data) {
        if(!validate_data(['member_id' => $data['member_id'], 'p_id' => $data['p_id'], 'vote_type' => $data['vote_type']])){
            return ['result' => 'fail', 'message' => '데이터가 올바르지 않습니다.'];
        }

        $insertResult = $this->voteModel->insertData([
            'member_id' => $data['member_id'],
            'p_id' => $data['p_id'],
            'vote_type' => $data['vote_type']
        ]);

        if($insertResult['insertId']){
            return ['result' => 'success', 'message' => '투표가 완료되었습니다.'];
        }else{
            return ['result' => 'fail', 'message' => '투표에 실패하였습니다.'];
        }
    }
}