<?php

header("Content-Type: application/json");

// Load config
$config = require __DIR__ . "/../config/chatbot.php";

// Helper: consistent JSON response
function json_ok($reply)
{
    echo json_encode(["success" => true, "reply" => $reply]);
    exit;
}

function json_error($message, $extra = null)
{
    $out = ["success" => false, "message" => $message];
    if ($extra !== null) {
        $out['debug'] = $extra;
    }
    echo json_encode($out);
    exit;
}


// Support GET history: ?action=history&limit=50&page=1
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'history') {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    require_once __DIR__ . '/../models/ChatModel.php';
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập', 'data' => []], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $cm = new ChatModel();
    $data = $cm->getHistory($_SESSION['user_id'], $limit, $offset);
    echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

// Check API Key
if (empty($config['api_key'])) {
    json_error("Thiếu API KEY Gemini");
}

// Parse incoming JSON body (client sends application/json)
$rawInput = file_get_contents('php://input');
$inputData = [];
$contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $inputData = json_decode($rawInput, true) ?: [];
}

// Support form-encoded fallback via $_POST
$userMessage = trim($inputData['message'] ?? $_POST['message'] ?? '');
if ($userMessage === '') {
    json_error("Không có dữ liệu message.");
}

// Gemini API payload (contents -> parts -> text)
$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => $config['system_prompt']],
                ["text" => $userMessage]
            ]
        ]
    ]
];

// Gemini API URL
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$config['model']}:generateContent?key={$config['api_key']}";

// CURL CALL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    $err = curl_error($ch);
    // log
    @file_put_contents(__DIR__ . "/../logs/chatbot_errors.log", date("Y-m-d H:i:s") . " | CURL ERROR | " . $err . PHP_EOL, FILE_APPEND);
    json_error("Lỗi kết nối tới API.", $err);
}

curl_close($ch);

$data = json_decode($response, true);

// Log non-200 responses for debugging
if ($httpCode >= 400) {
    @file_put_contents(__DIR__ . "/../logs/chatbot_errors.log", date("Y-m-d H:i:s") . " | HTTP $httpCode | " . ($response ?: json_encode($data)) . PHP_EOL, FILE_APPEND);
    $message = isset($data['error']['message']) ? $data['error']['message'] : "API returned HTTP $httpCode";
    json_error($message);
}

// Try to extract the reply from several possible Gemini response shapes
function extract_reply($data)
{
    if (!is_array($data)) return null;

    // common candidate locations
    $paths = [
        ['candidates', 0, 'content', 0, 'text'],
        ['candidates', 0, 'content', 0, 'parts', 0, 'text'],
        ['candidates', 0, 'content', 'parts', 0, 'text'],
        ['candidates', 0, 'message', 'content', 0, 'text'],
        ['candidates', 0, 'message', 'content', 0, 'parts', 0, 'text'],
        ['candidates', 0, 'output', 0, 'content', 0, 'text'],
        ['candidates', 0, 'output', 0, 'content', 0, 'parts', 0, 'text'],
    ];

    foreach ($paths as $p) {
        $v = $data;
        $found = true;
        foreach ($p as $key) {
            if (is_int($key)) {
                if (!isset($v[$key])) { $found = false; break; }
                $v = $v[$key];
            } else {
                if (!isset($v[$key])) { $found = false; break; }
                $v = $v[$key];
            }
        }
        if ($found && is_string($v) && trim($v) !== '') {
            return $v;
        }
    }

    // fallback: search for first string value under candidates
    if (isset($data['candidates']) && is_array($data['candidates'])) {
        foreach ($data['candidates'] as $cand) {
            $json = json_encode($cand);
            if ($json) {
                // try to find text fields
                if (preg_match('/"text"\s*:\s*"([^"]+)"/i', $json, $m)) {
                    return stripcslashes($m[1]);
                }
            }
        }
    }

    return null;
}

$reply = extract_reply($data);
if ($reply === null) {
    @file_put_contents(__DIR__ . "/../logs/chatbot_errors.log", date("Y-m-d H:i:s") . " | PARSE ERROR | " . ($response ?: json_encode($data)) . PHP_EOL, FILE_APPEND);
    json_error("Gemini không trả lời (không thể phân tích phản hồi).", $data ?: $response);
}

// Save history (if user logged in)
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
try {
    if (isset($_SESSION['user_id'])) {
        require_once __DIR__ . '/../models/ChatModel.php';
        $cm = new ChatModel();
        // Save user message then assistant reply
        $cm->saveMessage($_SESSION['user_id'], 'user', $userMessage);
        $cm->saveMessage($_SESSION['user_id'], 'assistant', $reply);
    }
} catch (Exception $e) {
    // log but don't fail the response
    @file_put_contents(__DIR__ . "/../logs/chatbot_errors.log", date("Y-m-d H:i:s") . " | SAVE ERROR | " . $e->getMessage() . PHP_EOL, FILE_APPEND);
}

json_ok($reply);
