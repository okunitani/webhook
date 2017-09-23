<?php

$LOG_FILE = dirname(__FILE__).'/hook.log';
$SECRET_KEY = 'R9vfMdVEotYDXK'; // secret_keyを用意し、secret_keyが一致するときのみ処理を行う

if ( isset($_GET['key']) && $_GET['key'] === $SECRET_KEY && isset($_POST['payload']) ) {
    $payload = json_decode($_POST['payload'], true);
    // githubはpayloadとうパラメータでコミットに関する情報をPOSTしてくるので、これを見てmasterの時のみgitのpullを行う
    if ($payload['ref'] === 'refs/heads/master') {
        exec(`~/opt/bin/git pull`);
        file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." ".$_SERVER['REMOTE_ADDR']." git pulled: ".$payload['head_commit']['message']."\n", FILE_APPEND|LOCK_EX);
    }
} else {
    file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}
