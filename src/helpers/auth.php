<?php

// 로그인 여부 체크
function isLogin() {
    if(empty($_SESSION['hackers2024_member_id']) || empty($_SESSION['hackers2024_member_cp']) || empty($_SESSION['hackers2024_member_user_level'])) {
        return false;
    }

    return true;
}

// 관리자 여부 체크
function isAdmin() {
    if(empty($_SESSION['hackers2024_member_user_level']) || $_SESSION['hackers2024_member_user_level'] != '2') {
        return false;
    }

    return true;
}

// 로그인 여부 체크 후 아니면 로그인페이지로 리다이렉트
function checkLogin() {
    if(!isLogin()){
        header("Location: /?page=login");
        exit;
    }
}

// 관리자 여부 체크 후 아니면 메인페이지로 리다이렉트
function checkAdmin() {
    if(!isAdmin()) {
        header("Location: /");
        exit;
    }
}

// 관리자 페이지 권한 체크
function checkAdminPageAuth() {
    checkLogin();
    checkAdmin();
}