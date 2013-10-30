<?php
include "common/top.php";

$username = getUsername();
if ( $username == "Anonymous" )
{
    header( "HTTP/1.0 403 Forbidden" );
    header( "Location: home.php" );
    exit;
}

$getSamplesSql =
    <<<SQL
    SELECT
     `sc`.`pkSampleId` AS 'id',
     `u`.`pkUsername` AS 'user',
     `sc`.`fldTitle` AS 'title',
     `l`.`fldLanguageName` AS 'lang',
     `sc`.`fldDescription` AS 'description',
     `sc`.`fldSampleCode` AS 'code'
     FROM
     `tblUsers` `u`
         JOIN `tblUserSample` `us`
         JOIN `tblSampleCode` `sc`
         JOIN `tblLanguages` `l`
     ON
     `u`.`pkUsername` = `us`.`fkUsername`
         AND `us`.`fkSampleId` = sc.`pkSampleId`
         AND `sc`.`fkSampleLanguage` = `l`.`pkLanguageId`
     WHERE
     `u`.`pkUsername` = "$username"
SQL;

$stmt = $db->prepare( $getSamplesSql );
$stmt->execute();
$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
include "common/head.php";
?>

    <table>
        <thead>
        <tr>
            <th width="150">
                Title
            </th>
            <th width="150">
                Language
            </th>
            <th width="150">
                Description
            </th>
            <th width="480">
                Code
            </th>
            <th width="10"></th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ( $rows as $row )
        {
            $title       = htmlentities( $row[ 'title' ] );
            $lang        = htmlentities( $row[ 'lang' ] );
            $description = htmlentities( $row[ 'description' ] );
            $code        = htmlentities( $row[ 'code' ] );
            $id          = htmlentities( $row[ 'id' ] );
            $output      =
                <<<HTML
                    <tr>
                <td>
                    {$title}
                </td>
                <td>
                    {$lang}
                </td>
                <td>
                    {$description}
                </td>
                <td>
                    <pre>{$code}</pre>
                </td>
                <td>
                    <a href="edit.php?id=$id" class="small button secondary">
                        Edit
                    </a>
                </td>
            </tr>
HTML;
            echo $output;
        }
        ?>
        </tbody>
    </table>
    <a href="new.php" id="add-new" class="medium button primary">
        Add a new snippet
    </a>


<?php include "common/bottom.php"; ?>