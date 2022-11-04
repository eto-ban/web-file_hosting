<?php
include("./tools/config.php");
?>
<link rel="stylesheet" href="/site/style.css">
<?php
$headertitle = 'Скачать файл';
include("./site/header.php");

$bans=file("./database/bans.bd");

foreach($bans as $line)
{
  if ($line==$_SERVER['REMOTE_ADDR']){
    echo "Ваш пк в черном списке";
    include("./site/footer.php");
    die();
  }
}

if(isset($_GET['file'])) {
  $filecrc = $_GET['file'];
} else {
  echo "<span class='upl_stasus'>Неверная ссылка на скачивание файла.</span>";
  include("./site/footer.php");
  die();
}

$checkfiles=file("./database/files.bd");
$foundfile=0;
foreach($checkfiles as $line)
{
  $thisline = explode('|', $line);
  if ($thisline[0]==$filecrc){
    $foundfile=$thisline;
  }
}

if(isset($_GET['del'])) {

$fc=file("./database/files.bd");
$f=fopen("./database/files.bd","w");
$deleted=0;
foreach($fc as $line)
{
  $thisline = explode('|', $line);
  if ($thisline[0] == $_GET['file']){
    if($thisline[2] == $_GET['del']){
	$deleted=1;
    } else {
    fputs($f,$line);
    }
  } else {
    fputs($f,$line);
  }
}
fclose($f);
if($deleted==1){
unlink("./storage/".$_GET['file']);
echo "<span class='upl_stasus'>Файл был успешно удалён.</span>";
} else {
echo "<span class='upl_stasus'>Неверная ссылка на удаление файла</span>";
}
include("./site/footer.php");
die();

}

if($foundfile==0) {
  echo "<span class='upl_stasus'>Неверная ссылка на скачивание файла.</span>";
  include("./site/footer.php");
  die();
}

if(isset($foundfile[7]) && $foundfile[7]!=md5("") && (!isset($_POST['pass']) || $foundfile[7] != md5($_POST['pass']))){
echo "<form action=\"download.php?file=".$foundfile[0]."\" method=\"post\">Введите пароль для скачивания данного файла: <input type=\"password\" name=\"pass\"><input type=\"submit\" value=\"Скачать файл\" /></form>";
include("./site/footer.php");
die();
}

$filesize = filesize("./storage/".$foundfile[0]);
$filesize = $filesize / 1048576;

if($filesize > $nolimitsize) {

$userip=$_SERVER['REMOTE_ADDR'];
$time=time();
$downloaders = fopen("./database/downloaders.bd","r+");
flock($downloaders,2);
while (!feof($downloaders)) { 
$user[] = chop(fgets($downloaders,65536));
}
fseek($downloaders,0,SEEK_SET);
ftruncate($downloaders,0);
foreach ($user as $line) {
@list($savedip,$savedtime) = explode("|",$line);
if ($savedip == $userip) {
if ($time < $savedtime + ($downloadtimelimit*60)) {
echo "Вы слишком спешите! Подождите еще немного и попробуйте скачать файл еще раз.";
include("./site/footer.php");
die();
}
}
if ($time < $savedtime + ($downloadtimelimit*60)) {
  fputs($downloaders,"$savedip|$savedtime\n");
}
}

}
  if (($filesize < 1) && ($filesize > 0.001))
    {
     echo "<div class='dwnll'><table class='dwnll'> 
              <tr> 
                <td>Имя фаила</td>
                <td>". $foundfile[1] ."</td>
              </tr>
              <tr> 
                <td>Вес файла</td>
                <td>".round($filesize*1024,0)."КБ </td>
              </tr>
              <tr> 
                <td>Загруженно</td>
                <td>". $foundfile[5] ." раз(а)</td>
              </tr>
              <tr> 
                <td>Описание</td>
                <td>". $foundfile[6] ."</td>
              </tr>
            </table></div>
";
    }
  elseif (($filesize < 1) && ($filesize < 0.001))
    {
        echo "<div class='dwnll'><table class='dwnll'> 
              <tr> 
                <td>Имя фаила</td>
                <td>". $foundfile[1] ."</td>
              </tr>
              <tr> 
                <td>Вес файла</td>
                <td>".round($filesize*1024*1024,0)." байт </td>
              </tr>
              <tr> 
                <td>Загруженно</td>
                <td>". $foundfile[5] ." раз(а)</td>
              </tr>
              <tr> 
                <td>Описание</td>
                <td>". $foundfile[6] ."</td>
              </tr>
            </table></div>
";
    }	
 elseif ($filesize > 1024) {
     echo "<div class='dwnll'><table class='dwnll'> 
              <tr> 
                <td>Имя фаила</td>
                <td>". $foundfile[1] ."</td>
              </tr>
              <tr> 
                <td>Вес файла</td>
                <td>".round($filesize/1024,0)." ГБ </td>
              </tr>
              <tr> 
                <td>Загруженно</td>
                <td>". $foundfile[5] ." раз(а)</td>
              </tr>
              <tr> 
                <td>Описание</td>
                <td>". $foundfile[6] ."</td>
              </tr>
            </table></div>
";
	} 
  else
    {
        echo "<div class='dwnll'><table> 
              <tr> 
                <td>Имя фаила</td>
                <td>". $foundfile[1] ."</td>
              </tr>
              <tr> 
                <td>Вес файла</td>
                <td>".round($filesize,2)." МБ</td>
              </tr>
              <tr> 
                <td>Загруженно</td>
                <td>". $foundfile[5] ." раз(а)</td>
              </tr>
              <tr> 
                <td>Описание</td>
                <td>". $foundfile[6] ."</td>
              </tr>
            </table></div>
";


    }	

if(isset($foundfile[6])){ echo "<td colspan=6> </td> </tr>"; }
else { echo "<td colspan=6> </td> </tr>"; }
$randcounter = rand(100,999);
?>
<tr> <td colspan=14 valign="top">
<?php $imya_fayla=htmlspecialchars($foundfile[1], ENT_QUOTES); ?>

<?php
    echo "<div class='dwnf_b'><a class='dwnf_link' title='".$imya_fayla."' alt='".$imya_fayla."' href=\"" .$scripturl. "download2.php?a=" . $filecrc . "&b=" . md5($foundfile[2].$_SERVER['REMOTE_ADDR']) . "\">Скачать</a></div> ";

    // include "capchainc.php"; // Капчв
?>

<script language="Javascript">
x<?php echo $randcounter; ?>=<?php echo $downloadtimer; ?>;
function countdown() 
{
 if ((0 <= 100) || (0 > 0))
 {
  x<?php echo $randcounter; ?>--;
  if(x<?php echo $randcounter; ?> == 0)
  {
   document.getElementById("dl").innerHTML = ' <a href="<?php echo $scripturl . "download2.php?a=" . $filecrc . "&b=" . md5($foundfile[2].$_SERVER['REMOTE_ADDR']) ?>">Скачать</a> </td> </tr>';
  }
  if(x<?php echo $randcounter; ?> > 0)
  {
   document.getElementById("dl").innerHTML = 'Осталось ждать <b>'+x<?php echo $randcounter; ?>+'</b> секунд.. </td> </tr>';
   setTimeout('countdown()',1000);
  }
 }
}
countdown();
</script>
</td> </tr>
<title><?php echo $sitenametitle . $sitenameprefix . "Загрузка файла - " . $foundfile[1]; ?></title>
<?php
// include("./tools/preview.php");
include("./tools/search.php");
include("./site/footer.php");

?>

