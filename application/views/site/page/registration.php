<style>
#registration{
    margin: 0 auto;
    padding: 10px 15px;
    width: 200px;
    border: 1px solid #D0D0D0;
}
#registration p.error{
    color: red;
    font-size: 11px;
}
#registration input{
    width: 98%;
}
</style>
<div id="registration">
<h1>Регистрация</h1>

<?=form_open('')?>
    <p>Имя*: </p>
    <p><input type="text" name="name" value="<?=set_value('name')?>"></p>
    <?=form_error('name', '<p class="error">', '</p>')?><br>

    <p>E-mail*: </p>
    <p><input type="text" name="email" value="<?=set_value('email')?>"></p>
    <?=form_error('email', '<p class="error">', '</p>')?><br>

    <p>Пароль*: </p>
    <p><input type="password" name="password" value="<?=set_value('password')?>"></p>
    <?=form_error('password', '<p class="error">', '</p>')?><br>

    <p>Пароль еще раз*: </p>
    <p><input type="password" name="passconf" value="<?=set_value('passconf')?>"></p>
    <?=form_error('passconf', '<p class="error">', '</p>')?><br>

    <p><input type="submit" name="" value="Зарегистрироватся"></p>
</form>
<br>
<p>* обязательные поля</p>
</div>