<?php
if($downloadtimer == 0) {

    session_start(); 			// запускаем сессию

    include ("./tools/capcha.php"); 	// вызываем модуль с нашей CAPTCHA


    if (!isset($_REQUEST['capcha']))		// если пользователь не ввел ответ, формируем и выдаем ему вопрос
    {
        GenerateCAPTCHA(4);
        echo "<p>Введите  символы : <br>&nbsp; &nbsp; &nbsp;".$_SESSION['mycaptcha1_text']."&nbsp; &nbsp; &nbsp; </p>";
        echo "<form method='POST' >";
        echo "<input type='text' name='capcha'/>";
        echo "&nbsp; <input type='submit' value='Отправить' />";
        echo "</form>";
    }
    else	// иначе проверяем правильность ответа пользователя и выдаем результат
    {
        if ($_REQUEST['capcha'] == $_SESSION['mycaptcha1_text'])
            echo "<center> <a title='".$imya_fayla."' alt='".$imya_fayla."' href=\"" .$scripturl. "download2.php?a=" . $filecrc . "&b=" . md5($foundfile[2].$_SERVER['REMOTE_ADDR']) . "\">Скачать</a> </center> ";
        else {
            GenerateCAPTCHA(4);
            echo "<p> Неверно! Введите символы <br> <br>&nbsp; &nbsp; &nbsp;".$_SESSION['mycaptcha1_text']."&nbsp; &nbsp; &nbsp;</p>";
            echo "<form method='POST' >";
            echo "<input type='text' name='capcha'/>";
            echo "&nbsp; <input type='submit' value='Отправить' />";
            echo "</form>";
        }
    }

} else  {   ?>

    JS не доступен
<?php } ?>