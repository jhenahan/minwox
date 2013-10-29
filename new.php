<?php

include "common/top.php";

$username = getUsername();
$title = getPost( 'title' );
$language = getPost( 'language' );
$description = getPost( 'description' );
$privacy = getPost( 'privacy' );
if ( $privacy == 'public' )
{
    $private        = "unchecked";
    $public         = "checked";
    $privateSnippet = false;
}
else
{
    $private        = "checked";
    $public         = "unchecked";
    $privateSnippet = true;
}
$code = htmlentities( getPost( 'code' ) );

$form_values = array( $title, $language, $description, $privacy, $code );

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
    $db->beginTransaction();
    $newSnippetSql =
        <<<SQL
        INSERT INTO tblSampleCode
        SET
        fkSampleLanguage = :lang,
        fldSampleCode = :sample,
        fldPrivate = :privacy,
        fldTitle = :title,
        fldDescription = :description
SQL;
    $newSnippet    = $db->prepare( $newSnippetSql );
    $newSnippet->bindValue( ':lang', $language );
    $newSnippet->bindValue( ':sample', $code );
    $newSnippet->bindValue( ':privacy', $privateSnippet );
    $newSnippet->bindValue( ':title', $title );
    $newSnippet->bindValue( ':description', $description );
    echo $privateSnippet;

    $joinSql =
        <<<SQL
        INSERT INTO tblUserSample
        SET
        fkSampleId = :someId,
        fkUsername = :username
SQL;
    $join    = $db->prepare( $joinSql );


    try
    {
        $newSnippet->execute();
        $snippetId = $db->lastInsertId();
        $join->bindValue( ':someId', $snippetId );
        $join->bindValue( ':username', $username );
        $join->execute();
        $db->commit();
        header( "Location: my.php" );
    }
    catch ( PDOException $e )
    {
        $db->rollBack();
        print "Error: " . $e->getMessage();
    }

}
?>

    <h1>New Snippet</h1>
    <form class="custom" action="new.php" method="post" data-abide>
        <div class="large-10 large-centered columns">
            <div class="title-input">
                <input name="title"
                       type="text"
                       required
                       pattern="alpha_numeric"
                       placeholder="Give your snippet a memorable name"
                       id="title"
                       value="<?php echo $title; ?>">
                <small class="error">Title is required</small>
            </div>
            <div class="language-input">
                <select id="language" name="language" required pattern="number">
                    <option value="" selected="" disabled>Choose a Language...
                    </option>
                    <?php
                    $languages = getLanguages( $db );
                    foreach ( $languages as $lang )
                    {
                        $selected = $lang[ 'id' ] == $language ?
                            'selected=selected' : '';
                        $output   =
                            <<<HTML
                                <option value="{$lang['id']}" {$selected}>
                            {$lang['language']}
                        </option>
HTML;
                        echo $output;

                    }
                    ?>
                </select>
                <small class="error">Language is required</small>
            </div>
            <div class="description-input">
                <textarea name="description"
                          placeholder="What does your snippet demonstrate?"
                          id="description"
                          required
                          pattern="alpha_numeric"><?php echo $description; ?></textarea>
                <small class="error">Description is required</small>
            </div>

            <div class="switch radius small-4">
                <input id="private"
                       name="privacy"
                       type="radio"
                       value="private"
                    <?php echo $private; ?>>
                <label for="private" onclick="">Private</label>

                <input id="public"
                       name="privacy"
                       type="radio"
                       value="public"
                    <?php echo $public; ?>>
                <label for="public" onclick="">Public</label>

                <span></span>
            </div>
            <div class="sample-input">
                <textarea placeholder="Paste your code here..."
                          rows="20"
                          name="code"
                          class="code"
                          required><?php echo $code; ?></textarea>
                <small class="error">A code sample is required</small>
            </div>

            <input type="submit" class="large button primary expand">
        </div>

    </form>

<?php include "common/bottom.php"; ?>