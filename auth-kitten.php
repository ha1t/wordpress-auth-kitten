<?php
/*
Plugin Name: 子猫認証
Plugin URI: http://project-p.jp/halt/
Description: コメント欄に子猫認証を追加します。プラグインをインストールした後、フォルダ内にあるviewer.phpをwordpressのindex.phpと同じディレクトリに配置してください。
Version: 1.0.0
Author: halt
Author URI: http://project-p.jp/halt/
*/

function auth_kitten_render()
{
    global $user_ID;
    if ($user_ID !== 0) {
        return null;
    }

    require_once dirname(__FILE__) . '/Auth/Kitten.php';
    $kitten = new Auth_Kitten();
    $base_url = get_bloginfo('url');
    $html = $kitten->buildHtml($base_url . '/viewer.php?f=');
    echo '<div class="action">';
    echo '<p>ねこ認証:9つのパネルの中からねこを3匹選んでください</p>';
    echo $html;
    echo '</div>';
}

function auth_kitten_verify($comment)
{
    global $user_ID;

    // ログイン済みならチェックしない
    if ($user_ID !== 0) {
        add_filter('pre_comment_approved', create_function('$a', 'return \'0\';'));
        return $comment;
    }

    require_once dirname(__FILE__) . '/Auth/Kitten.php';
    $kitten = new Auth_Kitten();
    $result = $kitten->verify($_POST['kitten']);

    if ($result === false) {
        add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
    }

    return $comment;
}

add_action('comment_form', 'auth_kitten_render');
add_action('preprocess_comment', 'auth_kitten_verify', 1);
