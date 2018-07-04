<style>
.post{
    margin: 0 0 10px 0;
}
.post span{
    font-size: 11px;
    color: #888888;
}
.p_left{
    float: left;
    background: rgba(0, 255, 0, 0.15);
    padding: 10px 20px;
    border-radius: 0 10px 10px 10px;
}
.p_left div{
    text-align: left;
    font-weight: bold;
}
.p_right{
    float: right;
    background: rgba(0, 0, 255, 0.15);
    padding: 10px 20px;
    border-radius: 10px 0 10px 10px;
}
.p_right div{
    text-align: right;
    font-weight: bold;
}
textarea{
    width: 99%;
    border-radius: 5px;
    padding: 3px 6px;
}
p.error{
    color: red;
    font-size: 11px;
}
</style>

<h1>Диалог</h1>

<?php
if(isset($message_list[0])):
    foreach ($message_list as $val):
        $user = $this->site_model->get_user_id($val['id_user']);
        if($val['id_user'] == $this->login){//От меня
            $p = 'class="p_right"';
            if($val['friend_read'] == 0){
                $st = '<b style="color: red">&or;</b>';
            }else{
                $st = '<b style="color: green">&or;</b>';
            }
        }else{//Мне
            $p = 'class="p_left"';
            $st = '';
        }?>

<div class="post">
    <div <?=$p?>>
        <div><?=$user['name']?></div>
        <p>
            <?=$val['message']?>
            <span><?=substr($val['time'], 11, 5)?></span>
            <?=$st?>
        </p>
    </div>
    <div style="clear:both;"></div>
</div>

<?php endforeach;
endif; ?>

<?=form_open('')?>

    <textarea name="message" placeholder="Напишите сообщение" rows="3" autofocus></textarea>
    <?=form_error('message', '<p class="error">', '</p>')?>



    <p style="text-align: right; margin: 10px 0 0 0"><input type="submit" name="" value="Отправить"></p>
</form>
