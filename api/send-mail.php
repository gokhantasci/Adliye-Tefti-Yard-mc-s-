<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

if (php_sapi_name() !== 'cli') {
  $hpHeader = $_SERVER['HTTP_X_HP'] ?? '';
  if (!empty($hpHeader)) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'stage'=>'GUARD','error'=>'honeypot']);
    exit;
  }

  $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? 'na', 0, 120);
  $now = time();

  $guardFile = __DIR__ . '/../data/mail_guard.json';
  if (!is_dir(dirname($guardFile))) @mkdir(dirname($guardFile), 0775, true);
  $h = @fopen($guardFile, 'c+');

  if ($h) {
    @flock($h, LOCK_EX);
    $raw = stream_get_contents($h);
    $data = json_decode($raw ?: '[]', true);
    if (!is_array($data)) $data = [];

    // Eski kayıtları temizle (2 saat)
    foreach ($data as $k=>$row) {
      if (!isset($row['ts']) || $now - (int)$row['ts'] > 7200) unset($data[$k]);
    }

    // İsterseniz anahtarınızı IP+UA yapabilirsiniz: $key = $ip.'|'.$ua;
    $key = $ip;
    $row = $data[$key] ?? ['ts'=>0,'cnt1'=>0,'cnt10'=>0];

    // Pencereleri resetle
    if ($now - (int)$row['ts'] > 600) { $row['cnt10'] = 0; } // 10 dakika
    if ($now - (int)$row['ts'] > 60)  { $row['cnt1']  = 0; } // 60 saniye

    $row['cnt1']  = (int)$row['cnt1'];
    $row['cnt10'] = (int)$row['cnt10'];

    // --- LİMİT AŞIMI: 60 saniye penceresi ---
    if ($row['cnt1'] >= 1) {
      $elapsed = $now - (int)$row['ts'];
      $wait = max(1, 60 - $elapsed);
      header('Retry-After: '.$wait);
      http_response_code(429);
      echo json_encode([
        'ok'          => false,
        'stage'       => 'GUARD',
        'error'       => 'rate-60s',
        'retry_after' => $wait,
        'message'     => "Çok sayıda istek. Lütfen {$wait} saniye bekleyip tekrar deneyin."
      ], JSON_UNESCAPED_UNICODE);
      @flock($h, LOCK_UN); @fclose($h);
      exit;
    }

    // --- LİMİT AŞIMI: 10 dakika penceresi ---
    if ($row['cnt10'] >= 5) {
      $elapsed = $now - (int)$row['ts'];
      $wait = max(1, 600 - $elapsed);
      header('Retry-After: '.$wait);
      http_response_code(429);
      echo json_encode([
        'ok'          => false,
        'stage'       => 'GUARD',
        'error'       => 'rate-10m',
        'retry_after' => $wait,
        'message'     => "Gönderim sınırı aşıldı. Lütfen {$wait} saniye bekleyin."
      ], JSON_UNESCAPED_UNICODE);
      @flock($h, LOCK_UN); @fclose($h);
      exit;
    }

    // Sayaçları artır ve yaz
    $row['cnt1']  += 1;
    $row['cnt10'] += 1;
    $row['ts']     = $now;
    $data[$key]    = $row;

    ftruncate($h, 0); rewind($h);
    fwrite($h, json_encode($data, JSON_UNESCAPED_UNICODE));
    @flock($h, LOCK_UN);
    fclose($h);
  }

  if (!empty($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
  }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
set_exception_handler(function($e){
  http_response_code(500);
  echo json_encode(['ok'=>false,'stage'=>'EXC','error'=>$e->getMessage()]);
});
set_error_handler(function($no,$str,$file,$line){
  http_response_code(500);
  echo json_encode(['ok'=>false,'stage'=>'ERR',"error"=>"($no) $str @ $file:$line"]);
  return true;
});
$raw = file_get_contents('php://input') ?: '';
$payload = json_decode($raw, true);
if (!is_array($payload)) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'stage'=>'INPUT','error'=>'JSON parse hatası','raw'=>$raw]);
  exit;
}
$to        = trim((string)($payload['to'] ?? ''));
$subject   = (string)($payload['subject'] ?? 'Bilgilendirme');
$textBody  = (string)($payload['body'] ?? '');
$htmlBody  = isset($payload['html']) ? (string)$payload['html'] : null;
$replyTo   = isset($payload['reply_to']) ? (string)$payload['reply_to'] : null;
$fromName  = isset($payload['from_name']) ? (string)$payload['from_name'] : 'Teftis Sistemi';
if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'stage'=>'INPUT','error'=>'Geçerli e-posta adresi değil']);
  exit;
}
if (!preg_match('/@adalet\.gov\.tr$/i', $to)) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'stage'=>'INPUT','error'=>'Sadece @adalet.gov.tr adreslerine izin verilir.']);
  exit;
}
$isKurum = (bool)preg_match('/@adalet\.gov\.tr$/i', $to);
$base = __DIR__.'/lib/phpmailer';
$need = [$base.'/Exception.php', $base.'/PHPMailer.php', $base.'/SMTP.php'];
foreach ($need as $f) {
  if (!file_exists($f)) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'stage'=>'LIB','error'=>"Eksik PHPMailer dosyası: $f"]);
    exit;
  }
}
require $base.'/Exception.php';
require $base.'/PHPMailer.php';
require $base.'/SMTP.php';
$gmailUser = getenv('GMAIL_USER') ?: '';           
$gmailPass = getenv('GMAIL_APP_PASSWORD') ?: '';   
if (!$gmailUser || !$gmailPass) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'stage'=>'ENV','error'=>'GMAIL_USER veya GMAIL_APP_PASSWORD boş']);
  exit;
}
function to_safe_plain_text(string $text, ?string $html): string {
  if ($html !== null && $html !== '') {
    $noTags = strip_tags($html);
    $noTags = preg_replace('/[ \t]+/', ' ', $noTags);
    $noTags = preg_replace('/\R{3,}/', "\n\n", $noTags);
    return trim($noTags);
  }
  return trim($text);
}
if ($htmlBody === null && $textBody !== '') {
  $htmlBody = nl2br(htmlspecialchars($textBody, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), false);
}
try {
  $mail = new PHPMailer(true);
  $mail->CharSet  = 'UTF-8';
  $mail->Encoding = 'quoted-printable';
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = $gmailUser;
  $mail->Password   = $gmailPass;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
  $mail->Port       = 587;
  $mail->setFrom($gmailUser, $fromName);
  $mail->addAddress($to);
  if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
    $mail->addReplyTo($replyTo, $replyTo);
  } else {
    $mail->addReplyTo($gmailUser, 'Gökhan TAŞÇI');
  }
  if ($isKurum) {
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body    = to_safe_plain_text($textBody, $htmlBody);
  } else {
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody ?: '<pre>'.htmlspecialchars($textBody, ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</pre>';
    $mail->AltBody = $textBody ?: strip_tags((string)$htmlBody);
  }
  $mail->send();
  echo json_encode(['ok'=>true,'msg'=>'Mail gönderildi ✅', 'mode'=>$isKurum ? 'plain' : 'html']);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'stage'=>'SMTP','error'=>$mail->ErrorInfo]);
}
