<?php
/**
 * API Bootstrap Dosyası
 * 
 * Bu dosya tüm API istekleri için temel yapılandırma ve yardımcı fonksiyonları sağlar.
 * JSON işlemleri, güvenli dosya okuma/yazma ve ortak sabitler burada tanımlanır.
 * 
 * @package AdliyeTeftis
 * @author  Gökhan TAŞÇI
 */

declare(strict_types=1);

// JSON yanıt için content-type başlığını ayarla
header("Content-Type: application/json; charset=utf-8");

// Veri dizini ve dosya yolları
const DATA_DIR = __DIR__ . '/../data';
const NOTES_FILE = DATA_DIR . '/notes.json';

// Veri dizinini oluştur (yoksa)
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Notlar dosyasını oluştur (yoksa)
if (!file_exists(NOTES_FILE)) {
    file_put_contents(NOTES_FILE, json_encode([]));
}

/**
 * JSON yanıt gönder ve scripti sonlandır
 * 
 * @param bool   $ok   İşlem başarılı mı?
 * @param mixed  $data Yanıt verisi
 * @param int    $code HTTP durum kodu
 * @return void
 */
function respond($ok, $data = null, $code = 200)
{
    http_response_code($code);
    echo json_encode(['ok' => $ok, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * JSON dosyasını güvenli şekilde oku
 * 
 * Dosya kilitleme (LOCK_SH) kullanarak eşzamanlı okuma işlemlerini destekler.
 * 
 * @param string $path Dosya yolu
 * @return array JSON verisi (dizi olarak)
 */
function read_json_file(string $path)
{
    $fp = fopen($path, 'r');
    
    if (!$fp) {
        respond(false, 'read_error', 500);
    }
    
    // Paylaşımlı kilit (okuma için)
    flock($fp, LOCK_SH);
    $raw = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    
    // JSON'u diziye çevir
    $arr = json_decode($raw ?: "[]", true);
    return is_array($arr) ? $arr : [];
}

/**
 * JSON dosyasını güvenli şekilde yaz
 * 
 * Atomik yazma için geçici dosya kullanır ve dosya kilitleme (LOCK_EX) ile
 * eşzamanlı yazma işlemlerini güvenli hale getirir.
 * 
 * @param string $path Dosya yolu
 * @param array  $arr  Yazılacak veri (dizi)
 * @return void
 */
function write_json_file(string $path, $arr)
{
    $tmp = $path . '.tmp';
    $fp = fopen($tmp, 'w');
    
    if (!$fp) {
        respond(false, 'write_error', 500);
    }
    
    // Özel kilit (yazma için)
    flock($fp, LOCK_EX);
    fwrite($fp, json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    
    // Atomik dosya değiştirme
    rename($tmp, $path);
}

/**
 * HTTP request body'sini JSON olarak oku
 * 
 * @return array JSON verisi (dizi olarak)
 */
function body_json()
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?? "", true);
    return is_array($data) ? $data : [];
}

/**
 * Benzersiz ID oluştur
 * 
 * @return string 12 karakterlik hexadecimal ID
 */
function uid(): string
{
    return bin2hex(random_bytes(6));
}
