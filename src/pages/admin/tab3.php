<div class="user_add_wrap">
    <div>유저 추가</div>
    <div>
        <span>휴대폰 번호</span> <input type="text" name="user_mobile" value="" placeholder="01012345678">
        <label><input type="radio" name="user_level" value="1" checked> 일반</label>
        <label><input type="radio" name="user_level" value="2"> 관리자</label>
        <button onclick="add_user()">추가</button>
    </div>

    <div class="vote_reset_wrap">투표 초기화</div>
    <div>
        <button onclick="vote_reset()">투표 초기화</button>
    </div>
</div>