<?php
    include_once("./tools/req.php");
    include("./tools/config.php");
    include("./site/header.php");
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitenametitle . $sitenameprefix . "Главная"  ?></title>
    <link rel="stylesheet" href="/site/style.css">

</head>
<body>
    <?php
        include("uploader.php");
      //  include("news/news.php");
        include("./tools/stats.php");
        include("./tools/search.php");
        include("./site/footer.php");
        ?>

</body>
</html>