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
    Sign Up
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
            <label>Username:</label>
            <input name="user[username]" type="text" class="text <?php echo error_field('text', $errors); ?>"><?php echo Utils::escape($user->getUsername()); ?>
        </div>
        <div class="field">
            <label>Password:</label>
            <input name="user[password]" type="password" class="text <?php echo error_field('text', $errors); ?>"><?php echo Utils::escape($user->getPassword()); ?>
        </div>
        <div class="wrapper">
<!--            <input type="submit" name="cancel" value="CANCEL" class="submit" />-->
            <input type="submit" name="save" value="<?php echo $edit ? 'EDIT' : 'ADD'; ?>" class="submit" />
        </div>
    </fieldset>
</form>