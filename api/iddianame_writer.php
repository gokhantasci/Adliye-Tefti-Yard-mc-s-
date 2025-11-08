<?php
declare(strict_types=1);

@ini_set('display_errors','0');
@ini_set('zlib.output_compression','Off');
if (function_exists('ob_get_level')) { while (ob_get_level()>0) { @ob_end_clean(); } }

function jerr(int $code, string $msg){
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok'=>false,'reason'=>$msg], JSON_UNESCAPED_UNICODE);
  exit;
}

// Health probe
if (isset($_GET['ping'])) {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'ok'        => true,
    'cwd'       => getcwd(),
    'script'    => __FILE__,
    'temp'      => sys_get_temp_dir(),
    'hasZip'    => class_exists('ZipArchive'),
    'tplExists' => is_file(__DIR__.'/../data/iddianame.docx'),
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

// ---------- Input (defensive)
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$ctype  = $_SERVER['CONTENT_TYPE']    ?? '';
$raw    = file_get_contents('php://input') ?: '';
$data   = null;

if ($raw !== '') { $data = json_decode($raw, true); }
if (!is_array($data) && $raw !== '' && stripos($ctype, 'application/x-www-form-urlencoded') !== false) {
  parse_str($raw, $formArr);
  if (isset($formArr['payload'])) {
    $data = json_decode((string)$formArr['payload'], true);
  } elseif (isset($formArr['rows'])) {
    $data = [
      'birimAdi'       => $formArr['birimAdi']       ?? '',
      'denetimAraligi' => $formArr['denetimAraligi'] ?? '',
      'rows'           => json_decode((string)$formArr['rows'], true)
    ];
  }
}
if (!is_array($data) && !empty($_POST)) {
  if (isset($_POST['payload'])) {
    $data = json_decode((string)$_POST['payload'], true);
  } elseif (isset($_POST['rows'])) {
    $data = [
      'birimAdi'       => $_POST['birimAdi']       ?? '',
      'denetimAraligi' => $_POST['denetimAraligi'] ?? '',
      'rows'           => json_decode((string)$_POST['rows'], true)
    ];
  }
}
if (!is_array($data)) {
  jerr(400, 'Geçersiz JSON (method='.$method.', ctype='.($ctype ?: 'yok').', rawLen='.strlen($raw).')');
}

$rows  = $data['rows'] ?? null;
$birim = (string)($data['birimAdi'] ?? '');
if (!is_array($rows)) jerr(400,'rows alanı eksik veya hatalı.');

// ---------- Template / working ----------
$tplPath = __DIR__ . '/../data/iddianame.docx';
if (!is_file($tplPath)) jerr(404,'Şablon bulunamadı: /data/iddianame.docx');
if (!class_exists('ZipArchive')) jerr(500,'PHP ZipArchive eklentisi yok.');

$tmpBase = is_writable(sys_get_temp_dir()) ? sys_get_temp_dir() : (__DIR__.'/../data');
$tmpFile = tempnam($tmpBase, 'idd_');
if (!$tmpFile) jerr(500,'Geçici dosya oluşturulamadı.');
$outDocx = $tmpFile . '.docx';
if (!copy($tplPath, $outDocx)) jerr(500,'Şablon kopyalanamadı.');

$zip = new ZipArchive();
if ($zip->open($outDocx) !== true) jerr(500,'DOCX açılamadı (Zip).');

$xmlPath = 'word/document.xml';
$xml = $zip->getFromName($xmlPath);
if ($xml === false) { $zip->close(); jerr(500,'word/document.xml yok.'); }

// Yerine koymalarda metni güvenli hale getir
$xmlEnt = static function(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8'); };
// TR büyük harfe çevir (i/ı, ö/Ö vb.)
function tr_upper(string $s): string {
  $s = strtr($s, [
    'i'=>'İ','ı'=>'I','ğ'=>'Ğ','ü'=>'Ü','ş'=>'Ş','ö'=>'Ö','ç'=>'Ç',
  ]);
  return mb_strtoupper($s, 'UTF-8');
}
// TR sadece ilk harfi büyük yap (kalanı olduğu gibi bırakır)
function tr_ucfirst(string $s): string {
  if ($s === '') return '';
  $first = mb_substr($s, 0, 1, 'UTF-8');
  $rest  = mb_substr($s, 1, null, 'UTF-8');
  return tr_upper($first) . $rest;
}
// TR capitalize: tamamını küçük yapıp ilk harfi büyük
function tr_capitalize(string $s): string {
  $s = mb_strtolower($s, 'UTF-8');
  return tr_ucfirst($s);
}

// ${BIRIM_ADI} yerleştir (tümü BÜYÜK HARF) ve {{TARIH}} dd/mm/yyyy
$xml = str_replace('${BIRIM_ADI}', $xmlEnt(tr_upper($birim)), $xml);
$xml = str_replace('{{TARIH}}', date('d/m/Y'), $xml);
// ${YER}-${TARIH}: BIRIM_ADI ilk kelimenin ilk harfi büyük + güncel yıl
$firstWord = '';
$trimBirim = trim($birim);
if ($trimBirim !== '') {
  $parts = preg_split('/\s+/', $trimBirim);
  $firstWord = isset($parts[0]) ? (string)$parts[0] : '';
}
$yerWord = tr_capitalize($firstWord);
$year = date('Y');
// Birleşik yer tutucu tek parça ise
$xml = str_replace('${YER}-${TARIH}', $xmlEnt($yerWord.'-'.$year), $xml);
// Ayrık yer tutucular için ayrıca tek tek değiştir
$xml = str_replace('${YER}', $xmlEnt($yerWord), $xml);
$xml = str_replace('${TARIH}', $year, $xml);

// DOM yükle
$doc = new DOMDocument();
$doc->preserveWhiteSpace = true;
$doc->formatOutput = false;
if (!@$doc->loadXML($xml)) { $zip->close(); jerr(500,'Şablon document.xml okunamadı.'); }

$xp  = new DOMXPath($doc);
$nsW = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
$xp->registerNamespace('w', $nsW);

// Yardımcılar
$createTextCell = function(string $text) use ($doc, $nsW): DOMElement {
  $tc = $doc->createElementNS($nsW, 'w:tc');
  $p  = $doc->createElementNS($nsW, 'w:p');
  $r  = $doc->createElementNS($nsW, 'w:r');
  $t  = $doc->createElementNS($nsW, 'w:t');
  $t->appendChild($doc->createTextNode($text));
  $r->appendChild($t); $p->appendChild($r); $tc->appendChild($p);
  return $tc;
};
$buildSimpleTable = function(array $rows) use ($doc, $nsW, $createTextCell): DOMElement {
  $tbl = $doc->createElementNS($nsW, 'w:tbl');
  // Basit başlık
  $hdr = $doc->createElementNS($nsW, 'w:tr');
  foreach (['SIRA','İDDİANAME NO','İDD. DEĞERLENDİRME NO','İDD. GÖNDERİLDİĞİ TARİH','İDD. DEĞERLENDİRME TARİHİ','DEĞERLENDİRME (Kabul-İade)','SÜRE (Gün)','HAKİM'] as $h){
    $hdr->appendChild($createTextCell($h));
  }
  $tbl->appendChild($hdr);
  // Veri
  foreach ($rows as $i=>$r){
    $tr = $doc->createElementNS($nsW, 'w:tr');
    $vals = [
      (string)($i+1),
      (string)($r['iddianameNo']    ?? ''),
      (string)($r['degerNo']        ?? ''),
      (string)($r['gonderimTarihi'] ?? ''),
      (string)($r['degerTar']       ?? ''),
      tr_ucfirst((string)($r['degerDurum'] ?? '')),
      (string)($r['sureGun']        ?? ''),
      (string)($r['hakim']          ?? ''),
    ];
    foreach ($vals as $v) { $tr->appendChild($createTextCell($v)); }
    $tbl->appendChild($tr);
  }
  return $tbl;
};

// 1) Önce ${sNo} içeren bir şablon satırı arayın
$tplNodes = $xp->query('//w:tr[.//w:t[contains(., "${sNo}")]]');
if ($tplNodes && $tplNodes->length > 0) {
  $tplTr = $tplNodes->item(0);
  $parentTbl = $tplTr->parentNode; // w:tbl
  if (!$parentTbl) { $zip->close(); jerr(500,'Şablon satırı bulundu ama tablo hiyerarşisi hatalı.'); }

  // Üretilen satırları ekle
  foreach ($rows as $i => $r) {
    $tr = $tplTr->cloneNode(true);
    // Placeholder metinlerini değiştir
    $map = [
      '${sNo}'     => (string)($i+1),
      '${IDD_NO}'  => (string)($r['iddianameNo']    ?? ''),
      '${DEG_NO}'  => (string)($r['degerNo']        ?? ''),
      '${GON_TAR}' => (string)($r['gonderimTarihi'] ?? ''),
      '${DEG_TAR}' => (string)($r['degerTar']       ?? ''),
      '${DEG_DUR}' => tr_ucfirst((string)($r['degerDurum'] ?? '')),
      '${SURE}'    => (string)($r['sureGun']        ?? ''),
      '${HAKIM}'   => (string)($r['hakim']          ?? ''),
    ];
    foreach ($xp->query('.//w:t', $tr) as $tn) {
      $text = $tn->textContent;
      foreach ($map as $k=>$v) { if (strpos($text,$k) !== false) { $text = str_replace($k, $v, $text); } }
      $tn->textContent = $text;
    }
    $parentTbl->insertBefore($tr, $tplTr);
  }
  // Şablon satırı kaldır
  $parentTbl->removeChild($tplTr);

} else {
  // 2) Şablon satırı yoksa, basit bir tablo üretip ekleyin (stil: şablondaki tablo stilinden bağımsız)
  $tbl = $buildSimpleTable($rows);
  $body = $xp->query('/w:document/w:body')->item(0);
  if (!$body) { $zip->close(); jerr(500,'Belgede w:body bulunamadı.'); }
  // sectPr varsa onun önüne, yoksa body sonuna ekle
  $sect = $xp->query('/w:document/w:body/w:sectPr')->item(0);
  if ($sect) { $body->insertBefore($tbl, $sect); } else { $body->appendChild($tbl); }
}

// Paragraf bazlı daha güvenilir güncelleme:
// Yeni şablonunuzda "Kayıtlara uygun olduğu tasdik olunur." satırından hemen sonra
// ayrı bir paragrafta ${YER}-${TARIH} geliyor. Word run'ları (w:t) parçalara ayırabildiği
// için önce tüm paragraf metinlerini birleştirip arama yapıyoruz; eşleşme bulursak
// bir sonraki paragrafı (w:p) Yer-DD-MM-YYYY şeklinde yazıyoruz.
try {
  // normalize eden yardımcı
  $normalize = function(string $s): string {
    $s = strtr($s, ['İ'=>'i','I'=>'i','ı'=>'i','ğ'=>'g','Ğ'=>'g','ü'=>'u','Ü'=>'u','ş'=>'s','Ş'=>'s','ö'=>'o','Ö'=>'o','ç'=>'c','Ç'=>'c']);
    return mb_strtolower($s, 'UTF-8');
  };

  // 1) Eğer DOCX içinde doğrudan ${YER}-${TARIH} placeholder'ı tek tırnaklı bir w:t içinde duruyorsa,
  //    önce onu bulup paragrafını güncelleyelim (bu durumda paragrafın içindeki runs da temizlenir).
  $found = false;
  $placeholderNodes = $xp->query('//w:t[contains(., "${YER}-${TARIH}")]');
  if ($placeholderNodes && $placeholderNodes->length > 0) {
    $firstWord = '';
    $trimBirim = trim($birim);
    if ($trimBirim !== '') { $parts = preg_split('/\s+/', $trimBirim); $firstWord = isset($parts[0]) ? (string)$parts[0] : ''; }
    $yerWord = tr_ucfirst(mb_strtolower($firstWord, 'UTF-8'));
    $targetText = $yerWord . '-' . date('d-m-Y');

    foreach ($placeholderNodes as $pn) {
      $p = $pn->parentNode;
      while ($p && !($p instanceof DOMElement && $p->namespaceURI === $nsW && $p->localName === 'p')) {
        $p = $p->parentNode;
      }
      if ($p instanceof DOMElement) {
        $tNodes = $xp->query('.//w:t', $p);
        if ($tNodes && $tNodes->length > 0) {
          $tNodes->item(0)->textContent = $targetText;
          for ($i=1; $i<$tNodes->length; $i++) { $tNodes->item($i)->textContent = ''; }
        } else {
          $r = $doc->createElementNS($nsW, 'w:r');
          $t = $doc->createElementNS($nsW, 'w:t');
          $t->appendChild($doc->createTextNode($targetText));
          $r->appendChild($t); $p->appendChild($r);
        }
        $found = true;
      }
    }
  }

  // 2) Eğer 1. yol işlememişse, "Kayıtlara uygun olduğu tasdik olunur." paragrafını ara
  //    ve onun hemen sonraki w:p paragrafını (hangi run/parça halinde olursa olsun)
  //    Yer-DD-MM-YYYY ile değiştir.
  if (!$found) {
    $paras = $xp->query('//w:p');
    for ($pi = 0; $pi < $paras->length; $pi++) {
      $p = $paras->item($pi);
      // Paragraf içindeki tüm w:t'leri birleştir
      $txt = '';
      foreach ($xp->query('.//w:t', $p) as $tn) { $txt .= $tn->textContent; }
      $norm = $normalize($txt);
      if (strpos($norm, 'kayitlara uygun oldugu tasdik olunur') !== false) {
        // bir sonraki gerçek paragrafı bul
        $next = $p->nextSibling;
        $steps = 0;
        while ($next && !($next instanceof DOMElement && $next->namespaceURI === $nsW && $next->localName === 'p') && $steps < 12) {
          $next = $next->nextSibling; $steps++;
        }
        if ($next instanceof DOMElement) {
          $firstWord = '';
          $trimBirim = trim($birim);
          if ($trimBirim !== '') { $parts = preg_split('/\s+/', $trimBirim); $firstWord = isset($parts[0]) ? (string)$parts[0] : ''; }
          $yerWord = tr_ucfirst(mb_strtolower($firstWord, 'UTF-8'));
          $targetText = $yerWord . '-' . date('d/m/Y');
          $tNodes = $xp->query('.//w:t', $next);
          if ($tNodes && $tNodes->length > 0) {
            $tNodes->item(0)->textContent = $targetText;
            for ($i=1; $i<$tNodes->length; $i++) { $tNodes->item($i)->textContent = ''; }
          } else {
            $r = $doc->createElementNS($nsW, 'w:r');
            $t = $doc->createElementNS($nsW, 'w:t');
            $t->appendChild($doc->createTextNode($targetText));
            $r->appendChild($t); $next->appendChild($r);
          }
        }
        // Bir eşleşmeyle işimiz bitti — genelde tek bir "tasdik olunur" olur.
        break;
      }
    }
  }
} catch (Throwable $__) { /* no-op */ }

// Not: Paragraflarda (w:p) stilin bozulmaması için genel bir toplu rewrite uygulanmıyor; baştaki
// basit yer değiştirmeler $xml üzerinde yapılır.

// Geçerli XML olarak yaz
$xmlOut = $doc->saveXML();
if ($xmlOut === false) { $zip->close(); jerr(500,'XML serialize edilemedi.'); }
$zip->addFromString($xmlPath, $xmlOut);
$zip->close();

// ---------- Stream ----------
if (function_exists('ob_get_level')) { while (ob_get_level()>0) { @ob_end_clean(); } }
if (!is_file($outDocx)) jerr(500,'DOCX kaydedilemedi.');
if (filesize($outDocx) <= 0) jerr(500,'DOCX boş kaydedildi.');

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="iddianame.docx"');
header('Content-Length: '.filesize($outDocx));
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: public');

$fh = fopen($outDocx,'rb');
if ($fh) { fpassthru($fh); fclose($fh); } else { readfile($outDocx); }
@unlink($outDocx);
@unlink($tmpFile);
exit;