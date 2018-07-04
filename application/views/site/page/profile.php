<h1>Мой профиль</h1>
<p>Имя: <?=$user_info['name']?></p>
<p>Статус: <?=$user_info['status']?></p>
<p>E-mail: <?=$user_info['email']?></p>
<p>Дата регистрации: <?=$user_info['date_insert']?></p>
<p>Дата авторизации: <?=$user_info['date_login']?></p>
<br><br>
<p><a href="<?=base_url()?>site/profile_status_edit">Изменить статус</a></p>
<br>
<p><a href="<?=base_url()?>site/profile_pass_edit">Изменить пароль</a></p>

