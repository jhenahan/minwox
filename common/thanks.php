<?php
if ( $spamMe )
{
    $spam_p = "Yes, please!";
}
else
{
    $spam_p = "Nope.";
}

$headers = 'From: jhenahan@uvm.edu' . "\r\n";
$headers .= 'Reply-To: jhenahan@uvm.edu' . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$base = $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

$subject = "Thank you for registering";
$message =
    <<<HTML
    <ul>
        <li>Username: $username</li>
        <li>First Name: $firstName</li>
        <li>Last Name: $lastName</li>
        <li>Email: $email</li>
        <li>Spam? $spam_p </li>
    </ul>
HTML;
$confirmLink = "https://{$base}/confirm.php?user={$username}";
$confirm =
    <<<CONFIRM
    <p>Click the link below to confirm your registration.</p>
    <p><a href="{$confirmLink}">{$confirmLink}</a></p>
CONFIRM;
$finalMessage = $message . $confirm;
mail(emailAddress($firstName, $lastName, $email),$subject,$finalMessage,$headers);



echo "<h1>{$subject}</h1>{$message}";
?>

<p>
    An email confirmation was sent to you. You must confirm your account before
    you can use Minwox.
</p>