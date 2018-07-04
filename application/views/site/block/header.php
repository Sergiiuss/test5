<style>
#header p{
    font-weight: bold;
    margin: 0;
}
#header a{
    display: block;
    float: right;
    margin: 0 0 0 15px;
}
#header a:hover{
    text-decoration: none;
}
</style>
<?php if($this->login === false): ?>
    <a href="<?=base_url()?>site/registration">Регистрация</a>
    <a href="<?=base_url()?>site/login">Вход</a>
<?php else: ?>
    <a href="<?=base_url()?>site/logout">Выход</a>
    <a href="<?=base_url()?>site/profile">Профиль</a>
    <a href="<?=base_url()?>site/my_friend">Друзья</a>
    <a href="<?=base_url()?>site/messages">Сообщения <span id="mess"></span></a>
<?php endif; ?>

<p><a href="<?=base_url()?>" style="float: none; margin: 0">Home</a></p>

<div style="clear: both"></div>

<script>
$(document).ready(function(){
    setInterval(function() {
        $.ajax({
            url: "<?=base_url()?>site/message_monitor/",
            cache: false,
            success: function(otvet){
                if(otvet > 0){
                    $('#mess').html('('+otvet+')');
                }else{
                    $('#mess').html('');
                }
            }
        });
    }, 1000);
})
</script>