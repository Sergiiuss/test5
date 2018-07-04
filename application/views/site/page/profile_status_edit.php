<style>
p.error{
    color: red;
    font-size: 11px;
}
</style>

<h1>Изменить статус профиля:</h1>
<?=form_open('')?>

    <p>Укажите статус: </p>
    <p><input type="text" name="status" value="<?=set_value('status', $user_info['status'])?>"></p>
    <?=form_error('status', '<p class="error">', '</p>')?><br>

    <p><input type="submit" name="" value="Сохранить"></p>
</form>
