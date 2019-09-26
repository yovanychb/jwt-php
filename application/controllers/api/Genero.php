<?php

require APPPATH . 'libraries/Rest_Controller.php';
class Genero extends REST_Controller
{
    public function __construct()
    {
        parent::__construct('rest');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header("Access-Control-Allow-Headers: *");
    }

    public function index_options()
    {
        return $this->response(null, REST_Controller::HTTP_OK);
    }

    public function index_get($id = null)
    {
        if ($id != null || !empty($id)) {
            $data = $this->db->get_where('genero', ['id_genero' => $id])->row_array();
            if ($data == null) {
                $this->response("El registro con ID $id no existe", REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $data = $this->db->get('genero')->result();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        $datos = [
            'titulo' => $this->post('titulo'),
            ];
        if ($datos != null) {
            $this->db->insert('genero', $datos);
            $this->db->select_max('id_genero');
            $id = $this->db->get('genero')->row_array();
            $data = $this->db->get_where('genero', ['id_genero' => $id['id_genero']])->result();
            $this->response($data, REST_Controller::HTTP_CREATED);
        }
    }

    public function index_put()
    {
        $id = $this->put('id_genero');
        $datos = [
            'titulo' => $this->post('titulo'),
            ];
        if ($datos != null) {
            $this->db->update('genero', $datos, ['id_genero' => $id]);
            $data = $this->db->get_where('genero', ['id_genero' => $id])->result();
            $this->response($data, REST_Controller::HTTP_OK);
        }
    }
    
    public function index_delete($id = null)
    {
        if ($id != null) {
            $this->db->delete('genero', ['id_genero' => $id]);
            $this->response("Registro eliminado", REST_Controller::HTTP_OK);
        }
    }


}
