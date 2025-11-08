<?php
// Notes API: list/add/edit/delete local JSON-backed notes
require __DIR__ . '/_bootstrap.php';
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
  $list = read_json_file(NOTES_FILE);
  respond(true, ['items'=>$list]);
}
if ($method === 'POST') {
  $b = body_json();
  $action = $b['action'] ?? '';
  $list = read_json_file(NOTES_FILE);
  if ($action === 'add') {
    $text = trim((string)($b['text'] ?? ''));
    if ($text === '') respond(false, 'empty_text', 422);
    $item = ['id'=>uid(), 'text'=>$text, 'ts'=>time()];
    array_unshift($list, $item);
    write_json_file(NOTES_FILE, $list);
    respond(true, ['item'=>$item]);
  }
  if ($action === 'edit') {
    $id = (string)($b['id'] ?? '');
    $text = trim((string)($b['text'] ?? ''));
    if ($id === '' || $text === '') respond(false, 'invalid_input', 422);
    $found = false;
    foreach ($list as &$it) {
      if ($it['id'] === $id) { $it['text'] = $text; $it['ts'] = time(); $found = true; break; }
    }
    if (!$found) respond(false, 'not_found', 404);
    write_json_file(NOTES_FILE, $list);
    respond(true, ['id'=>$id]);
  }
  if ($action === 'delete') {
    $id = (string)($b['id'] ?? '');
    if ($id === '') respond(false, 'invalid_input', 422);
    $list = array_values(array_filter($list, fn($it) => $it['id'] !== $id));
    write_json_file(NOTES_FILE, $list);
    respond(true, ['id'=>$id]);
  }
  respond(false, 'unknown_action', 400);
}
respond(false, 'method_not_allowed', 405);
