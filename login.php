<?php

include "common/top.php";

if ( !empty( $_POST[ 'login' ] ) )
{
    $input   = $_POST[ 'login' ];
    $userSql =
        <<<SQL
        SELECT pkUsername
        FROM tblUsers
        WHERE pkUsername = :input
SQL;

    $user = $db->prepare( $userSql );
    $user->bindValue( ':input', $input );
    $user->execute();
    if ( $user->rowCount() > 0 )
    {
        $_SESSION[ 'username' ] = htmlentities($input);
        $error                  = "";
        header("Location: my.php");
        exit;
    }
    else
    {
        $error =
            <<<HTML
            <div class="alert-box alert">
                User not found.
            </div>
HTML;

    }
}
else
{
    $input = "";
    $error = "";
}

include "common/head.php";
?>

    <h2>Log in to Minwox</h2>
    <form class="custom" action="login.php" method="post" data-abide>
        <div class="large-4 large-centered columns">
            <?php echo $error; ?>
            <div class="username-input">
                <input name="login"
                       type="text"
                       required
                       pattern="alpha_numeric"
                       placeholder="Who are you?"
                       id="title"
                       value="<?php echo $input; ?>">
                <small class="error">Username is required.</small>
            </div>
            <div id="remember">
                <label for="rememberMe">
                    <input type="checkbox"
                           id="rememberMe"
                           checked
                           style="display: none">
                    <span class="custom checkbox"></span> Remember me
                </label>
            </div>
            <input type="submit" class="button primary right">
        </div>
    </form>


<?php include "common/bottom.php"; ?>