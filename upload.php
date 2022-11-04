<?php
include("./tools/config.php");
include("./tools/translit.php");
include("./tools/req.php");
include("./site/header.php");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitenametitle . $sitenameprefix . "Загрузка файла"  ?></title>
    <link rel="stylesheet" href="/site/style.css">
</head>
<body>
<script>
    function copytext(el) {
        var $tmp = $("<textarea>");
        $("body").append($tmp);
        $tmp.val($(el).text()).select();
        document.execCommand("copy");
        $tmp.remove();
    }
</script>

<?php
    $filename = $_FILES['file1']['name'];
    $filesize = $_FILES['file1']['size'];
    $filecrc = md5_file($_FILES['file1']['tmp_name']);
$bans=file("./database/bans.bd");
foreach($bans as $line)
{
    if ($line==$filecrc."\n"){
        echo "Загрузка файла запрещена";
        include("./site/footer.php");
        die();
    }
    if ($line==$_SERVER['REMOTE_ADDR']."\n"){
        echo "Загрузка файлов с вашего ПК запрещена.";
        include("./site/footer.php");
        die();
    }
}

$checkfiles=file("./database/files.bd");
foreach($checkfiles as $line)
{
    $thisline = explode('|', $line);
    if ($thisline[0]==$filecrc){
        echo "<span class='upl_stasus'>Данный файл уже был загружен</span>";
        echo "<div class='upd_link'><table><tr><td><span class='upl_dwnlink'>Ссылка на скачивание файла:</td> <td> <a id='text1' class='upd_link' href=\"" . $scripturl . "download.php?file=" . $filecrc . "\">". $scripturl . "download.php?file=" . $filecrc . "</a> </td><td></span> <button onclick=\"copytext('#text1')\">Скопировать</button></td></tr>";
        echo "<tr><td><span class='upd_dellink'> Данный файл уже был загружен другим пользователем, вы не можете его удалить</span></td></tr></table></div>";
        include("./site/footer.php");
        die();
    }
}


if(isset($allowedtypes)){
    $allowed = 0;
    foreach($allowedtypes as $ext) {
        if(substr($filename, (0 - (strlen($ext)+1) )) == ".".$ext)
            $allowed = 1;
    }
    if($allowed==0) {
        echo "<span class='upl_stasus'> Файлы этого формата запрещены для загрузки на сайт.</span>";
        include("./site/footer.php");
        die();
    }
}


if(isset($categorylist)){
    $validcat = 0;
    foreach($categories as $cat) {
        if($_POST['category']==$cat){ $validcat = 1; }
    }
    if($validcat==0) {
        echo "<span class='upl_stasus'> Выбрана неправильная категория !</span> ";
        include("./site/footer.php");
        die();
    }
    $cat = $_POST['categories'];
}



if($filesize==0) {
    echo "<span class='upl_stasus'>Вы не выбрали ни один файл для загрузки.</span>";
    include("./site/footer.php");
    die();
}

$filesize = $filesize / 1048576;

if($filesize > $maxfilesize) {
    echo "<span class='upl_stasus'> Вы пытаетесь загрузить слишком большой файл.</span>";
    include("./site/footer.php");
    die();
}

$userip = $_SERVER['REMOTE_ADDR'];
$time = time();

if($filesize > $nolimitsize) {

    $uploaders = fopen("./database/uploaders.bd","r+");
    flock($uploaders,2);
    while (!feof($uploaders)) {
        $user[] = chop(fgets($uploaders,65536));
    }
    fseek($uploaders,0,SEEK_SET);
    ftruncate($uploaders,0);
    foreach ($user as $line) {
        @list($savedip,$savedtime) = explode("|",$line);
        if ($savedip == $userip) {
            if ($time < $savedtime + ($uploadtimelimit*60)) {
                echo "<span class='upl_stasus'>Вы слишком торопитесь. Подождите немного и попробуйте еще раз!</span>";
                include("./site/footer.php");
                die();
            }
        }
        if ($time < $savedtime + ($uploadtimelimit*60)) {
            fputs($uploaders,"$savedip|$savedtime\n");
        }
    }
    fputs($uploaders,"$userip|$time\n");

}

$passkey = rand(100000, 999999);

if($emailoption && isset($_POST['myemail']) && $_POST['myemail']!="") {
    $uploadmsg = "Загрузка вашего файла (".$filename.") завершена.\n Ссылка на скачивание файла: ". $scripturl . "download.php?file=" . $filecrc . "\n Ссылка для удаления файла: ". $scripturl . "download.php?file=" . $filecrc . "&del=" . $passkey . "\n Благодарим за использование нашего файлообменника!";
    mail($_POST['myemail'],"",$uploadmsg,"\n");
}

if($passwordoption && isset($_POST['pprotect'])) {
    $passwerd = md5($_POST['pprotect']);
} else { $passwerd = md5(""); }

if($descriptionoption && isset($_POST['descr'])) {
    $description = strip_tags($_POST['descr']);
} else { $description = ""; }

$filelist = fopen("./database/files.bd","a+");
/*Добавляем транслитерацию имени*/
$imya_translitom = translit($_FILES['file1']['name']);
/*Добавляем транслитерацию имени - Конец*/
fwrite($filelist, $filecrc ."|". basename($imya_translitom) ."|". $passkey ."|". $userip ."|". $time."|0|".$description."|".$passwerd."|\n");
/* fwrite($filelist, $filecrc ."|". basename($_FILES['upfile']['name']) ."|". $passkey ."|". $userip ."|". $time."|0|".$description."|".$passwerd."|".$cat."|\n"); */

$movefile = "./storage/" . $filecrc;
move_uploaded_file($_FILES['file1']['tmp_name'], $movefile);

echo "<span class='upl_stasus'>Файл успешно загружен</span>";
echo "<div class='upd_link'><table><tr><td>Ссылка на скачивание файла: </td> <td> <a class='upd_link' id='text1' href=\"" . $scripturl . "download.php?file=" . $filecrc . "\">". $scripturl . "download.php?file=" . $filecrc . "</a> </td><td></span> <button onclick=\"copytext('#text1')\">Скопировать</button></td></tr>";
echo "<tr><td>Ссылка для удаления файла: </td> <td> <a class='upd_link' id='text2' href=\"" . $scripturl . "download.php?file=" . $filecrc . "&del=" . $passkey . "\">". $scripturl . "download.php?file=" . $filecrc . "&del=" . $passkey . "</a> </td><td> <button onclick=\"copytext('#text2')\">Скопировать</button></td></tr></table></div>";
echo "";
?>
</body>
</html>