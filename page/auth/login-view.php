<div class="auth">
    <form method="POST" action="#">
        <div class="form-group">
            <label for="username">Username:</label>
            <input class="auth__input" id="username" type="text" name="inputUsername"/>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input class="auth__input" id="password" type="password" name="inputPassword"/>
        </div>
        <?php
        if (isset($error)) {
            echo $error;
        }
        ?>
        <input class="form__submit" name="submit" type="submit" value="Log In">
    </form>
</div>