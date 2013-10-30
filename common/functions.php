<?php


function buildConnectionString( $engine = "mysql"
    , $hostname = "localhost"
    , $database = "minwox" )
{
    return "{$engine}:host={$hostname};dbname=${database}";
}

function checkedConnection( $engine
    , $hostname
    , $database
    , $user
    , $password )
{
    $connection = buildConnectionString( $engine, $hostname, $database );
    $db         = new PDO( $connection, $user, $password );

    return $db;
}

function getUsername()
{
    if ( isset( $_SESSION[ 'username' ] ) )
    {
        return $_SESSION[ 'username' ];
    }
    else
    {
        return "Anonymous";
    }
}

function getPost( $field )
{
    if ( isset( $_POST[ $field ] ) )
    {
        return $_POST[ $field ];
    }
    else
    {
        return "";
    }
}

function getLanguages( PDO $db )
{
    $getLanguagesSql =
        <<<SQL
        SELECT pkLanguageId AS id, fldLanguageName AS language
    FROM tblLanguages
    ORDER BY fldLanguageName
SQL;
    $getLanguages    = $db->prepare( $getLanguagesSql );
    $getLanguages->execute();

    return $getLanguages->fetchAll( PDO::FETCH_ASSOC );
}

function emailAddress($firstname, $lastname, $email)
{
    return "{$firstname} {$lastname} <{$email}>";
}
