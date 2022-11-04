<hr>
<div class="search">
<div class="search_categore">
    <span class="search_categore-title">Файлы из категории:</span>
    <form action="/tools/files.php" method="GET">
        <select class="search_categore-select" name="cat">
            <option value="arc">Архивы
            <option value="docs">Документы
            <option value="mus">Музыка
            <option value="img">Диски
            <option value="prg">Программы
            <option value="pic">Изоброжение
            <option value="txt">Тексты
            <option value="web">Web-формат
            <option value="other">Другое
        </select>
    <input class="search_categore-btn" type=submit value="Выбрать">
    </form>
</div>

    <div class="search_input">
        <span class="search_input-title">Поиск файлов</span>
        <form name="keyword1" action="/tools/files.php" method="GET">
        <input class="search_input-select" name="keyword" size="25" maxlength="500" type="text">
        <input class="search_input-btn" value="Поиск" type="submit">
        </form>
    </div>
</div>
<hr>