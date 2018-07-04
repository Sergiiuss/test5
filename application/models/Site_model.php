<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_model extends CI_Model{

    public function __construct(){
        parent::__construct();
    }

    /** ---------------- Методы сохранения ---------------- */

    /** Смена статуса профиля */
    public function set_user_status(){
        $data['status'] = $this->input->post('status', TRUE);
        $this->db->where('id', $this->login);
        $this->db->update('site_users', $data);
    }

    /** Смена пароля профиля */
    public function set_user_pass(){
        $data['pass'] = password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT);
        $this->db->where('id', $this->login);
        $this->db->update('site_users', $data);
    }

    /** Регистрация - запись пользователя в базу */
    public function set_registration(){

        $data['name'] = $this->input->post('name', TRUE);
        $data['email'] = $this->input->post('email', TRUE);
        $data['pass'] = password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT);
        $data['date_insert'] = date('Y-m-d H:i:s');

        $this->db->insert('site_users', $data);
    }


    /** ---------------- Манипуляции/Запросы ---------------- */

    /** Авторизация пользователя */
    public function set_login(){
        $email = $this->input->post('email', TRUE);
        $pass = $this->input->post('password', TRUE);

        $this->db->select('*');
        $this->db->where('email', $email);
        $query = $this->db->get('site_users');
        if($query->num_rows()>0){//Данные совпали
            $user_info = $query->row();

            if(password_verify($pass, $user_info->pass)){//пароль подошел

                //Обновляем дату авторизации
                $data['date_login'] = date('Y-m-d H:i:s');
                $this->db->where('id', $user_info->id);
                $this->db->update('site_users', $data);

                //Пишем сессии
                $_SESSION['user_id'] = $user_info->id;
                $_SESSION['user_hash'] = md5($data['date_login']);

                return true;
            }else{
                return false;
            }
        }else{//Данные НЕ совпали
            return false;
        }
    }

    /** Проверка авторизации пользователя */
    public function user_init(){
        if(isset($_SESSION['user_id']) AND $_SESSION['user_id'] > 0){
            $id = $_SESSION['user_id'];
        }
        if(isset($_SESSION['user_hash']) AND $_SESSION['user_hash'] != ''){
            $hash = $_SESSION['user_hash'];
        }
        if(isset($id) AND isset($hash)){
            $this->db->select('*');
            $this->db->where('id', $id);
            $query = $this->db->get('site_users');
            if($query->num_rows()>0) {//Пользователь есть
                $user_info = $query->row();
                if(md5($user_info->date_login) == $hash){

                    //Обновляем дату действия
                    $data['date_action'] = date('Y-m-d H:i:s');
                    $this->db->where('id', $user_info->id);
                    $this->db->update('site_users', $data);

                    return $user_info->id;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /** ---------------- Сообщения ---------------- */

    /** Список диалогов */
    public function dialog_list(){

        $freind_id = array();

        $this->db->select('id_friend');
        $this->db->distinct();
        $this->db->where('id_user', $this->login);
        $query = $this->db->get('site_users_messages');
        if($query->num_rows()>0) {
            $rez = $query->result_array();
            foreach ($rez as $val){
                $freind_id[] = $val['id_friend'];
            }

            $this->db->select('id_user');
            $this->db->distinct();
            $this->db->where('id_friend', $this->login);
            $this->db->where_not_in('id_user', $freind_id);
            $query = $this->db->get('site_users_messages');
            if($query->num_rows()>0) {
                $rez2 = $query->result_array();
                foreach ($rez2 as $val){
                    $freind_id[] = $val['id_user'];
                }
            }
        }else{
            $this->db->select('id_user');
            $this->db->distinct();
            $this->db->where('id_friend', $this->login);
            $query = $this->db->get('site_users_messages');
            if($query->num_rows()>0) {
                $rez = $query->result_array();
                foreach ($rez as $val){
                    $freind_id[] = $val['id_user'];
                }
            }
        }

        if(isset($freind_id[0])){
            $this->db->select('*');
            $this->db->where_in('id', $freind_id);
            $query = $this->db->get('site_users');
            if($query->num_rows()>0) {
                return $query->result_array();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /** Список сообщений в диалоге */
    public function message_list($id){

        //Делаем сообщения прочитанными
        $data['friend_read'] = 1;
        $this->db->where('id_user', $id);
        $this->db->where('id_friend', $this->login);
        $this->db->update('site_users_messages', $data);

        //Достаем диалог
        $this->db->select('*');
        $this->db->where('id_user', $this->login);
        $this->db->where('id_friend', $id);
        $this->db->or_where('id_friend', $this->login);
        $this->db->where('id_user', $id);
        $this->db->order_by('time', 'asc');
        $this->db->limit(10);
        $query = $this->db->get('site_users_messages');
        if($query->num_rows()>0) {
            return $query->result_array();
        }else{
            return false;
        }
    }

    /** Кол-во непрочитанных сообщений по юзеру */
    public function get_message_monitor($id){
        $this->db->where('friend_read', 0);
        $this->db->where('id_user', $id);
        $this->db->where('id_friend', $this->login);
        $this->db->from('site_users_messages');
        return $this->db->count_all_results();
    }

    /** Отправляет сообщение ползователю */
    public function set_message($id){
        $data['id_user'] = $this->login;
        $data['id_friend'] = $id;
        $data['message'] = $this->input->post('message', TRUE);

        $this->db->insert('site_users_messages', $data);
    }


    /** ---------------- Друзья/Манипуляции ---------------- */

    /** Проверка пользователя - Друзья ли */
    public function check_friend($id){

        $a1 = 0;//Я добавил
        $a2 = 0;//Меня добавили

        //Я добавил в друзья
        $this->db->select('*');
        $this->db->where('id_user', $this->login);
        $this->db->where('id_friend', $id);
        $query = $this->db->get('site_users_friends');
        if($query->num_rows()>0){
            $a1 = 1;
        }

        //Меня добавили в друзья
        $this->db->select('*');
        $this->db->where('id_user', $id);
        $this->db->where('id_friend', $this->login);
        $query = $this->db->get('site_users_friends');
        if($query->num_rows()>0){
            $a2 = 1;
        }

        if($a1 == 0 AND $a2 == 0){
            return 0;//Не друзья
        }elseif($a1 == 1 AND $a2 == 1){
            return 3;//Друзья
        }elseif($a1 == 1 AND $a2 == 0){
            return 1;//Я дал запрос
        }elseif($a1 == 0 AND $a2 == 1){
            return 2;//Добавили меня
        }
    }

    /** Мои друзья */
    public function my_friend(){
        $this->db->select('id_friend');
        $this->db->where('id_user', $this->login);
        $this->db->where('status', 2);//Друзья
        $query = $this->db->get('site_users_friends');
        if($query->num_rows()>0) {
            $friend = $query->result_array();
            $friend_id = array();
            foreach($friend as $val){
                $friend_id[] = $val['id_friend'];
            }

            $this->db->select('*');
            $this->db->where_in('id', $friend_id);//Друзья
            $query = $this->db->get('site_users');
            if($query->num_rows()>0) {
                return $query->result_array();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /** Мои запросы в друзья */
    public function i_my_friend(){
        $this->db->select('id_friend');
        $this->db->where('id_user', $this->login);
        $this->db->where('status', 1);//Мои запросы
        $query = $this->db->get('site_users_friends');
        if($query->num_rows()>0) {
            $friend = $query->result_array();
            $friend_id = array();
            foreach($friend as $val){
                $friend_id[] = $val['id_friend'];
            }

            $this->db->select('*');
            $this->db->where_in('id', $friend_id);//Друзья
            $query = $this->db->get('site_users');
            if($query->num_rows()>0) {
                return $query->result_array();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /** Список кто давал запросы в друзья */
    public function request_my_friend(){
        $this->db->select('id_user');
        $this->db->where('id_friend', $this->login);
        $this->db->where('status', 1);//Друзья
        $query = $this->db->get('site_users_friends');
        if($query->num_rows()>0) {
            $friend = $query->result_array();
            $friend_id = array();
            foreach($friend as $val){
                $friend_id[] = $val['id_user'];
            }

            $this->db->select('*');
            $this->db->where_in('id', $friend_id);//Друзья
            $query = $this->db->get('site_users');
            if($query->num_rows()>0) {
                return $query->result_array();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * Данные о пользователе по ID
     * 1р - идентификатор
     */
    public function get_user_id($id){
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get('site_users');
        if($query->num_rows()>0) {
            return $query->row_array();
        }else{
            return false;
        }
    }

    /** Список всех пользователей */
    public function get_users(){
        $this->db->select('*');
        $query = $this->db->get('site_users');
        if($query->num_rows()>0) {
            return $query->result_array();
        }else{
            return false;
        }
    }


    /** ---------------- Правила валидации ---------------- */

    /** Правила валидации для регистрации */
    public function registration_val(){
        $config = array(
            array(
                'field' => 'name',
                'label' => 'Имя',
                'rules' => 'required|min_length[2]|max_length[50]'
            ),
            array(
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'required|valid_email|is_unique[site_users.email]'
            ),
            array(
                'field' => 'password',
                'label' => 'Пароль',
                'rules' => 'required|min_length[3]|max_length[12]'
            ),
            array(
                'field' => 'passconf',
                'label' => 'Пароль еще раз',
                'rules' => 'required|matches[password]|min_length[3]|max_length[12]'
            ),

        );
        return $config;
    }

    /** Правила валидации для регистрации */
    public function login_val()
    {
        $config = array(
            array(
                'field' => 'email',
                'label' => 'E-mail',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Пароль',
                'rules' => 'required|min_length[3]|max_length[12]'
            )

        );
        return $config;
    }

    /** Правила валидации для статуса */
    public function pass_edit_val(){
        $config = array(
            array(
                'field' => 'passold',
                'label' => 'Старый пароль',
                'rules' => 'callback_oldpass_check'//свой метод проверки
            ),
            array(
                'field' => 'password',
                'label' => 'Пароль',
                'rules' => 'required|differs[passold]|min_length[3]|max_length[12]'
            ),
            array(
                'field' => 'passconf',
                'label' => 'Пароль еще раз',
                'rules' => 'required|matches[password]|min_length[3]|max_length[12]'
            ),
        );
        return $config;
    }


}