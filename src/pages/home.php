<?php
use src\services\ParticipantService;
use src\services\VoteService;
use src\services\OnOffService;

if(!empty($_GET['test']) && $_GET['test'] == '1') {
    $_SESSION['hackers2024_member_id'] = '999';
    $_SESSION['hackers2024_member_cp'] = '01011111111';
    $_SESSION['hackers2024_member_user_level'] = '1';
}

checkLogin();

$participantService = new ParticipantService();
$voteService = new VoteService();
$onOffService = new OnOffService();

// 투표 종료 체크
$onOff = $onOffService->getOnOff();
$onOffResult = $onOffService->checkOnOff($onOff);
if($onOffResult['result'] == 'fail') {
    echo '<script>alert("'.$onOffResult['message'].'");</script>';
    exit;
}

// 참가자
$participants = $participantService->getParticipantList();

// 투표
$votes = $voteService->getVoteListByMemberId($_SESSION['hackers2024_member_id']);

// 투표 내역
$voted = [
    '1' => null, // gold
    '2' => null, // silver
    '3' => null, // bronze
];
if(!empty($votes)) {
    foreach($votes as $vote) {
        $voted[$vote['vote_type']] = $vote['p_id'];
    }
}

$img_url = $config['hacademia_cdn_url'];
?>

<script type="text/javascript" src="<?=$config['js_url']?>/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?=$config['js_url']?>/hackers2024.js"></script>

<script>
    // 투표 타입
    const voteType = {'1': 'gold', '2': 'silver', '3': 'bronze'};

    const myVote = {
        // 투표한 참가자 아이디
        'getPId': () => {
            let p_id_arr = [];
            $('#my_vote input').each(function(){
                if($(this).val() != '') {
                    p_id_arr.push($(this).val());
                }
            });

            return p_id_arr;
        },

        // 투표한 타입
        'getVotedType': () => {
            let vote_type_arr = [];
            $('#my_vote input').each(function(){
                if($(this).val() != '') {
                    vote_type_arr.push($(this).data('vote_type'));
                }
            });

            return vote_type_arr;
        },

        // 투표 저장
        'setVote': (p_id, vote_type) => {
            $('#my_vote input[name="vote_type_'+vote_type+'"]').val(p_id);
        },

        // 목록에 투표한 트로피 배지 추가
        'trophyBadge': () => {
            $('#my_vote input').each(function(){
                let html = '<img src="<?=$img_url?>trophy'+$(this).data('vote_type')+'.png" alt="">';

                // 트로피 배지 추가
                $('li[data-p_id="'+$(this).val()+'"] .vote_trophy').html('');
                $('li[data-p_id="'+$(this).val()+'"] .vote_trophy').append(html);
                $('li[data-p_id="'+$(this).val()+'"] .sum_wrap').addClass(voteType[$(this).data('vote_type')]);
                $('li[data-p_id="'+$(this).val()+'"] .sum_wrap span:first-child').addClass(voteType[$(this).data('vote_type')]);
            });
        },

        // 상단 트로피 배지 표시
        'trophyBadgeList': () => {
            $('.trophy img').removeClass('trophy_off');

            // 투표한 트로피는 숨김
            $('#my_vote input').each(function(){
                if($(this).val() != '') {
                    $('#trophy_'+voteType[$(this).data('vote_type')]).addClass('trophy_off');
                }
            });
        },

        // 투표 팝업 트로피 배지 표시
        'trophyBadgePop': () => {
            uncheckVote();
            $('.vote_pop .vote_bott label').css('display', 'block');

            // 투표한 트로피는 숨김
            $('#my_vote input').each(function(){
                if($(this).val() != '') {
                    $('.vote_pop .vote_bott label[for="'+voteType[$(this).data('vote_type')]+'"]').css('display', 'none');
                }
            });
        },

        // 전체 투표 여부
        'isAllVote': () => {
            let isAllVote = true;
            $('#my_vote input').each(function(){
                if($(this).val() == '') {
                    isAllVote = false;
                }
            });
            return isAllVote;
        }
    }

    // 투표 체크 해제
    const uncheckVote = () => {
        $('.vote_bott input[name="trophy"]').prop('checked', false);
    }

    // 투표 팝업 닫기
    const closePop = () => {
        $('.vote_pop').fadeOut();
        uncheckVote();
        $('#selected_participant input[name="selected_p_id"]').val('');
        $('.vote_wrap').css('display', 'block');
    }

    // 투표 완료 팝업 열기
    const openPopConfirm = (p_id, vote_type) => {
        const number = $('li[data-p_id="'+p_id+'"] .number').text();
        const img_url = $('li[data-p_id="'+p_id+'"] img').attr('src');
        const team_name = $('li[data-p_id="'+p_id+'"] a.name_wrap p:first-child').text();
        const trophy_img = '<?=$img_url?>l_trophy'+vote_type+'.png';
        
        let trophy_name = '';
        switch(vote_type) {
            case '1':
                trophy_name = '골드 트로피';
                break;

            case '2':
                trophy_name = '실버 트로피';
                break;

            case '3':
                trophy_name = '브론즈 트로피';
                break;  
        }

        Object.entries(voteType).forEach(function([key, value]){
            $('.vote_confirm .vote_middle .team_wrap .sum_wrap').removeClass(value);
            $('.vote_confirm .vote_middle .team_wrap .sum_wrap .number').removeClass(value);
        });

        $('.vote_confirm .vote_middle .team_wrap p').eq(0).text(trophy_name);
        $('.vote_confirm .vote_middle .team_wrap p').eq(1).text(team_name);
        $('.vote_confirm .vote_middle .team_wrap .sum_wrap .number').text(number);
        $('.vote_confirm .vote_middle .team_wrap .sum_wrap img').attr('src', img_url);
        $('.vote_confirm .vote_middle .team_wrap .sum_wrap').addClass(voteType[vote_type]);
        $('.vote_confirm .vote_middle .team_wrap .sum_wrap .number').addClass(voteType[vote_type]);
        $('.vote_confirm .vote_middle .team_trohpy img').attr('src', trophy_img);
        $('.vote_confirm').fadeIn();
    }

    // 투표 완료 팝업 닫기
    const closePopConfirm = () => {
        $('.vote_confirm').fadeOut();
        uncheckVote();
        $('#selected_participant input[name="selected_p_id"]').val('');
        $('.vote_wrap').css('display', 'block');
    }

    $(document).ready(function(){
        //팀이름 글자 크기 조절
        $('.name_wrap p:first-child').each(function(){
            const teamName = $(this).text(); 
            const teamNameLength = teamName.length; 

            console.log(teamName);
            console.log(teamNameLength);

            if (teamNameLength > 10) {
                $(this).css('font-size', '3.2vw'); 
                $(this).css('height', '9vw');
            } else {
                $(this).css('font-size', '3.6vw');
                $(this).css('height', '4vw');
            }
        });

        // 투표한 트로피 배지 숨김
        myVote.trophyBadgeList();
        myVote.trophyBadge();

        // 마크 클릭
        $('.mark').click(function(){
            $(this).toggleClass('off')

            if($(this).hasClass('off') === true) {
                $('.mark_bg_wrap').fadeOut(); $('.vote_wrap').css('display','block');
            } else {
                $('.mark_bg_wrap').fadeIn(); $('.vote_wrap').css('display','none');
            }
        })

        // 참가자 선택
        $('.cont_wrap li').click(function(){
            const p_id = $(this).data('p_id');
            const team_name = $(this).find('a.name_wrap p:first-child').text();
            const image_url = $(this).find('#img_'+p_id).attr('src');

            $('.vote_wrap').css('display', 'none');
            
            // 투표 기회 체크
            if(myVote.isAllVote()) {
                alert('투표 기회가 모두 소진되었습니다.');
                $('.vote_wrap').css('display', 'block');
                return;
            }

            // 이미 투표한 참가자 체크
            const votedPIdArr = myVote.getPId();
            if(votedPIdArr.includes(p_id.toString())) {
                alert('이미 투표가 완료된 참가자입니다.');
                $('.vote_wrap').css('display', 'block');
                return;
            }
            
            // 선택한 참가자 정보 설정
            $('#selected_participant input[name="selected_p_id"]').val(p_id);

            // 투표 팝업 트로피 배지 표시
            myVote.trophyBadgePop();

            // 투표 팝업 표시
            $('.vote_pop .vote_middle img').attr('src', image_url);
            $('.vote_pop .vote_middle p').text(team_name);
            $('.vote_pop').fadeIn();
        })
    })
</script>

<style>
    * {margin: 0;padding: 0;box-sizing: border-box;font-family: 'Noto Sans KR', sans-serif;}
    body, input, textarea, select, table, button{font-family: 'Noto Sans KR', sans-serif;}
    body{background:url("<?=$img_url?>bg.png"); background-size:cover; width: 100%; height:100%; object-fit: cover;}
    img{max-width:100%; height:auto; margin:0; padding:0; border:none; vertical-align:middle;}
    a,
    a:hover,
    a:active,
    a:visited,
    a:link{color:#222; text-decoration:none;}
    li{list-style:none;}
    img{border:0;}
    .e_wrap {width: 100%; font-size:2.4vw;}
    .e_wrap img {width: 100%; vertical-align: top;}
    .pos_r {position: relative;}
    .ov{overflow: hidden;}
    .flex{display: flex; align-items: center;}
    .t-c{text-align: center;}

    /* 신청팀 썸네일 css */
    .sum_wrap{position:relative; display:block; border-radius:3vw; background:#efefef; width: 100%; height:30vw; overflow:hidden; border:1px solid #efefef;}
    .number{position:absolute; top:0; left:0; color:#fff; border-radius:0 0 2.4vw 0; width: 6vw; height:6vw; line-height:6vw; text-align:center; font-size:3vw; font-weight:700; background:#111;}

    .sum_wrap.gold{border:6px solid #d7ae57;}
    .sum_wrap.silver{border:6px solid #aaaaaa;}
    .sum_wrap.bronze{border:6px solid #c9966d;}

    .number.gold{background:#d7ae57;}
    .number.silver{background:#aaaaaa;}
    .number.bronze{background:#c9966d;}


    .mark_bg_wrap{position:absolute; top:0; left:0; width: 100%; height:100%; z-index: 9;}
    .mark_bg_wrap .mark_bg{background:rgba(0,0,0,0.6); width: 100%; height: 100%; position:fixed;}
    .mark_bg_wrap img{position:absolute; top:0; left:50%; transform:translatex(-50%); width: 90%; margin:0 auto;}
    .mark{display:block; width:9.9vw; height:11vw; position:fixed; bottom:3%; right:6%; z-index: 10; background:url("<?=$img_url?>mark_btn.png") no-repeat 100% 0;}
    .mark.off{background:url("<?=$img_url?>mark_btn.png") no-repeat 0; width:9.9vw; height:11vw; z-index: 5;}
    .vote_wrap{display:none;}
    .vote_wrap .top_wrap{width: 90%; margin:0 auto;}
    .vote_wrap .trophy{width: 32%; position:absolute; top:-0.4vw; left:57.8%;}
    .vote_wrap .trophy img{width: 23.2%; background:#f6f8fa; margin-right:2.8vw;}
    .vote_wrap .trophy img.trophy_off{display:none;}
    .vote_wrap .trophy img:last-child{margin-right:0;}

    .cont_wrap{background:#fff; width: 90%; height:auto; margin:-0.5vw auto 0; padding: 4vw 0 9vw;}
    .cont_wrap ul{width: 90%; margin:0 auto; text-align:center;}
    .cont_wrap ul li{width: 48%; display:inline-block; vertical-align:top;}
    .cont_wrap ul li:nth-child(odd){margin-right: 2vw;}
    .cont_wrap ul li a{display:block;}

    .cont_wrap ul li a.sum_wrap img{height:100%;}
    .cont_wrap ul li a.sum_wrap .vote_trophy{position:absolute; bottom:10%; right:10%; width: 6vw; height:11.6vw;}
    .cont_wrap ul li a.name_wrap{padding:2vw 0 6vw;}
    .cont_wrap ul li a img{}
    .cont_wrap ul li a p{font-size:3vw; color:#7b818f;}
    .cont_wrap ul li a p:first-child{font-size: 3.6vw; font-weight:900; color:#222; height:5vw;}

    /* vote_pop */
    .vote_pop{display:none; position:absolute; top:0; left:0; width: 100%; height:100%; z-index: 7;}
    .pop_bg{position:fixed; top:0; left:0; width: 100%; height:100%;background:rgba(0,0,0,0.6);}
    .vote_con{width: 90%; height:164vw; background:#fff; position:absolute; top:4vw; left:50%; transform:translatex(-50%);}

    .vote_con .vote_top{border-bottom:0.4vw solid #111; font-size:4vw; letter-spacing: -0.08em; display: flex; justify-content: space-between;     margin: 2vw 5vw 8vw; padding: 3vw 0; font-weight:700;}
    .vote_con .vote_top span{}
    .vote_con .vote_top a{position:relative; text-indent:-999rem; font-size:0; line-height:0; display:block; width: 6vw; height:6vw;}
    .vote_con .vote_top a:after{content:''; position:absolute; top:0; left:3vw; width: 0.3vw; height:5vw; transform:rotate(-45deg); background:#111;}
    .vote_con .vote_top a:before{content:''; position:absolute; top:0; left:3vw; width: 0.3vw; height:5vw; transform:rotate(45deg); background:#111;}

    .vote_con .vote_middle{width: 66%; margin: 0 auto;}
    .vote_con .vote_middle > div{background:#efefef; border-radius:3vw; overflow:hidden;}
    .vote_con .vote_middle p{font-size:3.4vw; font-weight:700; text-align:center; padding: 2.6vw 0 4vw;}

    .vote_con .vote_bott{margin-top:2vw;}
    .vote_con .vote_bott input{display: none;}
    .vote_con .vote_bott label{display:block; width: 90%; margin:0 auto 2vw; background:url('<?=$img_url?>selc_box.png') no-repeat 0% 0% / 200%; height:18vw; text-indent:-999rem; font-size:0;}
    .vote_con .vote_bott label:nth-child(2){background-position:0 0%;}
    .vote_con .vote_bott label:nth-child(4){background-position:0 50%;}
    .vote_con .vote_bott label:nth-child(6){background-position:0 100%;}

    .vote_con .vote_bott input[type="radio"]{border:5px solid red;}
    .vote_con .vote_bott input[type="radio"]:checked+label{background-position-x:100%;}

    .vote_btn{color:#fff; background: linear-gradient(-90deg, #EE7752, #E73C7E, #23A6D5, #23D5AB, #EE7752); background-size: 400% 100%; font-size:4vw;text-transform: uppercase;  animation: Gradient 4s ease infinite; font-weight:700; border:none; height:15vw; line-height:15vw; width: 100%; display: block; position:absolute; bottom:0; left:0;}
    .vote_con .vote_bott .vote_btn div{position: relative; z-index: 5;}
    .vote_con .vote_bott button:after{content: ''; position: absolute; background-size: inherit; background-image: inherit; animation: inherit; left: 0px; right: 0px; top: 2px; height: 100%; filter: blur(1rem);}

    @keyframes Gradient {
        50% {
            background-position: 140% 50%;
            transform: skew(-2deg);
        }
    }

    /* alert_wrap */
    .alert_wrap{z-index: 8;}
    .alert_wrap,.alert_wrap2{display:none; position:fixed; top:0; left:0; width: 100%; height:100%;}
    .alert_info{width: 90%; background:#fff; position:absolute; top:35%; left:50%; transform:translatex(-50%); text-align: center; padding:8vw 0 0; font-weight:700; letter-spacing: -0.08rem;}
    .alert_wrap .alert_info p.alert_tit{font-size:4vw; margin-bottom: 5vw;}
    .alert_wrap .alert_info p{font-size:3.2vw; color:#333333; margin-bottom: 12vw;}
    .alert_wrap .alert_info ul{width: 100%; display:flex; justify-content: space-between; height:12vw; line-height:12vw;}
    .alert_wrap .alert_info ul li{width: 50%; font-size:4vw; color:#fff;}
    .alert_wrap .alert_info ul li:first-child{background:#444444;}
    .alert_wrap .alert_info ul li .vote_btn{height:12vw; line-height:12vw; margin:0; position: relative;}

    /* alert_wrap2 */
    .alert_wrap2 .alert_info .alert_tit{font-size:3.6vw; font-weight:500; padding:0 2vw 4vw;}

    /* vote_confirm */
    .vote_confirm{display:none; position:absolute; top:0; left:0; width: 100%; height:100%; z-index: 7;}
    .vote_confirm .vote_con .vote_top{margin: 2vw 5vw 0;}
    .vote_confirm .vote_con .vote_middle{width: 100%;}
    .vote_confirm .vote_con .vote_middle .team_wrap{position:absolute; top:14.4%; left:50%; transform:translatex(-50%); width: 80%; background:transparent; }
    .vote_confirm .vote_con .vote_middle .team_wrap p{margin-bottom:5.8vw; color:#444; letter-spacing: -0.08rem;}
    .vote_confirm .vote_con .vote_middle .team_wrap .sum_wrap{width: 57%; margin:6vw auto 0;}
    .vote_confirm .vote_middle .team_trohpy{position:absolute; top:51.4%; left:65.6%; display:block; width: 15.6%;}
    .vote_confirm .vote_con .vote_btn{margin:10.4vw 0 0;}

    /* 관리자 버튼 */
    .admin_btn_wrap button{width: 12vw; height:4vw; margin-bottom:2vw;}
</style>

<body>
    <div id="my_vote">
        <input type="hidden" name="vote_type_1" data-vote_type="1" value="<?=$voted['1']?>">
        <input type="hidden" name="vote_type_2" data-vote_type="2" value="<?=$voted['2']?>">
        <input type="hidden" name="vote_type_3" data-vote_type="3" value="<?=$voted['3']?>">
    </div>

    <div id="selected_participant">
        <input type="hidden" name="selected_p_id" value="">
    </div>

    <div class="e_wrap pos_r">
        <div class="mark_bg_wrap">
            <div class="mark_bg"></div>
            <img src="<?=$img_url?>mark.jpg" alt="">
        </div>
        <a class="mark" href="javascript:" alt="닫기"></a>
    
        <div class="vote_wrap pos_r">
            <img src="<?=$img_url?>hama_top2.jpg" alt="">
            <div class="top_wrap pos_r">
                <img src="<?=$img_url?>my_trophy.png" alt="">
                <div class="trophy">
                    <img id="trophy_gold" src="<?=$img_url?>trophy1.png" alt="">
                    <img id="trophy_silver" src="<?=$img_url?>trophy2.png" alt="">
                    <img id="trophy_bronze" src="<?=$img_url?>trophy3.png" alt="">
                </div>
            </div>
    
            <div class="cont_wrap">
                <?php if($_SESSION['hackers2024_member_user_level'] == '2') { ?>
                    <div class="admin_btn_wrap">
                        <button class="admin_btn" onclick="location.href='/admin';">관리자</button>
                    </div>
                <?php } ?>

                <ul>
                    <?php if(!empty($participants)) { 
                        $cnt = 0;
                        foreach($participants as $participant) { 
                            $cnt ++; ?>
                            <li data-p_id="<?=$participant['p_id']?>">
                                <a class="sum_wrap" href="javascript:">
                                    <span class="number"><?=$cnt?></span>
                                    <img id="img_<?=$participant['p_id']?>" src="<?=$participant['image_url']?>" alt="">
                                    <span class="vote_trophy"></span>
                                </a>
                                <a class="name_wrap" href="javascript:">
                                    <p><?=$participant['team_name']?></p>
                                    <p><?=$participant['title']?></p>
                                </a>
                            </li>
                        <?php } 
                    } ?>
                </ul>
            </div>
        </div>

        <div class="vote_pop pos_r">
            <div class="pop_bg" onclick="closePop();"></div>
            <div class="vote_con">
                <div class="vote_top pos_r">
                    <span>투표참여</span>
                    <a href="javascript:" onclick="closePop();">닫기</a>
                </div>

                <div class="vote_middle">
                    <div>
                        <img src="<?=$img_url?>test_img.jpg" alt="">
                    </div>
                    <p>참가팀이름</p>
                </div>

                <div class="vote_bott">
                    <input type="radio" id="gold" name="trophy" value="1"><label for="gold">골드 트로피</label>
                    <input type="radio" id="silver" name="trophy" value="2"><label for="silver">실버 트로피</label>
                    <input type="radio" id="bronze" name="trophy" value="3"><label for="bronze">브론즈 트로피</label>

                    <button class="vote_btn" onclick="$('.alert_wrap').fadeIn();">
                        <div>수여하기</div> 
                    </button>
                </div>
            </div>
        </div>

        <div class="alert_wrap">
            <div class="pop_bg" onclick="closePop();"></div>
            <div class="alert_info">
                <p class="alert_tit">알림</p>
                <p>한번 진행한 투표는 취소 또는 변경할 수 없습니다. </br>투표를 진행하시겠습니까?</p>
                <ul>
                    <li onclick="$(this).parents('.alert_wrap').fadeOut();">취소</li>
                    <li>
                        <button class="vote_btn" onclick="vote();">
                            <div>확인</div> 
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="vote_confirm">
            <div class="pop_bg" onclick="closePopConfirm();"></div>
            <div class="vote_con">
                <div class="vote_top pos_r">
                    <span>투표완료</span>
                    <a href="javascript:" onclick="closePopConfirm();">닫기</a>
                </div>

                <div class="vote_middle pos_r">
                    <img src="<?=$img_url?>vote_confirm.jpg" alt="">

                    <div class="team_wrap">
                        <p>골드 트로피</p>
                        <p>참가팀이름</p>
                        <div class="sum_wrap gold">
                            <img src="<?=$img_url?>test_img.jpg" alt="">
                            <span class="number gold">1</span>
                        </div>
                    </div>
                    <span class="team_trohpy">
                        <img src="<?=$img_url?>l_trophy1.png" alt="">
                    </span>
                </div>

                <div class="vote_bott">
                    <button class="vote_btn" onclick="closePopConfirm();">
                        <div>확인</div> 
                    </button>
                </div>
            </div>            
        </div>

        <div class="alert_wrap2">
            <div class="pop_bg" onclick="$(this).parent().fadeOut();"></div>
            <div class="alert_info">
                <p class="alert_tit">모바일웹 프론트모바일웹 프론트모바일웹 프론트</br>모바일웹 프론트모바일웹 프론트</p>
                <button class="vote_btn" onclick="">
                    <div onclick="$('.alert_wrap2').fadeOut();">확인</div> 
                </button>
            </div>
        </div>
    </div>
</body>