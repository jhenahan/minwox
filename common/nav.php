<?php
    if (getUsername() == "Anonymous")
    {
        $anon = true;
    }
    else
    {
        $anon = false;
    }
?>
<nav class="top-bar">
    <ul class="title-area">
        <li class="name">
            <h1><a href="/">minwox.</a></h1>
        </li>
        <li class="toggle-topbar menu-icon">
            <a href="#"><span>Menu</span></a>
        </li>
    </ul>

    <section class="top-bar-section">
        <ul class="left">
            <li class="divider"></li>
            <li class="has-dropdown">
                <a href="all.php">Snippets</a>
                <ul class="dropdown">
                    <?php
                    if (!$anon)
                    {
                        $li =
                            <<<HTML
                            <li>
                                <a href="../my.php">My Snippets</a>
                            </li>
HTML;
                        echo $li;
                    }
                    ?>
                    <li>
                        <a href="new.php">New Snippet</a>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>
        </ul>
    </section>
    <section class="top-bar-section">
        <ul class="right">
            <li class="divider"></li>
            <li class="has-dropdown">
                <a href="#"><?php echo getUsername(); ?></a>
                <ul class="dropdown">
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
                        <a href="register">Register</a>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>
        </ul>
    </section>
</nav>
