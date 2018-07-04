<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller{

    public $login = false;//ID пользователя при авторизации

    /** Конструктор */
    public function __construct(){
        parent::__construct();
        $this->load->model('site_model');
        $this->login = $this->site_model->user_init();
    }

    /** Стартовая страница */
    public function index(){

        $c['users'] = $this->site_model->get_users();

        $index['title'] = 'Главная';
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['content'] = $this->load->view('site/page/index', $c, true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);
    }

    /** Страница пользователя */
    public function user($id = 0){
        if($id == 0){redirect('');}

        $c['user_info'] = $this->site_model->get_user_id($id);

        $index['title'] = 'Главная';
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['content'] = $this->load->view('site/page/user', $c, true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);
    }


    /** ---------------- Сообщения ---------------- */

    /** Список Диалогов */
    public function messages(){

        $c['dialog_list'] = $this->site_model->dialog_list();

        $index['title'] = 'Диалог';
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['content'] = $this->load->view('site/page/messages', $c, true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);
    }

    /** Переписка с другим пользователем */
    public function message($id = 0){
        if($id == 0){redirect('');}

        //Валидация и сохранение сообщения
        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Сообщение', 'required|max_length[5000]');//правила валидации
        if($this->form_validation->run() == FALSE){}else{
            $this->site_model->set_message($id);
        }

        $c['message_list'] = $this->site_model->message_list($id);

        $index['title'] = 'Диалог';
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['content'] = $this->load->view('site/page/message', $c, true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);

    }

    /** Кол-во непрочитанных сообщений */
    public function message_monitor(){
        $this->db->where('friend_read', 0);
        $this->db->where('id_friend', $this->login);
        $this->db->from('site_users_messages');
        echo $this->db->count_all_results();
    }

    /** ---------------- Друзья/Манипуляции ---------------- */

    /** Список друзей */
    public function my_friend(){

        $c['friends'] = $this->site_model->my_friend();//Друзья
        $c['i_friends'] = $this->site_model->i_my_friend();//Мои запросы в друзья
        $c['request_friends'] = $this->site_model->request_my_friend();//Запросы в друзья

        $index['title'] = 'Мои друзья';
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['content'] = $this->load->view('site/page/my_friend', $c, true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);
    }

    /** Добавление в друзья */
    public function friend_add($id){
        if($id > 0){
            $data['id_user'] = $this->login;
            $data['id_friend'] = $id;
            $data['status'] = 1;//Запрос в друзья
            $this->db->insert('site_users_friends', $data);
        }
        redirect('site/my_friend');
    }

    /** Подтверждения в друзья */
    public function friend_confirm($id){
        if($id > 0){
            //Подтверждаю запрос в друзья
            $data1['id_user'] = $this->login;
            $data1['id_friend'] = $id;
            $data1['status'] = 2;//Друзья
            $this->db->insert('site_users_friends', $data1);

            //Обновляю статус того кто добавил в друзья
            $data2['status'] = 2;//Друзья
            $this->db->where('id_user', $id);
            $this->db->where('id_friend', $this->login);
            $this->db->update('site_users_friends', $data2);
        }
        redirect('site/my_friend');
    }

    /** Удаление из друзей */
    public function friend_del($id){

        //Удаление в 1 направлении
        $this->db->where('id_user', $this->login);
        $this->db->where('id_friend', $id);
        $this->db->delete('site_users_friends');

        //Удаление во 2 направлении
        $this->db->where('id_user', $id);
        $this->db->where('id_friend', $this->login);
        $this->db->delete('site_users_friends');

        redirect('site/my_friend');
    }

    /** ---------------- Профиль/Манипуляции ---------------- */

    /** Профиль пользователя */
    public function profile(){

        if($this->login === false){redirect('');}

        $c['user_info'] = $this->site_model->get_user_id($this->login);

        $index['title'] = 'Мой профиль';
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['content'] = $this->load->view('site/page/profile', $c, true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);
    }

    /** Редактирование статуса профиля */
    public function profile_status_edit(){

        if($this->login === false){redirect('');}

        $this->load->library('form_validation');

        $this->form_validation->set_rules('status', 'Статус', 'max_length[255]');//правила валидации

        if ($this->form_validation->run() == FALSE){
            $c['user_info'] = $this->site_model->get_user_id($this->login);

            $index['title'] = 'Редактирование статуса';
            $index['header'] = $this->load->view('site/block/header', '', true);
            $index['content'] = $this->load->view('site/page/profile_status_edit', $c, true);
            $index['footer'] = $this->load->view('site/block/footer', '', true);

            $this->load->view('site/index', $index);
        }else{
            $this->site_model->set_user_status();//Смена статуса
            redirect('site/profile');//Переброс на профиль
        }
    }

    /** Редактирование пароля профиля */
    public function profile_pass_edit(){

        if($this->login === false){redirect('');}

        $this->load->library('form_validation');

        $config = $this->site_model->pass_edit_val();//правила валидации
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            $c['user_info'] = $this->site_model->get_user_id($this->login);

            $index['title'] = 'Смена пароля';
            $index['header'] = $this->load->view('site/block/header', '', true);
            $index['content'] = $this->load->view('site/page/profile_pass_edit', $c, true);
            $index['footer'] = $this->load->view('site/block/footer', '', true);

            $this->load->view('site/index', $index);
        }else{
            $this->site_model->set_user_pass();//Смена пароля
            redirect('site/profile');//Переброс на профиль
        }
    }
    /** Валидация - проверка старого пароля */
    public function oldpass_check($str){
        $user = $this->site_model->get_user_id($this->login);
        if($str == ''){
            $this->form_validation->set_message('oldpass_check', '{field} не может быть пустым');
            return FALSE;
        }elseif(!password_verify($str, $user['pass'])){
            $this->form_validation->set_message('oldpass_check', '{field} не совпадает');
            return FALSE;
        }else{
            return TRUE;
        }
    }


    /** ---------------- Вход/Выход/Регистрация ---------------- */

    /** Регистрация */
    public function registration(){

        $this->load->library('form_validation');

        $config = $this->site_model->registration_val();//правила валидации
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE){
            $index['title'] = 'Регистрация';
            $index['content'] = $this->load->view('site/page/registration', '', true);
        }else{
            $this->site_model->set_registration();//Регистрируем пользователя
            $index['title'] = 'Успешная регистрация';
            $index['content'] = $this->load->view('site/page/registration_success', '', true);
        }
        $index['header'] = $this->load->view('site/block/header', '', true);
        $index['footer'] = $this->load->view('site/block/footer', '', true);

        $this->load->view('site/index', $index);
    }

    /** Вход */
    public function login(){

        $this->load->library('form_validation');

        $config = $this->site_model->login_val();//правила валидации
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE){
            $index['title'] = 'Авторизация';
            $index['header'] = $this->load->view('site/block/header', '', true);
            $index['content'] = $this->load->view('site/page/login', '', true);
            $index['footer'] = $this->load->view('site/block/footer', '', true);

            $this->load->view('site/index', $index);
        }else{
            $rez = $this->site_model->set_login();//Авторизация пользователя
            if($rez === true){
                redirect('');//Переброс на стартовую
            }else{
                redirect('site/login');//Переброс на повторную авторизацию
            }
        }
    }

    /** Выход */
    public function logout(){

        $date = date('Y-m-d H:i:s');
        $time = strtotime($date) - 500;//Ставим оффлайн

        //Обновляем дату действия
        $data['date_action'] = $time;
        $this->db->where('id', $this->login);
        $this->db->update('site_users', $data);

        unset($_SESSION['user_id']);
        unset($_SESSION['user_hash']);

        redirect('');//Переброс на стартовую
    }

}