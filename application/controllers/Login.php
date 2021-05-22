<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Login extends CI_Controller{

        private $datos;

        function __construct(){
            parent::__construct();
            $this->load->model('UsuariosModel');
        }

        public function index(){
            if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
                $username = $this->input->post('username');
                $password = $this->encryption->encrypt($this->input->post('password'));
                // $data = array("nombreUsuario" => $username);
                #para encriptar el password utiliza la funcion md5($password);
                $usuario = $this->UsuariosModel->login($username,$password);
                if($usuario){
                    $data = array(
                        'id' => $usuario->id,
                        'nombreUsuario' => $usuario->nombreUsuario,
                        'tipo' => $usuario->tipoUsuario,
                        'grupo' => $usuario->grupo,
                        'subgrupo' => $usuario->subgrupo,
                        'login' => true
                    );
                    $this->session->set_userdata($data);
                    header('location: ' . base_url());
                }else {
                    $this->datos['error'] = 'El usuario y contraseña no coinciden';
                    // header('location: ' . base_url() . 'Login/inicioSesion#badpassword.jsp');
                    $this->inicioSesion();
                }
            }else {
                header('location: ' . base_url() . 'Login/inicioSesion');
            } 
        }

        public function inicioSesion(){
            $this->load->view('overall/header');
            $this->load->view('overall/nav');
            $this->load->view('login/login',$this->datos);
            $this->load->view('overall/footer');
        }

        public function logout(){
            $this->session->sess_destroy();
            header('location: ' . base_url() . 'home');
        }
    }
?>