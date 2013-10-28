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