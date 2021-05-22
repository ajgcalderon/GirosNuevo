<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Temporal extends CI_Controller{
        public function index(){
            header("location: " . base_url() . 'Login/logout');
        }
    }
?>  