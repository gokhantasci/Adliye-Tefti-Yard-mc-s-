<?php
declare(strict_types=1);
// API bootstrap: JSON helpers, safe file IO, and common constants
header("Content-Type: application/json; charset=utf-8");
const DATA_DIR = __DIR__ . '/../data';
const NOTES_FILE = DATA_DIR . '/notes.json';
if (!is_dir(DATA_DIR)) { mkdir(DATA_DIR, 0755, true); }
if (!file_exists(NOTES_FILE)) { file_put_contents(NOTES_FILE, json_encode([])); }
function respond($ok, $data = null, $code = 200) {
  http_response_code($code);
  echo json_encode(['ok'=>$ok, 'data'=>$data], JSON_UNESCAPED_UNICODE);
  exit;
}
function read_json_file(string $path) {
  $fp = fopen($path, 'r');
  if (!$fp) respond(false, 'read_error', 500);
  flock($fp, LOCK_SH);
  $raw = stream_get_contents($fp);
  flock($fp, LOCK_UN); fclose($fp);
  $arr = json_decode($raw ?: "[]", true);
  return is_array($arr) ? $arr : [];
}
function write_json_file(string $path, $arr) {
  $tmp = $path . '.tmp';
  $fp = fopen($tmp, 'w');
  if (!$fp) respond(false, 'write_error', 500);
  flock($fp, LOCK_EX);
  fwrite($fp, json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
  fflush($fp);
  flock($fp, LOCK_UN); fclose($fp);
  rename($tmp, $path);
}
function body_json() {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw ?? "", true);
  return is_array($data) ? $data : [];
}
function uid(): string {
  return bin2hex(random_bytes(6));
}
