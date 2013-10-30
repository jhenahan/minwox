<?php

include "common/top.php";

if (isset($_GET['user']))
{
    $username = $_GET['user'];
    $confirmSql =
        <<<SQL
        UPDATE tblUsers
        SET fldConfirmedRegistration = 1
        WHERE pkUsername = :username
SQL;
    $confirm = $db->prepare($confirmSql);
    $confirm->bindValue(':username', $username);

    try
    {
        $confirm->execute();
    }
    catch (PDOException $e)
    {
        print "Error: " . $e->getMessage();
        exit;
    }

}
else
{
    header('Location: home.php');
    exit;
}

include "common/head.php";
?>

<h1>Success!</h1>
<p>You have confirmed you registration, and can now log in.</p>
