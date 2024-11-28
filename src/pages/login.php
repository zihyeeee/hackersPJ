<?php
use src\services\OnOffService;

$onOffService = new OnOffService();
$onOff = $onOffService->getOnOff();

$img_url= $config['hacademia_cdn_url'];
?>

<script type="text/javascript" src="<?=$config['js_url'];?>/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?=$config['js_url'];?>/hackers2024.js"></script>

<script>
    $(document).ready(function(){
        $(window).scroll(function(){
            let win_top = $(window).scrollTop();
            let main_top = $('.main_top').offset().top-500;

            if(win_top > main_top) {
                $('.info_box').addClass("up");
                $('.info_box ul').addClass("up");
            }
        })
    })

    function lazy_move(obj) {
        var $target = $(obj);

        $('html, body').stop().animate(
            {
                scrollTop: $target.offset().top,
            }, {
                duration: 1000,
                step: function (now, fx) {
                    var newOffset = $target.offset().top;

                    if (fx.end !== newOffset)
                        fx.end = newOffset;
                },
            }
        );
    }
</script>


<style>
    * {margin: 0;padding: 0;box-sizing: border-box;font-family: 'Noto Sans KR', sans-serif;}
    body, input, textarea, select, table, button{font-family: 'Noto Sans KR', sans-serif;}
    body{background:#000101;}
    li{list-style:none;}
    img{border:0;}
    a,
    a:hover,
    a:active,
    a:visited,
    a:link{color:#222; text-decoration:none;}
    .e_wrap {font-size:2.4vw; background:#000101; width: 100%;}
    .e_wrap img {width: 100%;vertical-align: top;}
    .pos_r {position: relative;}
    .ov{overflow: hidden;}
    .flex{display: flex; align-items: center;}
    .t-c{text-align: center;}

    .e_wrap .main_top > img{object-fit: cover; width: 100%; height:100%;}
    .e_wrap .main_top .scroll{position:absolute; top:72%; left:50%; transform:translatex(-50%); width: 60%; margin:5vw auto;}
    .e_wrap .main_top .scroll a{width: 100%; margin:0 auto; display:block; text-decoration:underline; padding-right:2vw; }
    .e_wrap .main_top .scroll a img{width: 100%;}
    .e_wrap .main_top .scroll > img{width: 18%; margin-top:5vw;}

    .e_wrap .user_chk{border-radius:6vw 6vw 0 0;}
    .e_wrap .user_chk .user_box{background:#fff; border-radius:6vw 6vw 0 0; overflow:hidden; padding-bottom:26vw;}
    .e_wrap .user_chk .info_box{background:#fafafa; letter-spacing:-0.1rem; padding:10vw 4vw 24vw; transform:translateY(80vw); margin-bottom:18vw; opacity: 0;}
    .e_wrap .user_chk .info_box.up{transform:translateY(0vw); opacity: 1; transition:all 1s ease-in;}
    .e_wrap .user_chk .info_box h2{font-size:5.4vw;}
    .e_wrap .user_chk .info_box h2:nth-child(1){font-weight:500;}
    .e_wrap .user_chk .info_box ul{color:#444; transform:translateY(20vw); opacity: 0;}
    .e_wrap .user_chk .info_box ul.up{transform:translateY(4vw); opacity: 1; transition:all 1s ease; transition-delay:1s;}
    .e_wrap .user_chk .info_box ul li{position:relative; height:5.4vw; line-height:5.4vw; font-size:3vw; padding-left:2.8vw;}
    .e_wrap .user_chk .info_box ul li:before{content:""; background:#444; width: 0.8vw; height:0.8vw; border-radius:100%; position:absolute; top:2.2vw; left:0;}
    .e_wrap .user_chk .info_box ul li:nth-child(2):before{display:none;}

    .e_wrap .user_chk .phone_num{letter-spacing:-0.08rem; padding:3vw 4vw;}
    .e_wrap .user_chk .phone_num p{font-size:3.6vw; font-weight:700; margin-bottom:2vw;}
    .e_wrap .user_chk .phone_num input[type="text"]{border-width:0 0 2px 0; font-size:5vw; color:#111; padding:1vw 2vw; width: 100%;}
    .e_wrap .user_chk .phone_num .submit_btn{color:#fff; background: linear-gradient(-90deg, #EE7752, #E73C7E, #23A6D5, #23D5AB, #EE7752); background-size: 400% 100%; font-size:4vw;text-transform: uppercase;  animation: Gradient 4s ease infinite; font-weight:700; border:none; border-radius:2vw; height:15vw; line-height:15vw; width: 100%; display: block; margin:4vw auto 0;}
    .e_wrap .user_chk .phone_num .submit_btn div{position: relative; z-index: 5;}
    .e_wrap .user_chk .phone_num .submit_btn::after {content: ''; position: absolute; background-size: inherit; background-image: inherit; animation: inherit; left: 0px; right: 0px; top: 2px; height: 100%; filter: blur(1rem);}

    @keyframes Gradient {
        50% {
            background-position: 140% 50%;
            transform: skew(-2deg);
        }
    }
</style>

<body>
    <div class="e_wrap pos_r">
        <div class="main_top pos_r">
            <img src="<?=$img_url;?>hama.jpg" alt="HAMA">
            <div class="scroll pos_r flex" onclick="lazy_move('.user_chk')">
                <a class="t-c">
                    <img src="<?=$img_url;?>main_sys.png" alt="scroll">
                </a>
                <img src="<?=$img_url;?>scroll.gif" alt="scroll">
            </div>
        </div>
    
        <div class="user_chk">
            <img src="<?=$img_url;?>hama_top.jpg" alt="HAMA">
            <div class="user_box">
                <div class="info_box">
                    <h2>본인인증을 위해</h2>
                    <h2>휴대폰 번호를 입력해 주세요.</h2>
                    <ul>
                        <li>정확하게 입력해야 투표 참여가 가능합니다.</li>
                        <li>(인트라넷 정보 기준)</li>
                        <li>중복투표를 방지하기 위해 휴대폰 번호를 수집합니다.</li>
                        <li>수집된 휴대폰 번호는 송년회 종료 후 파기됩니다.</li>
                    </ul>
                </div>
                <div class="phone_num">
                    <p>휴대폰번호</p>
                    <input type="hidden" name="onoff" value="<?= $onOff ?>">
                    <input type="text" name="user_mobile" maxlength="11" placeholder="01012345678">
                    <button class="submit_btn" onclick="login()">
                        <div>입장하기</div> 
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>