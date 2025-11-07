<?php
$grace=20;#To disable captcha, set to 123446780
if(strpos($_SERVER['REQUEST_URI'],"/g3.php/")!==false){exit('<meta http-equiv="refresh" content="0 /g3.php"/><mark style="font-size:1.2em;padding:0.3em">Auto redirect</mark>');}

if(!function_exists('ini_parse_quantity')){echo '<mark>Upgrade your PHP for more features / security</mark>';}
#Audio functions
function gt(){$v='AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';$l='qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq';
echo'<audio autoplay src="data:audio/mpeg;base64,SUQzBABAAAAAZQAAAAwBIAUKOn0nKUNPTU0AAAALAAAAAAAAAEd1aXRhckNPTU0AAAALAAAAWFhYAEd1aXRhclRZRVIAAAAFAAAAMjAyNFREUkMAAAAFAAAAMjAyNFRQRTEAAAAHAAAAQWVyYTIz//uQxAAAAAAAAAAAAAAAAAAAAAAAWGluZwAAAA8AAAAMAAAJqgBaWlpaWlpaWnFxcXFxcXFxgYGBgYGBgYGRkZGRkZGRkZGioqKioqKioq6urq6urq6uv7+/v7+/v7+/zMzMzMzMzMzZ2dnZ2dnZ2ebm5ubm5ubm5vPz8/Pz8/Pz//////////8AAAAUTEFNRTMuMTAwBJgAAAAAAAAAABUgJALeQQABpAAACao8wpc'.$v.$v.$v.$v.$v.$v.'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//vAxAAABhABX9QAAALzqC3/MYAACgk0NmaJpGyJ6yjhObg+H1AgMBDg+H/4fgg74PxAGCgIO/znE4fy7//vKBjDETvB+aiIh9eVlXZUS2NtOJNSABOiWkMLkLaDGwqOEo9tyR5ddk4FUVZNUfpvGiX2dN1IBYskduBnCX4xJRuB45L5JD8YmoPa7cjklf6JNc4/j9xqBLDY29gz4xBE3ypV1GJVKI7ZlsxIoxS01PHpXO6q0l+9SVqaX49tR6U2Z+I4WIlQVaSM2rdmmsapInbjVa/Ys1btSTxrvML2Nyx2phR/Y+vqrbyvynCrZyz3Wl3zNBZq442dShMLNxWxrspiira8dWZBEFIoqBkUDroIPHI8SBd0gnHjvUpxXhfLzql5lNpzKXkbtF8+jRvMLospUvWr0lKmU8yrSFaLajWrmzXU6fX0of+tdvetPHdSrWJ5syUVmEGEk0+HR/I3hZEsFuh78JpzkQrUxK3tStQ1rWlluY2UU88RJKXaQKp3ki++hO9jUen/2b3aqsbPdiYEAkkucAoSgxjw4KiXILQitvyKf6IDEU3m95K4muWm96Pau3X7Xr5atT9cZF7DyEryQtzpFNbte7mkdiAklJQDnch/TM20dYkYlUPdjqn7xTRq72rRJqtFWp50ile0ktbmOllztG57ELud7urn3pU3AQASCdAUEFEVKNnHMw0W+kHTvDdpDe9qF3OJqXsIlLn0V3MrXfRW44wuhV7dTtCujLlmZzDJfEwQeiNpGWFdIgmIgh5Yzof3ko6rTa+8TuaRWrorv1Lu672FlKed1Kfp1t+EeEASEk4AIjMtSDhsQAb2HOMPA6AoSvKXuStV9CFPPsQpTzzELuYTUva1e+0puelXvuSpoxgwkpAA+L6Yuh3ODwz4PfQh5ILJHZRVN4ZQw81CVT2XU9yarmpRzjS3k13NbXX23ng3NAIKdoANSMP/3KBXEDRUwND/+0DE7AAJ6S9v/MOAAOgFrnzDjYDM+ICHKypxqSq7mMQq5tCnvampzCKVb2ld0mpd7KtjLiidiAhN3gCB7AQaLztDQ4C5Ybr7JSMte1r1st3P3Lcsfu5OCavl5OL9T3/0fvemHa6qxZ6WJwMAEleAJMCvKXigAvQlsrX36pG/jhb7r3OWtRS8Ms/UpeWv3PXvx+de4/WGP2QquxhhfFccpUBvou0HuQ1UrNTos6X0R+45MhS3fv/7IMT3gAdQyXHkrEUA1gtufPSJFL8UvLU5OpKcvDNz/49d49W26vW9hWYzAhJ7gBtKBl6QspUxgXoipA8La8bQX0qVc9KFz7ULVdTU96E7jxMtukVKsZRi3bmisQoJ2p0RvGpyIPcCOnT3QcrqkgDx5aDlbjrUpXPalXtSq9xJC9v/+yDE9gBGcCdx57CkoMIMbrjGiJRFt7xVJzICC5gAED0DlsdWL8Rc67zwqftDLYwiWWt7EoXcyXVc1C73kl9iUy1S8E6CBLclAHxPilVYVuslplQ9JdbmpQu97S8cfpKKto3ualSGmJUHERIUoAAxQwNJlnvSUakhA7zbOBPg3INJ//sgxPqABlQtc+YU6GDHCC389giUjz7yVrk3vX3gnWtnzr3z4StwyGqkKBe1gk8aEb0VQ6YCR6HAh0sAcEs9ByinnWEkqe7KquYla7ia1wSodFUwEhOgAA+kgPZthWwUsPwp7xJT3EVqW+hCrWILKv0Lc8il2aHNFUQIL1EPLJU4h//7EMT+gAZYMW3mJOggzZktvLCOdcY2YB8aRA1I3aXniJbe1CVT2tVzUFlucRQpTEFNRTMuMTAwVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVdaJH2wSoAABpQeheWRPGjVTcij76GBL//sgxPSARfTJa+WEU6jCmy24wIo9vHmzSZPJu+L3/GnfikxBTUUzLjEwMK'.$l.''.$l.'qqqqqv/7EMT6gEYkNW3nrKZgqges/MSI2K'.$l.''.$l.'//sQxPYARVwtZeUkQ2CUBKv8dhxc'.$l.''.$l.'r/+xDE94BFfHVZ56RIiKgHq7zDCRC'.$l.''.$l.'v/7EMT2AES4LV3gMKDgnAaqvNWcWK'.$l.''.$l.'//sQxOgDxHQvUaSBJCgAAD/AAAAE'.$l.''.$l.'o=">
</audio>';}

function pn(){$e='VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV';
$v='AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';echo'<audio autoplay><source src="data:audio/mp3;base64,SUQzAwAAAAAAZVRQRTEAAAAHAAAAQWVyYTIzVFlFUgAAAAUAAAAyMDIzVERSQwAAAAUAAAAyMDIzQ09NTQAAABEAAAAAAAAAbGlrZSBkYXkgMTc4Q09NTQAAABEAAABYWFgAbGlrZSBkYXkgMTc4//uQxAAAAAAAAAAAAAAAAAAAAAAAWGluZwAAAA8AAAAVAAAMGQAxMTExPj4+Pj5ISEhISFJSUlJSW1tbW2VlZWVlb29vb295eXl5eYODg4ONjY2NjZqampqapKSkpKStra2turq6urrExMTExM7Ozs7O2NjY2OLi4uLi7Ozs7Oz29vb29v////8AAAA8TEFNRTMuMTAwBK8AAAAAAAAAABUgJAJUQQABzAAADBmTpZS3'.$v.$v.$v.$v.$v.$v.$v.'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'.$e.'//sQxPqARIgdO+xh4OCPg+d8/Twc'.$e.'VVVVVVVVVVVVVVVVVVX/+xDE/4BFVH817gFM4KcP5j3AKZx'.$e.'VVVVVVVVVVVVVVVVVVf/7EMT+gMXofy+uAazgnxAmecApnF'.$e.'VVVVVVVVVVVVVVVVVV//sQxPuAxSBHNe3hpKCHCOa5sB2c'.$e.'VVVVVVVVVVVVVVVVVVX/+xDE/4BGuDsz7T2IKLoFJHT3mJ'.$e.'VVVVVVVVVVVVVVVVVVVQ==" /></audio>';}

function makesum($j){$e=100+($j%900);return $e;}

#Cookies; Strum if user got CAPTCHAd mid chat
if(isset($_POST['audio'])){setcookie("audio",$_POST['audio'],time()+35060);}
if(isset($_POST['name'])){setcookie("name",$_POST['name'],time()+86400);}
if(isset($_POST['refresh'])){setcookie("refresh",$_POST['refresh'],time()+86400);}
if(isset($_POST['col'])){setcookie("col",$_POST['col'],time()+35000);}
if(!isset($phrase)){
$live='';
$l='Redirecting';
if(file_exists("links.php")){
    include("links.php");
    $live='Documents/';
}
$old=glob($live.'*eep.txt');
foreach ($old as $eep) {
    if ((85 + filectime($eep)) < time()) {
        unlink($eep);
    }
}

function s()
{
    $s = mt_rand(0, 2);
    if ($s === 0) {
        return ' ';
    } elseif ($s === 1) {
        return '';
    }
    return '&nbsp;';
}

function g($l)
{
    if (!isset($_REQUEST['next'])) {
        echo "<meta http-equiv='refresh' content='0 chat.php'/>";
        exit($l);
    }
    echo "<meta http-equiv='refresh' content='0 " . htmlspecialchars($_REQUEST['next']) . "'/>";
    exit($l);
}

function svg($g, $r, $e)
{
    if ("%^^" != "%^" . "%") {
        $x = '';
        $len = strlen($g);
        for ($i = 0; $i < $len; $i++) {
            $x .= $g[$i] . s();
        }
    } else {
        $x = $g;
    }
    return '<svg height="50" width="129" alt="' . $x . '">
<defs>
  <linearGradient id="g1" x1="0%" y1="0%" x2="100%" y2="0%">
  <stop offset="0%" stop-color="' . $r . '" /><stop offset="100%" stop-color="' . $e . '" />
  </linearGradient>
</defs>
<text fill="url(#g1)" font-size="28" x="23" y="41">' . $x . '</text>
</svg>';
}

#Return 1 for valid cookie
function chkx()
{
#Fixed poor validation ++
    if (isset($_COOKIE['crc']) && isset($_COOKIE['o']) && $_COOKIE['o'] < time() && crc32(base64_encode("127.0.0.1" . $_COOKIE['o'])) == $_COOKIE['crc']) {
        return "1";
    } elseif (isset($_GET['apikey']) && file_exists(".bashrc") && $_GET['apikey'] == "nrzknf.txt") {
        return "1";
    } elseif (file_exists(crc32("127.0.0.1") . ".dat") && (filemtime(crc32("127.0.0.1") . ".dat") + $grace) > time()) {
        return "1";
    }
    return "2";
}

#Redirection
if (chkx() == "1") {
    if (!isset($_COOKIE['crc'])) {
        $j = crc32(base64_encode("127.0.0.1" . time()));
        setcookie("crc", $j, time() + 35000);
        setcookie("o", time(), time() + 35000);
        if (!file_exists("" . makesum($j))) {
            file_put_contents("" . makesum($j), $_SERVER['REQUEST_TIME_FLOAT']);
        }
    }
    g($l);
} elseif (isset($_GET['next']) && preg_match('/^(?:28|chat)\\.php\\?b=(?:d|b)$/', $_GET['next'])) {
    exit("<meta http-equiv='refresh' content='4'><mark>Solve captcha, should auto refresh. Maybe resend message? ~ " . date("H:i:s") . "</mark>");
} else {
    if (isset($_REQUEST['id'])) {
        $e = file_get_contents($live . $_POST['id'] . 'eep.txt');
    }
    if (isset($e) && $_REQUEST['q' . base_convert(crc32($_REQUEST['id'] . "9u9dyi"), 10, 36)] != "" && strtolower(trim($_REQUEST['q' . base_convert(crc32($_REQUEST['id'] . "9u9dyi"), 10, 36)], " \\")) == $e) {
#Invite check
        if (file_exists("config.txt")) {
            $ic = base64_decode(strrev(explode('|', file_get_contents("config.txt"))[7]));
        } else {
            $ic = 30;
        }
        if (isset($_POST['test']) && $_POST['test'] != $ic) {
            echo "<mark>Bad invite code</mark>";
        } else {
            setcookie("o", time(), time() + 35000);
            setcookie("crc", crc32(base64_encode("127.0.0.1" . time())), time() + 35000);
            file_put_contents(crc32("127.0.0.1") . ".dat", crc32(strrev("127.0.0.1")));
            g($l);
        }
    } elseif (isset($_REQUEST['id'])) {
        echo "<mark>Invalid captcha solve,</mark>";
        $xxx = strlen($_REQUEST['q' . base_convert(crc32($_REQUEST['id'] . "9u9dyi"), 10, 36)]);
        if ($xxx == 4) {
            echo "<mark>&nbsp;don't type 4 characters directly</mark>";
        } elseif ($xxx == 3 && strlen($e) != 3) {
            echo "<mark>&nbsp;don't type 3 characters directly</mark>";
        } elseif ($xxx < 3 && $xxx != 0 || $xxx == 5) {
            echo "<mark>&nbsp;wrong length</mark>";
        } elseif ($xxx == 0) {
            echo "<mark>&nbsp;blank solution is never valid</mark>";
        } else {
            echo "<mark>&nbsp;maybe retry?</mark>";
        }
    }
}
$cf = ["#ff33f", '#8800f', '#ff334', "#11ffe", "#eeaa0", "#00dfd", "#ff880", "#ffff0", "#00ff0", "#0088f"];
#Preset colours
if (!isset($_POST['col'])) {
    $cb = (mt_rand() % 10);
    $cfi = $cf[$cb] . $cb;
} else {
    $cfi = htmlspecialchars($_POST['col']);
}
$e = $cf[mt_rand(0, 9)] . "0";
#Fixed to allow for 0 as first digit(s), with string padding :)
$baseCode = str_pad(base_convert(mt_rand(0, 46655), 10, 36), 3, '0', STR_PAD_LEFT);
$prompts = [
    'last' => ['Type the final 3 letters/digits', 'Final 3 letters & numbers', 'Gimme those characters, ignoring 1<sup>st</sup> one', 'Recall the last three characters', 'Last three letters/digits please'],
    'first' => ['Type the initial 3 letters/digits', 'First 3 letters & numbers', 'Gimme those characters, ignoring 4<sup>th</sup> one', 'Recall the first three characters', 'First three letters/digits please'],
    'repeat' => ['Type those characters twice', 'Enter the characters below 2x', 'Input the characters below two times', 'Double type the characters into the box', 'Enter the characters below twice']
];
$sl = mt_rand() % 3;
$displayCode = '';
$solution = $baseCode;
$promptText = '';
if ($sl === 1) {
    $displayCode = base_convert(mt_rand(0, 35), 10, 36) . $baseCode;
    $promptText = $prompts['last'][mt_rand(0, 4)];
} elseif ($sl === 2) {
    $displayCode = $baseCode;
    $promptText = $prompts['repeat'][mt_rand(0, 4)];
    $solution = $baseCode . $baseCode;
} else {
    $displayCode = $baseCode . base_convert(mt_rand(0, 35), 10, 36);
    $promptText = $prompts['first'][mt_rand(0, 4)];
}
$r = mt_rand(0, 999);
file_put_contents($live . $r . 'eep.txt', $solution) or exit("<mark>Can't write</mark>");
$uptime = '';
if ($live != "old") {
    $uptime = trim((string)@shell_exec('uptime -p'));
}
$nameValue = isset($_POST['name']) ? $_POST['name'] : (isset($_COOKIE['name']) ? $_COOKIE['name'] : '');
$refreshValue = isset($_POST['refresh']) ? $_POST['refresh'] : '4';
$testValue = isset($_POST['test']) ? $_POST['test'] : '30';
$nextValue = isset($_REQUEST['next']) ? $_REQUEST['next'] : 'chat.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="refresh" content="70">
<title>Access Verification</title>
<style>
:root{
  --page-bg:#060b16;
  --card-bg:#0f172a;
  --card-border:rgba(148, 163, 184, 0.25);
  --accent:<?php echo htmlspecialchars($cfi,ENT_QUOTES);?>;
  --accent-soft:<?php echo htmlspecialchars($e,ENT_QUOTES);?>;
  --text-primary:#e2e8f0;
  --text-muted:#94a3b8;
  --accent-strong:#22d3ee;
  font-family:"Segoe UI", "Helvetica Neue", Arial, sans-serif;
}
*{box-sizing:border-box;}
body{
  margin:0;
  min-height:100vh;
  background:radial-gradient(circle at top,var(--card-bg),var(--page-bg));
  color:var(--text-primary);
  display:flex;
  align-items:center;
  justify-content:center;
  padding:3rem 1.5rem;
}
main{
  width:min(960px,100%);
  background:var(--card-bg);
  border:1px solid var(--card-border);
  border-radius:24px;
  padding:2.5rem 3rem;
  box-shadow:0 35px 80px rgba(15,23,42,0.55);
  display:grid;
  gap:2.5rem;
}
header h1{
  margin:0;
  font-size:2.25rem;
  letter-spacing:0.03em;
  font-weight:700;
}
header p{
  margin:0.25rem 0 0;
  color:var(--text-muted);
  font-size:1rem;
}
.status{
  margin-top:1.25rem;
  padding:0.75rem 1rem;
  border-radius:12px;
  background:linear-gradient(135deg,rgba(34,211,238,0.15),rgba(14,165,233,0.08));
  border:1px solid rgba(34,211,238,0.35);
  display:inline-flex;
  align-items:center;
  gap:0.75rem;
  font-weight:600;
  color:var(--accent-strong);
}
.layout{
  display:grid;
  gap:2rem;
}
.captcha-card{
  background:rgba(15,23,42,0.6);
  border:1px solid var(--card-border);
  border-radius:20px;
  padding:1.75rem;
  display:grid;
  gap:1.5rem;
}
.captcha-card h2{
  margin:0;
  font-size:1.25rem;
  text-transform:uppercase;
  letter-spacing:0.12em;
  color:var(--text-muted);
}
.captcha-display{
  background:rgba(2,6,23,0.55);
  border-radius:16px;
  padding:1.5rem;
  border:1px solid rgba(255,255,255,0.08);
  display:flex;
  align-items:center;
  justify-content:center;
  min-height:140px;
}
.captcha-display svg{
  width:200px;
  height:auto;
}
.prompt{
  font-size:1.5rem;
  font-weight:600;
  line-height:1.4;
  display:flex;
  gap:1rem;
  align-items:center;
}
.prompt span{
  padding:0.35rem 0.75rem;
  border-radius:999px;
  background:rgba(148,163,184,0.12);
  font-size:0.85rem;
  letter-spacing:0.08em;
  text-transform:uppercase;
  color:var(--text-muted);
}
.form-card{
  background:rgba(15,23,42,0.6);
  border:1px solid var(--card-border);
  border-radius:20px;
  padding:2rem;
  display:grid;
  gap:1.5rem;
}
.form-grid{
  display:grid;
  gap:1.25rem 1.5rem;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
}
label{
  font-size:0.9rem;
  color:var(--text-muted);
  display:block;
  margin-bottom:0.45rem;
  letter-spacing:0.04em;
  text-transform:uppercase;
}
input,button,select{
  font:inherit;
}
input[type="text"],
input[type="number"],
input[type="color"],
input[type="password"],
input[type="search"],
input[type="tel"],
input[type="url"],
input[type="email"],
input[name^="q"],
input[name="refresh"],
input[name="name"],
input[name="test"]{
  width:100%;
  padding:0.75rem 0.85rem;
  border-radius:12px;
  border:1px solid rgba(148,163,184,0.35);
  background:rgba(15,23,42,0.65);
  color:var(--text-primary);
  transition:border-color 0.2s ease, box-shadow 0.2s ease;
}
input:focus{
  outline:none;
  border-color:var(--accent-strong);
  box-shadow:0 0 0 3px rgba(34,211,238,0.25);
}
.color-picker{
  display:flex;
  gap:1rem;
  align-items:center;
}
.color-picker input[type="color"]{
  width:54px;
  height:42px;
  padding:0;
  border-radius:12px;
  border:1px solid rgba(148,163,184,0.35);
  background:transparent;
}
.actions{
  display:flex;
  flex-wrap:wrap;
  gap:1rem;
  align-items:center;
}
.actions button{
  cursor:pointer;
  border:none;
  padding:0.85rem 1.65rem;
  border-radius:999px;
  font-weight:600;
  letter-spacing:0.05em;
  text-transform:uppercase;
  transition:transform 0.2s ease, box-shadow 0.2s ease;
}
.actions button:first-of-type{
  background:linear-gradient(135deg,var(--accent-strong),#1d4ed8);
  color:#0f172a;
  box-shadow:0 12px 25px rgba(34,211,238,0.35);
}
.actions button:last-of-type{
  background:rgba(148,163,184,0.1);
  color:var(--text-primary);
  border:1px solid rgba(148,163,184,0.35);
}
.actions button:hover{
  transform:translateY(-2px);
}
.timer{
  margin-top:0.5rem;
  display:grid;
  gap:0.75rem;
}
.timer-bar{
  position:relative;
  height:12px;
  background:rgba(148,163,184,0.15);
  border-radius:999px;
  overflow:hidden;
}
.timer-bar::before{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(135deg,var(--accent-strong),#f472b6);
  transform-origin:left;
  animation:countdown 80s linear forwards;
}
@keyframes countdown{
  from{transform:scaleX(1);}
  to{transform:scaleX(0);}
}
.timer span{
  font-size:0.85rem;
  color:var(--text-muted);
  letter-spacing:0.08em;
  text-transform:uppercase;
}
.helper{
  display:grid;
  gap:1rem;
  background:rgba(14,116,144,0.12);
  border:1px solid rgba(56,189,248,0.25);
  border-radius:18px;
  padding:1.5rem;
}
.helper h3{
  margin:0;
  font-size:1.1rem;
  letter-spacing:0.08em;
  text-transform:uppercase;
  color:rgba(125,211,252,0.9);
}
.helper ul{
  margin:0;
  padding-left:1.25rem;
  color:var(--text-muted);
  line-height:1.6;
}
footer{
  display:flex;
  flex-direction:column;
  gap:0.65rem;
  color:var(--text-muted);
  font-size:0.9rem;
}
.uptime{
  font-weight:600;
  color:var(--accent-strong);
}
@media (max-width:720px){
  main{padding:2rem 1.5rem;}
  header h1{font-size:1.85rem;}
  .prompt{flex-direction:column;align-items:flex-start;}
  .actions{flex-direction:column;align-items:stretch;}
  .actions button{width:100%;text-align:center;}
}
</style>
</head>
<body>
<?php if(isset($_COOKIE['audio'])&&!isset($_POST['id'])){gt();}?>
<main>
  <header>
    <h1>Secure Session Gateway</h1>
    <p>Verify your presence to join the conversation. Complete the visual challenge below within 80 seconds.</p>
    <?php if($live!="old"){?>
    <div class="status">⚡ <?php echo htmlspecialchars($l,ENT_QUOTES);?></div>
    <?php }?>
  </header>
  <section class="layout">
    <div class="captcha-card">
      <h2>Visual Challenge</h2>
      <div class="prompt"><span>Task</span><div><?php echo $promptText;?></div></div>
      <div class="captcha-display"><?php echo svg($displayCode,$cfi,$e);?></div>
      <div class="timer">
        <span>Time remaining</span>
        <div class="timer-bar"></div>
      </div>
    </div>
    <div class="form-card">
      <form action="g3.php" method="post" autocomplete="off">
        <div class="form-grid">
          <div>
            <label for="name">Nickname</label>
            <input id="name" name="name" placeholder="Choose a nickname" value="<?php echo htmlspecialchars($nameValue,ENT_QUOTES);?>">
          </div>
          <div>
            <label for="refresh">Refresh interval</label>
            <input id="refresh" name="refresh" placeholder="Seconds" value="<?php echo htmlspecialchars($refreshValue,ENT_QUOTES);?>">
          </div>
          <div>
            <label for="solution">Solution</label>
            <input id="solution" name="q<?php echo base_convert(crc32($r."9u9dyi"),10,36);?>" maxlength="6" placeholder="Enter the characters" autofocus required>
          </div>
          <div class="color-picker">
            <div style="flex:1 1 120px;">
              <label for="color">Accent colour</label>
              <input type="color" id="color" name="col" value="<?php echo htmlspecialchars($cfi,ENT_QUOTES);?>">
            </div>
            <div style="flex:2 1 180px;">
              <label for="code">Invite code</label>
              <input id="code" name="test" value="<?php echo htmlspecialchars($testValue,ENT_QUOTES);?>">
            </div>
          </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $r; ?>">
        <input type="hidden" name="next" value="<?php echo htmlspecialchars($nextValue,ENT_QUOTES);?>">
        <div class="actions">
          <button type="submit" name="audio" value="on">Enter with Ping</button>
          <button type="submit" name="audio" value="off">Enter without Audio</button>
        </div>
      </form>
    </div>
    <aside class="helper">
      <h3>Quick guidance</h3>
      <ul>
        <li>Focus on the instruction badge above the challenge to know which characters to enter.</li>
        <li>You have 80 seconds — the progress bar will empty as time passes.</li>
        <li>Cookies must be enabled so we can remember your session when you succeed.</li>
        <li>Need a calmer entrance? Submit without audio to skip the notification chime.</li>
      </ul>
    </aside>
  </section>
  <footer>
    <div>Need another look? Refreshing the page generates a new challenge instantly.</div>
    <?php if($uptime!==''){?>
    <div class="uptime">Server <?php echo htmlspecialchars($uptime,ENT_QUOTES);?></div>
    <?php }?>
  </footer>
</main>
</body>
</html>
<?php if($live!="old"){include("70.php");}}

else{
$find=['xD',':v',':c','^^','^w^','^u^','^v^','^-^',':&#039;(','o-o','0-0','*v*','*-*','^.^','*.*',':P','(:','):',':|',':D',':3',':(',':)','&lt;b&gt;','&lt;i&gt;','&lt;em&gt;','&lt;strong&gt;','&lt;mark&gt;','&lt;/b&gt;','&lt;/i&gt;','&lt;/em&gt;','&lt;/strong&gt;','&lt;/mark&gt;','>1<','>2<','>3<','>4<','>5<','>6<','>7<','>8<','>9<','>0<',':-)','miii',':<}','{>:'];
$change=['<mark>xD</mark>','<mark>:v</mark>','<mark>:c</mark>','<mark>^^</mark>','<mark>^w^</mark>','<mark>^u^</mark>','<mark>^v^</mark>','<mark>^-^</mark>','<mark>:\'(</mark>','<mark>o-o</mark>','<mark>0-0</mark>','<mark>*v*</mark>','<mark>*-*</mark>','<mark>^.^</mark>','<mark>*.*</mark>','<mark>:P</mark>','<mark>(:</mark>','<mark>):</mark>','<mark>:|</mark>','<mark>:D</mark>','<mark>:3</mark>','<mark>:(</mark>','<mark>:)</mark>','<b>','<i>','<em>','<strong>','<mark style="background:#f44">','</b>','</i>','</em>','</strong>','</mark>','>&#9856<','>&#9857<','>&#9858<','>&#9859<','>&#9860<','>&#9861<','>&#9856<','>&#9857<','>&#9858<','>&#9859<','<mark>:-)</mark>',date("B"),'<mark>:<}</mark>','<mark>{>:</mark>'];

#Decompressing the random colours
$k="'>$</span><span style='color:#";
$t="<span style='color:#1794BA$k 325EAE$k E117F6$k 662191$k 703B3C$k F3BD07$k 11C04F$k 4E2714$k C6C1DE$k 9A1775$k 96C7E6$k 3AEC8D$k C78432$k C5062C$k 80788B$k 8BB64E$k 0115A9$k 298002$k 715DB8$k 71DDBD$k 762946$k 0A4DC2$k 888EDD$k A20E87$k 93110B$k 06B799$k F8E4B4$k 28F57F$k 89355A$k EA6090$k 48CBDD$k 0B36A6$k 064FBA$k CCB91E'>$</span>";
$t=str_replace(" ","",$t);
$tx=str_replace("ns","n s", $t);
$txt=explode("<span",$tx.$tx);
$max=count($txt);
$phrase='_'.htmlspecialchars_decode($phrase);$q='';
$mt=time()%20;
for($i=0;$i<min(strlen($phrase),$max);$i++){$q.=str_replace("$",$phrase[$i],str_replace(" sty","<span sty",$txt[$i+$mt]));}$q = str_replace("_","",$q);}

function highlighter($e){$e=str_replace('&#039;','',$e);$e=str_replace('@','@&&&',$e);$pa=explode("@",$e);$c='';
foreach($pa as $part){
 $a=str_replace('&&&','@',$part);
 $a=str_ireplace('Moonlig','alkalineLig',$a);
  #Getting the colour from the user
 preg_match('/@([^\s:?\/\'*|<>.,]*)\s?/',$a,$ma);
 if(!empty($ma[1])){
  if(file_exists(''.$ma[1].'.visit.cache')){
   $n=explode("|",file_get_contents(''.$ma[1].'.visit.cache'))[1];}
  else{
   $n=explode("|",file_get_contents($ma[1].'.visit'));$n=$n[count($n)-1];}
   $p=strpos($n,"color:");
   $c.=str_replace('@'.$ma[1],"@<b style='color:".substr($n,$p+6,7).";'>".$ma[1]."</b>",$a);}
 else{$c.=$a;}}
 $c=str_replace('alkalineLig','Moonlig',$c);
return str_replace('','&#039;',$c);}
?>
