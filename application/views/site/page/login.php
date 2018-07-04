<style>
#login{
    margin: 0 auto;
    padding: 10px 15px;
    width: 200px;
    border: 1px solid #D0D0D0;
}
#login p.error{
    color: red;
    font-size: 11px;
}
#login input{
    width: 98%;
}
</style>
<div id="login">
<h1>Авторизация</h1>

<?=form_open('')?>

    <p>E-mail*: </p>
    <p><input type="text" name="email" value="<?=set_value('email')?>"></p>
    <?=form_error('email', '<p class="error">', '</p>')?><br>

    <p>Пароль*: </p>
    <p><input type="password" name="password" value="<?=set_value('password')?>"></p>
    <?=form_error('password', '<p class="error">', '</p>')?><br>

    <p><input type="submit" name="" value="Войти"></p>
</form>
<br>
<p>* обязательные поля</p>
</div>