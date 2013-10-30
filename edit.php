<?php

include "common/top.php";


if ( strtoupper( $_SERVER[ 'REQUEST_METHOD' ] ) == 'GET' )
{
    $id               = $_GET[ 'id' ];
    $getCurrentSample =
        <<<SAMPLE
        SELECT
        fldSampleCode as "code",
        fldPrivate as "privacy",
        fkSampleLanguage as "lang",
        fldTitle as "title",
        fldDescription as "description"
        FROM tblSampleCode
        WHERE pkSampleId = :input
SAMPLE;
    $sample           = $db->prepare( $getCurrentSample );
    $sample->bindValue( ':input', $id );
    try
    {
        $sample->execute();
        $data        = $sample->fetch( PDO::FETCH_ASSOC );
        $title       = $data[ 'title' ];
        $language    = $data[ 'lang' ];
        $description = $data[ 'description' ];
        $privacy     = $data[ 'privacy' ] ? true : false;
        $code        = $data[ 'code' ];
    }
    catch ( PDOException $e )
    {
        print "Error: " . $e->getMessage();
    }
    if ( $privacy )
    {
        $private        = "checked";
        $public         = "unchecked";
        $privateSnippet = true;
    }
    else
    {
        $private        = "unchecked";
        $public         = "checked";
        $privateSnippet = false;
    }
}
elseif ( strtoupper( $_SERVER[ 'REQUEST_METHOD' ] ) == 'POST' )
{
    if ( isset( $_POST[ 'id' ] ) )
    {
        $id = $_POST[ 'id' ];
    }
    else
    {
        header( 'Location: home.php' );
        exit;
    }
    $title       = getPost( 'title' );
    $language    = getPost( 'language' );
    $description = getPost( 'description' );
    $privacy     = getPost( 'privacy' );
    if ( $privacy == 'public' )
    {
        $private        = "";
        $public         = "checked";
        $privateSnippet = false;
    }
    else
    {
        $private        = "checked";
        $public         = "";
        $privateSnippet = true;
    }
    $code = htmlentities( getPost( 'code' ) );

    $form_values =
        array( $id, $title, $language, $description, $privacy, $code );

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
        $updateSnippetSql =
            <<<SQL
            UPDATE tblSampleCode
            SET
            fkSampleLanguage = :lang,
            fldSampleCode = :sample,
            fldPrivate = :privacy,
            fldTitle = :title,
            fldDescription = :description
            WHERE pkSampleId = :someId
SQL;
        $updateSnippet    = $db->prepare( $updateSnippetSql );
        $updateSnippet->bindValue( ':lang', $language );
        $updateSnippet->bindValue( ':sample', $code );
        $updateSnippet->bindValue( ':privacy', $privateSnippet );
        $updateSnippet->bindValue( ':title', $title );
        $updateSnippet->bindValue( ':description', $description );
        $updateSnippet->bindValue( ':someId', $id );
        try
        {
            $updateSnippet->execute();
            header('Location: my.php');
        }
        catch (PDOException $e)
        {
            print "Error: " . $e->getMessage();
        }

    }
}
else
{
    header( 'Location: home.php' );
    exit;
}
include "common/head.php";
?>

    <h1>Edit Snippet</h1>
    <form class="custom" action="edit.php" method="post" data-abide>
        <input type="hidden" name="id" value="<?php echo $_GET[ 'id' ] ?>">

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