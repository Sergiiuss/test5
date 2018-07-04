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

<?php $date = date('Y-m-d H:i:s');
if(isset($friends[0])): ?>
<h1>Друзья</h1>
<?php
foreach ($friends as $val):
    if($val['id'] == $this->login){continue;}?>
<div class="item">
    <p><a href="<?=base_url()?>site/user/<?=$val['id']?>"><?=$val['name']?></a> (<?=$val['status']?>)</p>

    <?php $time = strtotime($date) - strtotime($val['date_action']);
    if($time > 300){//5 минут после последнего действия ?>
        <span class="offline">Offline</span>
    <?php }else{ ?>
        <span class="online">Online</span>
    <?php } ?>


    <a href="<?=base_url()?>site/friend_del/<?=$val['id']?>" class="friend_add">Удалить из друзей</a>

    <a href="<?=base_url()?>site/message/<?=$val['id']?>" class="friend_add">Сообщение</a>

</div>
<?php endforeach;
endif; ?>



<?php
if(isset($i_friends[0])): ?>
<h1>Мои запросы в друзья</h1>
<?php
foreach ($i_friends as $val):
    if($val['id'] == $this->login){continue;}?>
<div class="item">
    <p><a href="<?=base_url()?>site/user/<?=$val['id']?>"><?=$val['name']?></a> (<?=$val['status']?>)</p>

    <?php $time = strtotime($date) - strtotime($val['date_action']);
    if($time > 300){//5 минут после последнего действия ?>
        <span class="offline">Offline</span>
    <?php }else{ ?>
        <span class="online">Online</span>
    <?php } ?>

    <a href="<?=base_url()?>site/friend_del/<?=$val['id']?>" class="friend_add">Ожидает подтверждения (отменить)</a>

</div>
<?php endforeach;
endif; ?>




<?php
if(isset($request_friends[0])): ?>
<h1>Запросы в друзья (мне)</h1>
<?php
foreach ($request_friends as $val):
    if($val['id'] == $this->login){continue;}?>
<div class="item">
    <p><a href="<?=base_url()?>site/user/<?=$val['id']?>"><?=$val['name']?></a> (<?=$val['status']?>)</p>

    <?php $time = strtotime($date) - strtotime($val['date_action']);
    if($time > 300){//5 минут после последнего действия ?>
        <span class="offline">Offline</span>
    <?php }else{ ?>
        <span class="online">Online</span>
    <?php } ?>

    <a href="<?=base_url()?>site/friend_confirm/<?=$val['id']?>" class="friend_add">Заявка в друзья (подтвердить)</a>

</div>
<?php endforeach;
endif; ?>
