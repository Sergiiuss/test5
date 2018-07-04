<style>
p.error{
    color: red;
    font-size: 11px;
}
</style>
<h1>Изменить пароль</h1>
<?=form_open('')?>

    <p>Старый пароль: </p>
    <p><input type="password" name="passold" value="<?=set_value('passold')?>"></p>
    <?=form_error('passold', '<p class="error">', '</p>')?><br>

    <p>Новый пароль: </p>
    <p><input type="password" name="password" value="<?=set_value('password')?>"></p>
    <?=form_error('password', '<p class="error">', '</p>')?><br>

    <p>Новый пароль еще раз: </p>
    <p><input type="password" name="passconf" value="<?=set_value('passconf')?>"></p>
    <?=form_error('passconf', '<p class="error">', '</p>')?><br>

    <p><input type="submit" name="" value="Сохранить"></p>
</form>
