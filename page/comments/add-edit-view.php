<?php

function error_field($title, array $errors) {
    foreach ($errors as $error) {
        /* @var $error Error */
        if ($error->getSource() == $title) {
            return ' error-field';
        }
    }
    return '';
}
?>

<h1>
    <?php if ($edit): ?>
        Edit Comment
    <?php else: ?>
        Add New Comment
    <?php endif; ?>
</h1>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <?php /* @var $error Error */ ?>
            <li><?php echo $error->getMessage(); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="#" method="POST">
    <fieldset>
        <div class="field">
            <label>Comment:</label>
            <textarea name="comment[comment]" class="text <?php echo error_field('text', $errors); ?>"><?php echo Utils::escape($comment->getComment()); ?></textarea>
        </div>           
        <div class="wrapper">
<!--            <input type="submit" name="cancel" value="CANCEL" class="submit" />-->
            <input type="submit" name="save" value="<?php echo $edit ? 'EDIT' : 'ADD'; ?>" class="submit" />
        </div>
    </fieldset>
</form>