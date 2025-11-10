<?php
// personel_hesap_tablo.php
// Tablo-tabanlı sürüm: Öğrenim durumları ve tavanlar doğrudan sizin verdiğiniz listeye göre.
// Ayrıca "Ekstra Durumlar" (derece/kademe artışı) checkbox'ları eklendi.

// --- Öğrenim Durumu Tablosu (ListIndex sırası ÖNEMLİ) ---
// label, bas_d, bas_k, max_d, max_k, max37_d, max37_k, sure (emsal hesabında kullanılır)
$EDU = [
/*0*/ ['label'=>'İlkokul',               'bas_d'=>15,'bas_k'=>1,'max_d'=>7,'max_k'=>9,'max37_d'=>6,'max37_k'=>9,'sure'=>5],
/*1*/ ['label'=>'Ortaokul',              'bas_d'=>14,'bas_k'=>2,'max_d'=>5,'max_k'=>9,'max37_d'=>4,'max37_k'=>9,'sure'=>8],
/*2*/ ['label'=>'Ortaokul (4 Yıllık)',   'bas_d'=>14,'bas_k'=>3,'max_d'=>5,'max_k'=>9,'max37_d'=>4,'max37_k'=>9,'sure'=>9],
/*3*/ ['label'=>'Lise',                  'bas_d'=>13,'bas_k'=>3,'max_d'=>3,'max_k'=>8,'max37_d'=>2,'max37_k'=>6,'sure'=>11],
/*4*/ ['label'=>'Lise (4 Yıllık)',       'bas_d'=>12,'bas_k'=>1,'max_d'=>3,'max_k'=>8,'max37_d'=>2,'max37_k'=>6,'sure'=>12],
/*5*/ ['label'=>'Meslek Lisesi',         'bas_d'=>12,'bas_k'=>2,'max_d'=>3,'max_k'=>8,'max37_d'=>2,'max37_k'=>6,'sure'=>11],
/*6*/ ['label'=>'Meslek Lisesi (4 Yıl)', 'bas_d'=>12,'bas_k'=>3,'max_d'=>3,'max_k'=>8,'max37_d'=>2,'max37_k'=>6,'sure'=>12],
/*7*/ ['label'=>'Yükseköğr. (2 Yıl)',    'bas_d'=>10,'bas_k'=>2,'max_d'=>1,'max_k'=>4,'max37_d'=>1,'max37_k'=>4,'sure'=>13],
/*8*/ ['label'=>'Yükseköğr. (4 Yıl)',    'bas_d'=> 9,'bas_k'=>1,'max_d'=>1,'max_k'=>4,'max37_d'=>1,'max37_k'=>4,'sure'=>15],
/*9*/ ['label'=>'Yüksek Lisans',         'bas_d'=> 9,'bas_k'=>2,'max_d'=>1,'max_k'=>4,'max37_d'=>1,'max37_k'=>4,'sure'=>15],
/*10*/['label'=>'Doktora',               'bas_d'=> 8,'bas_k'=>1,'max_d'=>1,'max_k'=>4,'max37_d'=>1,'max37_k'=>4,'sure'=>15],
];

// --- Ekstra Durumlar (Derece/Kademe artışları) ---
// not: derece +1 => toplamKademe'ye +3 ekler; kademe +1 => toplamKademe'ye +1 ekler
$EXTRAS = [
  '243/64.Sicil - 8 Yıllık' => ['derece'=>0, 'kademe'=>1],
  '458 SKHK/5289 SK'        => ['derece'=>1, 'kademe'=>0],
  'Teknik Hizmetler'        => ['derece'=>1, 'kademe'=>0],
  'Mühendis'                => ['derece'=>1, 'kademe'=>0],
  '657-36/A-6/b'            => ['derece'=>0, 'kademe'=>1],
];

// --- Yardımcılar ---
function tr_to_int($s){ return intval(trim((string)$s)); }
function parse_date_tr($s){ $s=trim((string)$s); if($s==='') return null; $p=explode('/',$s); if(count($p)!==3) return null; $d=intval($p[0]);$m=intval($p[1]);$y=intval($p[2]); if($d<1||$d>31||$m<1||$m>12||$y<1) return null; return [$d,$m,$y]; }
function fmt_date_tr($a){ if(!$a) return ''; return sprintf('%02d/%02d/%04d',$a[0],$a[1],$a[2]); }
function dmy_to_360($d,$m,$y){ return $y*360+$m*30+$d; }
function diff_360($d1,$m1,$y1,$d2,$m2,$y2){ return dmy_to_360($d2,$m2,$y2)-dmy_to_360($d1,$m1,$y1); }
function from_days_360($days){ $y=intdiv($days,360); $rem=$days-$y*360; $mo=intdiv($rem,30); $d=$rem-$mo*30; return [$y,$mo,$d]; }
function add_days_30_360($d,$m,$y,$days){ $tot=$y*360+$m*30+$d+$days; $ny=intdiv($tot,360); $r=$tot-$ny*360; $nm=intdiv($r,30); $nd=$r-$nm*30; if($nd==0){$nd=30;$nm--; if($nm<1){$nm+=12;$ny--;}} $nd=max(1,min(30,$nd)); $nm=max(1,min(12,$nm)); return [$nd,$nm,$ny]; }
function derece_kademe_son($bd,$bk,$tk){ $yd=$bd-intdiv($tk,3); $yk=$bk+($tk%3); if($yk>3){ $yd-=intdiv($yk,3); $yk=$yk%3; } return [$yd,$yk]; }
function tavan_uygula($d,$k,$md,$mk){ if($d<=$md){ $k+=($md-$d)*3; $d=$md; if($k>$mk) $k=$mk; } return [$d,$k]; }
function safe($k,$def=''){ return $_POST[$k] ?? $def; }

$hatalar=[]; $sonuc=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
  $mem_idx = (int)safe('mem_bas_idx',0);
  $son_idx = (int)safe('son_ogr_idx',0);
  $diploma=parse_date_tr(safe('diploma_tarihi',''));
  $islem  = parse_date_tr(safe('islem_tarihi',''));
  $gorev  = parse_date_tr(safe('gorev_bas',''));
  if(!$diploma||!$islem||!$gorev){ $hatalar[]='Tarih(ler) hatalı/eksik.'; }
  if(!isset($EDU[$mem_idx])||!isset($EDU[$son_idx])){ $hatalar[]='Öğrenim seçimi hatalı.'; }

  if(!$hatalar){
    $ob = $EDU[$mem_idx]['sure'];
    $os = $EDU[$son_idx]['sure'];
    $emsY = $diploma[2] + ($os - $ob);
    // VBA kural: aynı index veya mem_bas=8 ve son in [9,10] ise diploma tarihi; son in [7..10] ise 31/07; aksi 30/06
    $same = ($mem_idx===$son_idx) || ($mem_idx===8 && in_array($son_idx,[9,10]));
    if($same) $emsal = $diploma; else $emsal = in_array($son_idx,[7,8,9,10]) ? [31,7,$emsY] : [30,6,$emsY];

    $tarih = (dmy_to_360($gorev[0],$gorev[1],$gorev[2]) >= dmy_to_360($emsal[0],$emsal[1],$emsal[2])) ? $gorev : $emsal;
    $emsalVar = ($tarih===$emsal) ? 'Var' : 'Yok';

    // Parametreler
    $u_y=tr_to_int(safe('ucretsiz_yil',0)); $u_a=tr_to_int(safe('ucretsiz_ay',0)); $u_g=tr_to_int(safe('ucretsiz_gun',0));
    $f_y=tr_to_int(safe('fhzs_yil',0));     $f_a=tr_to_int(safe('fhzs_ay',0));     $f_g=tr_to_int(safe('fhzs_gun',0));
    $sgk=tr_to_int(safe('sigorta_gun',0));  $uzm=tr_to_int(safe('fhzs_esas_gun',0));
    $ask_d=tr_to_int(safe('ask_dus_gun',0));$ask_top=tr_to_int(safe('askerlik_toplam_gun',0));
    $askT=parse_date_tr(safe('askerlik_tarihi',''));

    $u_days=$u_y*360+$u_a*30+$u_g; $f_days=$f_y*360+$f_a*30+$f_g;

    $guns = diff_360($tarih[0],$tarih[1],$tarih[2], $islem[0],$islem[1],$islem[2]) - $u_days + $uzm - $ask_d;
    [$hy,$ha,$hg]=from_days_360(max(0,$guns));
    $ekea = diff_360($tarih[0],$tarih[1],$tarih[2], $islem[0],$islem[1],$islem[2]) + $f_days - $u_days + $uzm + $sgk - $ask_d;
    [$ey,$ea,$eg]=from_days_360(max(0,$ekea));

    $emsalsiz = diff_360($gorev[0],$gorev[1],$gorev[2], $islem[0],$islem[1],$islem[2]) - $u_days + $uzm - $ask_d;
    [$esy,$esa,$esg]=from_days_360(max(0,$emsalsiz));
    $ekea_es = diff_360($gorev[0],$gorev[1],$gorev[2], $islem[0],$islem[1],$islem[2]) + $f_days - $u_days + $uzm + $sgk - $ask_d;
    [$eey,$eea,$eeg]=from_days_360(max(0,$ekea_es));

    $top=$guns; $etop=$ekea;
    if($askT && dmy_to_360($gorev[0],$gorev[1],$gorev[2]) > dmy_to_360($askT[0],$askT[1],$askT[2])){
      $top+=$ask_top; $etop+=$ask_top; $emsalsiz+=$ask_top; $ekea_es+=$ask_top;
    }
    [$ty,$ta,$tg]=from_days_360(max(0,$top)); [$tey,$tea,$teg]=from_days_360(max(0,$etop));

    // Başlangıç & tavan
    $bd=$EDU[$son_idx]['bas_d']; $bk=$EDU[$son_idx]['bas_k'];
    $md=$EDU[$son_idx]['max_d']; $mk=$EDU[$son_idx]['max_k'];
    $md37=$EDU[$son_idx]['max37_d']; $mk37=$EDU[$son_idx]['max37_k'];

    // Özel & Ekstralar
    $ozD=tr_to_int(safe('ozel_derece',0)); $ozK=tr_to_int(safe('ozel_kademe',0));
    $dusD=tr_to_int(safe('dus_derece',0)); $dusK=tr_to_int(safe('dus_kademe',0));
    $uyg37 = isset($_POST['uyg37']) ? true : false;

    // seçilen ekstra checkbox'larını toplam kademeye ekle
    $extraKademe=0;
    foreach($EXTRAS as $name=>$inc){
      if(isset($_POST['extra_'.$name])){
        $extraKademe += $inc['derece']*3 + $inc['kademe'];
      }
    }

    $tk = intdiv($top,360) + $ozD*3 + $ozK + $extraKademe - $dusD*3 - $dusK;
    [$sd,$sk]=derece_kademe_son($bd,$bk,$tk);
    $etk = intdiv($etop,360) + $ozD*3 + $ozK + $extraKademe - $dusD*3 - $dusK;
    [$sd2,$sk2]=derece_kademe_son($bd,$bk,$etk);

    if($uyg37){ [$sd,$sk]=tavan_uygula($sd,$sk,$md37,$mk37); [$sd2,$sk2]=tavan_uygula($sd2,$sk2,$md37,$mk37); }
    else{       [$sd,$sk]=tavan_uygula($sd,$sk,$md,$mk);     [$sd2,$sk2]=tavan_uygula($sd2,$sk2,$md,$mk); }

    // Terfi tarihleri
    $art=360-($top-intdiv($top,360)*360); $yeni = add_days_30_360($islem[0],$islem[1],$islem[2],$art);
    $eart=360-($etop-intdiv($etop,360)*360); $eyeni = add_days_30_360($islem[0],$islem[1],$islem[2],$eart);

    $sonuc = [
      'mem'=>$EDU[$mem_idx]['label'],'son'=>$EDU[$son_idx]['label'],
      'emsal'=>fmt_date_tr($emsal),'emsalVar'=>$emsalVar,
      'hizmet'=>"$hy yıl $ha ay $hg gün",'ekea'=>"$ey yıl $ea ay $eg gün",
      'emsalsiz'=>"$esy yıl $esa ay $esg gün",'ekea_emsalsiz'=>"$eey yıl $eea ay $eeg gün",
      'toplam'=>"$ty yıl $ta ay $tg gün",'ekea_toplam'=>"$tey yıl $tea ay $teg gün",
      'dk'=>"$sd/$sk",'ekea_dk'=>"$sd2/$sk2",
      'yeni_terfi'=>fmt_date_tr($yeni),'ekea_yeni_terfi'=>fmt_date_tr($eyeni),
      'uyg37'=>$uyg37?'Evet':'Hayır','extraKademe'=>$extraKademe,
    ];
  }
}
?>
<!doctype html>
<html lang="tr"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personel Hesap (Tablo Tabanlı)</title>
<style>
body{font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:#0b1220;color:#e6edf6;margin:0}
.container{max-width:1200px;margin:24px auto;padding:0 16px}
.card{background:#111827;border:1px solid #1f2937;border-radius:14px;padding:18px;margin-bottom:14px}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.span2{grid-column:span 2}
label{font-size:12px;color:#b6c2d9;margin-bottom:6px;display:block}
input,select{width:100%;padding:10px;border-radius:10px;border:1px solid #334155;background:#0b1220;color:#e6edf6}
.chk{display:block;margin-bottom:6px}
.btn{background:#2563eb;border:none;color:#fff;padding:10px 14px;border-radius:10px;cursor:pointer}
.kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.kpi{background:#0b1220;border:1px dashed #334155;border-radius:10px;padding:10px}
.small{font-size:12px;color:#9aa7bd}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>VBA ➜ PHP (Tablo Sürümü)</h2>
    <form method="post">
      <div class="grid">
        <div class="span2">
          <label>Memuriyet Başlangıç Öğrenim</label>
          <select name="mem_bas_idx">
            <?php foreach($EDU as $i=>$e): $sel = ($i==(int)($_POST['mem_bas_idx']??0))?'selected':''; ?>
              <option value="<?=$i?>" <?=$sel?>>[<?=$i?>] <?=$e['label']?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="span2">
          <label>Son Öğrenim</label>
          <select name="son_ogr_idx">
            <?php foreach($EDU as $i=>$e): $sel = ($i==(int)($_POST['son_ogr_idx']??0))?'selected':''; ?>
              <option value="<?=$i?>" <?=$sel?>>[<?=$i?>] <?=$e['label']?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div><label>Diploma Tarihi</label><input name="diploma_tarihi" placeholder="GG/AA/YYYY" required value="<?=htmlspecialchars($_POST['diploma_tarihi']??'')?>"></div>
        <div><label>İşlem Tarihi</label><input name="islem_tarihi" placeholder="GG/AA/YYYY" required value="<?=htmlspecialchars($_POST['islem_tarihi']??'')?>"></div>
        <div><label>Göreve Başlama</label><input name="gorev_bas" placeholder="GG/AA/YYYY" required value="<?=htmlspecialchars($_POST['gorev_bas']??'')?>"></div>
        <div><label>Askerlik Tarihi (ops.)</label><input name="askerlik_tarihi" placeholder="GG/AA/YYYY" value="<?=htmlspecialchars($_POST['askerlik_tarihi']??'')?>"></div>

        <div class="span2"><label>Ücretsiz İzin (Y/A/G)</label>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
            <input type="number" name="ucretsiz_yil" min="0" value="<?=htmlspecialchars($_POST['ucretsiz_yil']??'0')?>">
            <input type="number" name="ucretsiz_ay"  min="0" value="<?=htmlspecialchars($_POST['ucretsiz_ay']??'0')?>">
            <input type="number" name="ucretsiz_gun" min="0" value="<?=htmlspecialchars($_POST['ucretsiz_gun']??'0')?>">
          </div>
        </div>
        <div class="span2"><label>FHZS (Y/A/G)</label>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
            <input type="number" name="fhzs_yil" min="0" value="<?=htmlspecialchars($_POST['fhzs_yil']??'0')?>">
            <input type="number" name="fhzs_ay"  min="0" value="<?=htmlspecialchars($_POST['fhzs_ay']??'0')?>">
            <input type="number" name="fhzs_gun" min="0" value="<?=htmlspecialchars($_POST['fhzs_gun']??'0')?>">
          </div>
        </div>
        <div><label>FHZS Esas Gün</label><input type="number" name="fhzs_esas_gun" min="0" value="<?=htmlspecialchars($_POST['fhzs_esas_gun']??'0')?>"></div>
        <div><label>SGK Toplam Gün</label><input type="number" name="sigorta_gun" min="0" value="<?=htmlspecialchars($_POST['sigorta_gun']??'0')?>"></div>
        <div><label>Askerlikten Düş. Gün</label><input type="number" name="ask_dus_gun" min="0" value="<?=htmlspecialchars($_POST['ask_dus_gun']??'0')?>"></div>
        <div><label>Askerlik Toplam Gün (ops.)</label><input type="number" name="askerlik_toplam_gun" min="0" value="<?=htmlspecialchars($_POST['askerlik_toplam_gun']??'0')?>"></div>

        <div class="span2"><label>Özel Durum (+Derece/+Kademe)</label>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
            <input type="number" name="ozel_derece" min="0" value="<?=htmlspecialchars($_POST['ozel_derece']??'0')?>">
            <input type="number" name="ozel_kademe" min="0" value="<?=htmlspecialchars($_POST['ozel_kademe']??'0')?>">
            <div></div>
          </div>
        </div>
        <div class="span2"><label>Düşülecek (-Derece/-Kademe)</label>
          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
            <input type="number" name="dus_derece" min="0" value="<?=htmlspecialchars($_POST['dus_derece']??'0')?>">
            <input type="number" name="dus_kademe" min="0" value="<?=htmlspecialchars($_POST['dus_kademe']??'0')?>">
            <div></div>
          </div>
        </div>

        <div class="span2">
          <label>Ekstra Durumlar</label>
          <?php foreach($EXTRAS as $name=>$inc): $checked = isset($_POST['extra_'.$name])?'checked':''; ?>
            <label class="chk"><input type="checkbox" name="extra_<?=$name?>" <?=$checked?>> <?=$name?> (<?=($inc['derece']?('+'.$inc['derece'].' drc '):'')?><?=($inc['kademe']?('+'.$inc['kademe'].' kdm'):'')?>)</label>
          <?php endforeach; ?>
        </div>

        <div><label><input type="checkbox" name="uyg37" <?= isset($_POST['uyg37'])?'checked':''; ?>> 37. Madde Uygulansın</label></div>
      </div>
      <br><button class="btn" type="submit">Hesapla</button>
    </form>
  </div>

  <?php if($hatalar): ?>
    <div class="card" style="border-color:#ef4444"><b>Hata</b><pre><?=htmlspecialchars(implode("\n",$hatalar))?></pre></div>
  <?php endif; ?>

  <?php if($sonuc): ?>
    <div class="card">
      <h3>Sonuçlar</h3>
      <div class="kpis">
        <div class="kpi"><b>Mem.Baş.</b><br><?=htmlspecialchars($sonuc['mem'])?></div>
        <div class="kpi"><b>Son Öğrenim</b><br><?=htmlspecialchars($sonuc['son'])?></div>
        <div class="kpi"><b>37. md</b><br><?=htmlspecialchars($sonuc['uyg37'])?></div>
      </div><br>
      <div class="kpis">
        <div class="kpi"><b>Emsal</b><br><?=htmlspecialchars($sonuc['emsal'])?> (<?=htmlspecialchars($sonuc['emsalVar'])?>)</div>
        <div class="kpi"><b>Yeni Terfi</b><br><?=htmlspecialchars($sonuc['yeni_terfi'])?></div>
        <div class="kpi"><b>EKEA Yeni Terfi</b><br><?=htmlspecialchars($sonuc['ekea_yeni_terfi'])?></div>
      </div><br>
      <div class="kpis">
        <div class="kpi"><b>Hizmet</b><br><?=htmlspecialchars($sonuc['hizmet'])?></div>
        <div class="kpi"><b>EKEA</b><br><?=htmlspecialchars($sonuc['ekea'])?></div>
        <div class="kpi"><b>Emsalsiz</b><br><?=htmlspecialchars($sonuc['emsalsiz'])?></div>
      </div><br>
      <div class="kpis">
        <div class="kpi"><b>EKEA Emsalsiz</b><br><?=htmlspecialchars($sonuc['ekea_emsalsiz'])?></div>
        <div class="kpi"><b>Toplam</b><br><?=htmlspecialchars($sonuc['toplam'])?></div>
        <div class="kpi"><b>EKEA Toplam</b><br><?=htmlspecialchars($sonuc['ekea_toplam'])?></div>
      </div><br>
      <div class="kpis">
        <div class="kpi"><b>Derece/Kademe</b><br><?=htmlspecialchars($sonuc['dk'])?></div>
        <div class="kpi"><b>EKEA DK</b><br><?=htmlspecialchars($sonuc['ekea_dk'])?></div>
        <div class="kpi"><b>Ekstra (+kademe)</b><br><?=htmlspecialchars($sonuc['extraKademe'])?></div>
      </div>
    </div>
  <?php endif; ?>
</div>
</body></html>
