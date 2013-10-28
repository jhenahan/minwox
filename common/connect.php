<?php

include $_SERVER[ 'DOCUMENT_ROOT' ] . "/.env.php";

try
{
    $db = checkedConnection( $dbEngine, $dbHost, $dbDatabase, $dbUsername,
                             $dbPassword );
}
catch ( PDOException $e )
{
    print "Error: " . $e->getMessage();
}
