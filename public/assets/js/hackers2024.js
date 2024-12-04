let isProcessing = false;
function ajax_request(data){
    const action = data.get('action');
    console.log(action);

    if(isProcessing){
        return;
    }

    isProcessing = true;
    $.ajax({
        url: '/?page=action',
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        dataType: 'json',
        async: false,
        success: function(response){
            isProcessing = false;

            if(typeof response == 'string'){    
                response = JSON.parse(response);
            }

            if(response.message){
                alert(response.message);
            }

            // 성공 시 액션별 처리
            if(response.result == 'success'){
                switch(action){
                    case 'login':
                        location.href = '/';
                        break;

                    case 'vote':
                        $('.alert_wrap').fadeOut();
                        $('.vote_pop').fadeOut();
                        $('.vote_confirm').fadeIn();

                        myVote.setVote(response.p_id, response.vote_type);
                        myVote.trophyBadgeList();
                        myVote.trophyBadge();
                        myVote.trophyBadgePop();

                        openPopConfirm(response.p_id, response.vote_type);
                        break;

                    default:
                        location.reload();
                        break;
                }
            }

            // 실패 시 액션별 처리
            if(response.result == 'fail'){
                switch(action){
                    case 'vote':
                        location.reload();
                        break;
                }
            }
        }, 
        error: function(xhr, status, error){
            isProcessing = false;   
            alert('오류가 발생했습니다.');
        }
    });
}

function login(){
    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('user_mobile', $('[name=user_mobile]').val());
    formData.append('onoff', $('[name=onoff]').val());
    
    ajax_request(formData);
}

function vote(){
    const formData = new FormData();
    const p_id = $('#selected_participant input[name=selected_p_id]').val();
    const vote_type = $('.vote_bott input[name=trophy]:checked').val();

    if(!p_id || !vote_type){
        alert('투표 정보가 올바르지 않습니다.');
        closePop();
        $('.alert_wrap').fadeOut();
        return;
    }

    const p_id_arr = myVote.getPId();
    if(p_id_arr.includes(p_id)){
        alert('이미 투표하였습니다.');
        closePop();
        $('.alert_wrap').fadeOut();
        return;
    }

    const vote_type_arr = myVote.getVotedType();
    if(vote_type_arr.includes(vote_type)){
        alert('이미 사용한 트로피입니다.');
        closePop();
        $('.alert_wrap').fadeOut();
        return;
    }

    formData.append('action', 'vote');
    formData.append('p_id', p_id);
    formData.append('vote_type', vote_type);

    ajax_request(formData);
}

// 투표창 설정 시작/종료
function onoff(onoff){
    const formData = new FormData();
    formData.append('action', 'onoff');
    formData.append('onoff', onoff);

    ajax_request(formData);
}

// 참가자 추가
function add_participant(){
    const team_name = $('#new input[name="team_name"]').val();
    const title = $('#new input[name="title"]').val();
    const imageFile = $('#new input[name="image"]')[0].files[0]; // 파일 객체 가져오기

    if(!validate_participant({team_name: team_name, title: title, image: imageFile})){
        return;
    }

    const formData = new FormData();
    formData.append('action', 'add_participant');
    formData.append('team_name', team_name);
    formData.append('title', title);
    formData.append('image', imageFile);

    ajax_request(formData);
}

// 참가자 수정
function modify_participant(p_id){
    const team_name = $('#modify_'+p_id+' input[name="team_name"]').val();
    const title = $('#modify_'+p_id+' input[name="title"]').val();
    const imageFile = $('#modify_'+p_id+' input[name="image"]')[0].files[0]; // 파일 객체 가져오기
    let original_image = $('#modify_'+p_id+' input[name="original_image"]').val();
    let original_image_name = $('#modify_'+p_id+' input[name="original_image_name"]').val();

    // 기존 이미지가 있는데 새롭게 이미지를 등록한 경우
    if(original_image && imageFile){
        if(!validate_participant({team_name: team_name, title: title, image: imageFile})){
            return;
        }

        original_image = '';
        original_image_name = '';
    } else {
        if(!validate_participant({team_name: team_name, title: title})){
            return;
        }
    }

    const formData = new FormData();
    formData.append('action', 'modify_participant');
    formData.append('p_id', p_id);
    formData.append('team_name', team_name);
    formData.append('title', title);
    formData.append('original_image', original_image);
    formData.append('original_image_name', original_image_name);
    formData.append('image', imageFile);

    ajax_request(formData);
}

// 참가자 삭제
function del_participant(p_id){
    const formData = new FormData();
    formData.append('action', 'del_participant');
    formData.append('p_id', p_id);

    ajax_request(formData);
}

// 참가자 유효성 검사
function validate_participant(data){
    for(const key in data){
        if(!data[key]){
            if(key == 'image'){
                alert('이미지를 등록해주세요.');
            }else{
                alert('빈칸을 채워주세요.');
            }
            return false;
        }
    }
    return true;
}

// 유저 추가
function add_user(){
    const formData = new FormData();
    formData.append('action', 'add_user');
    formData.append('user_mobile', $('[name=user_mobile]').val());
    formData.append('user_level', $('[name=user_level]:checked').val());

    ajax_request(formData);
}

// 투표 초기화
function vote_reset(){
    let confirmCount = 10; // 필요 횟수
    let count = 0;

    for(let i = 0; i < confirmCount; i++){
        if(confirm('투표 초기화를 진행하시겠습니까? 내용은 복구되지 않습니다. ' + (confirmCount - count))){
            count += 1;
        } else {
            return;
        }
    }

    const formData = new FormData();
    formData.append('action', 'vote_reset');

    ajax_request(formData);
}