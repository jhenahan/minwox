<?php

include ".env.php";

try
{
    $db = checkedConnection( $dbEngine, $dbHost, $dbDatabase, $dbUsername,
                             $dbPassword );
}
catch ( PDOException $e )
{
    print "Error: " . $e->getMessage();
}
