<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Auth extends REST_Controller
{
    public function __construct()
    {
        parent::__construct('rest');
        $this->load->helper(['jwt', 'authorization']);
    }


    public function login_post()
    {
        $this->db->where('username', $this->post('username'));
        $this->db->where('password', $this->post('password'));
        $usuario = $this->db->get('usuarios')->row();

        if ($usuario != null) {
            $token = AUTHORIZATION::generateToken(['username' => $this->post('username')]);
            $status = parent::HTTP_OK;
            $response = ['status' => $status, 'token' => $token];
            $this->response($response, $status);
        } else {
            $this->response(['msg' => 'Usuario o contraseña ivalido!'], parent::HTTP_NOT_FOUND);
        }
    }

    protected function verify()
    {
        $headers = $this->input->request_headers();
        $token = $headers['Authorization'];
        try {
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
