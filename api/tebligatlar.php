<?php
// /api/tebligatlar.php
declare(strict_types=1);

// CORS gerekirse açın (aynı origin ise şart değil)
// header('Access-Control-Allow-Origin: https://teftis.657.com.tr');
// header('Vary: Origin');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

$path = realpath(__DIR__ . '/../data/tebligatlar.json');
if (!$path || !is_readable($path)) {
  http_response_code(404);
  echo json_encode(['error' => 'tebligatlar.json not found or not readable']);
  exit;
}

readfile($path);
