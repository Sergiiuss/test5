<style>
.item{
    border: 1px solid #D0D0D0;
    padding: 10px 15px;
    margin: 0 0 15px 0;
}
.offline{
    color: red;
}
.online{
    color: green;
}
.friend_add{
    float: right;
    margin: 0 0 0 10px;
}
</style>
<h1>Пользователи</h1>

<?php $date = date('Y-m-d H:i:s');
if(isset($users[0])):
foreach ($users as $val):
    if($val['id'] == $this->login){continue;}?>
<div class="item">
    <p><a href="<?=base_url()?>site/user/<?=$val['id']?>"><?=$val['name']?></a> (<?=$val['status']?>)</p>

    <?php if($this->login !== false): //Если авторизован
        $time = strtotime($date) - strtotime($val['date_action']);
        if($time > 300){//5 минут после последнего действия ?>
            <span class="offline">Offline</span>
        <?php }else{ ?>
            <span class="online">Online</span>
        <?php } ?>

        <?php $check_friend = $this->site_model->check_friend($val['id']);//Проверка на друзья
        if($check_friend == 0):?>
            <a href="<?=base_url()?>site/friend_add/<?=$val['id']?>" class="friend_add">Добавить в друзья</a>
        <?php elseif($check_friend == 3): ?>
            <a href="<?=base_url()?>site/friend_del/<?=$val['id']?>" class="friend_add">Удалить из друзей</a>
            <a href="<?=base_url()?>site/message/<?=$val['id']?>" class="friend_add">Сообщение</a>
        <?php elseif($check_friend == 1): ?>
            <a href="<?=base_url()?>site/friend_del/<?=$val['id']?>" class="friend_add">Ожидает подтверждения (отменить)</a>
        <?php elseif($check_friend == 2): ?>
            <a href="<?=base_url()?>site/friend_confirm/<?=$val['id']?>" class="friend_add">Заявка в друзья (подтвердить)</a>
        <?php endif; ?>

    <?php endif; ?>

</div>
<?php endforeach;
endif; ?>
