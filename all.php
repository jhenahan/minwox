<?php
include "common/top.php";

$getSamplesSql =
    <<<SQL
     SELECT
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
     `u`.`fldConfirmedRegistration` <> 0
         AND `sc`.`fldPrivate` <> 1
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
            Author
        </th>
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
    </tr>
    </thead>
    <tbody>
    <?php

    foreach ( $rows as $row )
    {
        $user        = strip_tags( $row[ 'user' ] );
        $title       = strip_tags( $row[ 'title' ] );
        $lang        = strip_tags( $row[ 'lang' ] );
        $description = strip_tags( $row[ 'description' ] );
        $code        = strip_tags( $row[ 'code' ] );
        $output      =
            <<<HTML
                <tr>
                <td>
                    {$user}
                </td>
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
            </tr>
HTML;
        echo $output;
    }
    ?>
    </tbody>
</table>


<?php include "common/bottom.php"; ?>
