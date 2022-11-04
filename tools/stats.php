<?php


$papkafs = opendir('./storage/');
$papkafsfiles = 0;
$papkaSize = 0;
while ($filepapka = readdir($papkafs)) {
    if ($filepapka == '.' || $filepapka == '..' || is_dir('../storage/' . $filepapka)) {
        continue;
    }
    $papkafsfiles++;
}
$sizehosted = 0;

$handle = opendir("./storage/");
$pageSize = 999999999;
while ($file = readdir($handle)) {
    $sizehosted = $sizehosted + filesize("./storage/" . $file);
    if ((is_dir("./storage/" . $file . '/')) && ($file != '..') && ($file != '.')) {
        $sizehosted = $sizehosted + total_size("./storage/" . $file . '/');
    }
}
$sizehosted = round($sizehosted / 1024 / 1024, 2);


if ($sizehosted > 1024) {
    $filessize = " " . (round($sizehosted / 1024, 1)) . " ГБ.";
} else {
    $filessize = "" . $sizehosted . " МБ";
};


?>

<div class="stats">
    <span> Количество файлов на сервере: <a class="stats_link" href="/tools/files.php"> <?php echo $papkafsfiles ?> </a> </span>
    <span> Общии размер: <?php echo $filessize ?> </span>
</div>

