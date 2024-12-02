<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $_SESSION['account'] = $data['account'];
    $_SESSION['ethBalance'] = $data['ethBalance'];
    $_SESSION['tokenBalance'] = $data['tokenBalance'];
}
?>