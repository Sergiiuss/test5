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

<h1>Диалоги</h1>

<?php $date = date('Y-m-d H:i:s');
if(isset($dialog_list[0])):
    foreach ($dialog_list as $val):
        if($val['id'] == $this->login){continue;}?>
        <div class="item">
            <p><?=$val['name']?> (<?=$val['status']?>)</p>
            <?php
            $count = $this->site_model->get_message_monitor($val['id']);
            $time = strtotime($date) - strtotime($val['date_action']);
            if($time > 300){//5 минут после последнего действия ?>
                <span class="offline">Offline</span>
            <?php }else{ ?>
                <span class="online">Online</span>
            <?php } ?>


            <a href="<?=base_url()?>site/message/<?=$val['id']?>" class="friend_add">Написать сообщение (<?=$count?>)</a>




        </div>
    <?php endforeach;
endif; ?>
