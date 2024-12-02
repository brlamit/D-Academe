<?php
session_start();

// Clear wallet session data
unset($_SESSION['account']);
unset($_SESSION['ethBalance']);
unset($_SESSION['tokenBalance']);

echo json_encode(['status' => 'success']);