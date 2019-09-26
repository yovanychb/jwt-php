<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Application extends REST_Controller
{
    public function __construct()
    {
        parent::__construct('rest');
        $this->load->helper(['jwt', 'authorization']);
    }

    public function index_get()
    {
        $tokenData = 'Hello World!';
        $token = AUTHORIZATION::generateToken($tokenData);
        $status = parent::HTTP_OK;
        $response = ['status' => $status, 'token' => $token];
        $this->response($response, $status);
    }

    public function index_post()
    {
        $user = [
            'username' => $this->post('username'),
            'password' => $this->post('password')
        ];
        $this->db->where('username', $this->post('username'));
        $this->db->where('password', $this->post('password'));
        $usuario = $this->db->get('usuarios')->row();

        if ($usuario != null) {
            $token = AUTHORIZATION::generateToken(['username' => $user['username']]);
            $status = parent::HTTP_OK;
            $response = ['status' => $status, 'token' => $token];
            $this->response($response, $status);
        } else {
            $this->response(['msg' => 'Invalid username or password!'], parent::HTTP_NOT_FOUND);
        }
    }

    private function verify_request()
    {
        $headers = $this->input->request_headers();
        $token = $headers['Authorization'];
        try {
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
                $this->response($response, $status);
            } else {
                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'token' => $data];
                $this->response($response, $status);
            }
        } catch (Exception $e) {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            $this->response($response, $status);
        }
    }

    public function get_me_data_post()
    {
        $data = $this->verify_request();
    }
}
