<?php
/**
 * News API Endpoint
 * Haber verilerini JSON formatında döndürür
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

$newsFile = __DIR__ . '/../data/news.json';

if (!file_exists($newsFile)) {
    http_response_code(404);
    echo json_encode([
        'error' => true,
        'message' => 'Haber dosyası bulunamadı'
    ]);
    exit;
}

$newsData = file_get_contents($newsFile);
if ($newsData === false) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Haber dosyası okunamadı'
    ]);
    exit;
}

// JSON doğrulama
$news = json_decode($newsData);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Geçersiz JSON formatı'
    ]);
    exit;
}

// Başarılı yanıt
echo $newsData;
