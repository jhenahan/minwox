<?php

include "common/top.php";

$username = getPost( 'username' );
$firstName = getPost( 'firstname' );
$lastName = getPost( 'lastname' );
$email = getPost( 'email' );
$spam = getPost( 'spamMe' );
if ( $spam != 'on' )
{
    $spamMe  = false;
    $checked = '';
}
else
{
    $spamMe  = true;
    $checked = 'checked';
}


$form_values =
    array( $username, $firstName, $lastName, $email );

/*
 * This is a fold which returns false if any field is empty. Since the content
 * doesn't matter, this is all we need to check for. If all the relevant fields
 * are filled in, this returns true and we proceed with the database stuff.
 */
$none_empty = array_reduce( $form_values, function ( $a, $b )
{
    return !empty( $a ) && !empty( $b );
}, true );

if ( $none_empty )
{
    $userSql =
        <<<SQL
        INSERT INTO tblUsers
        SET pkUsername = :username,
        fldFirstName = :firstname,
        fldLastName = :lastname,
        fldEmail = :email,
        fldSpamMe = :spam
SQL;

    $user = $db->prepare( $userSql );
    $user->bindValue( ':username', $username );
    $user->bindValue( ':firstname', $firstName );
    $user->bindValue( ':lastname', $lastName );
    $user->bindValue( ':email', $email );
    $user->bindValue( ':spam', $spamMe );
    try
    {
        $user->execute();
        include "common/head.php";
        include "common/thanks.php";
        include "common/bottom.php";
        exit;
    }
    catch (PDOException $e)
    {
        print "Error: " . $e->getMessage();
    }
}

include "common/head.php";
?>

    <h2>Register</h2>
    <form class="custom" action="register.php" method="post" data-abide>
        <div class="large-4 large-centered columns">
            <div class="username-input">
                <input name="username"
                       type="text"
                       required
                       pattern="alpha_numeric"
                       placeholder="Desired Username"
                       id="username"
                       value="<?php echo $username; ?>">
                <small class="error">Username is required.</small>
            </div>
            <div class="firstname-input">
                <input name="firstname"
                       type="text"
                       required
                       pattern="alpha_numeric"
                       placeholder="First Name"
                       id="firstname"
                       value="<?php echo $firstName; ?>">
                <small class="error">First name is required.</small>
            </div>
            <div class="username-input">
                <input name="lastname"
                       type="text"
                       required
                       pattern="alpha_numeric"
                       placeholder="Last Name"
                       id="lastname"
                       value="<?php echo $lastName; ?>">
                <small class="error">Last name is required.</small>
            </div>
            <div class="email-input">
                <input name="email"
                       type="text"
                       required
                       pattern="email"
                       placeholder="Email Address"
                       id="email"
                       value="<?php echo $email; ?>">
                <small class="error">Email is required.</small>
            </div>
            <div id="spam">
                <label for="spamMe">
                    <input type="checkbox"
                           name="spamMe"
                           id="spamMe"
                        <?php echo $checked ?>
                           style="display: none">
                    <span class="custom checkbox"></span> Please send me lots
                                                          of spam
                </label>
            </div>
            <input type="submit" class="button primary right">
        </div>
    </form>


<?php include "common/bottom.php"; ?>