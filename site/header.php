<header>
    <div class="header">
        <div class="header_logo">
            <nav class="header_nav">
                <a class="header_logo-link" href="/"> <?php echo $sitenametitle ?></a>
                <a class="header_nav-link" href="/tools/files.php">Последние файлы</a>
            </nav>
        </div>
        <div class="search_input">
            <form name="keyword1" action="/tools/files.php" method="GET">
                <input class="search_input-select" name="keyword" size="25" maxlength="500" placeholder="Поиск файлов" type="text">
                <input class="search_input-btn" value="Поиск" type="submit">
            </form>
        </div>
    </div>
</header>