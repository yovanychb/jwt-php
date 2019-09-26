<?php

require APPPATH . 'libraries/Rest_Controller.php';
class Tarea extends REST_Controller
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
            $data = $this->db->get_where('tarea', ['id_tarea' => $id])->row_array();
            if ($data == null) {
                $this->response("El registro con ID $id no existe", REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $data = $this->db->get('tarea')->result();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        $datos = [
            'nombre' => $this->post('nombre'),
            'descripcion' => $this->post('descripcion'),
            'duracion' => $this->post('duracion'),
            'estado' => $this->post('estado'),
        ];
        if ($datos != null) {
            $this->db->insert('tarea', $datos);
            $this->db->select_max('id_tarea');
            $id = $this->db->get('tarea')->row_array();
            $data = $this->db->get_where('tarea', ['id_tarea' => $id['id_tarea']])->result();
            $this->response($data, REST_Controller::HTTP_CREATED);
        }
    }

    public function index_put()
    {
        $id = $this->put('id_tarea');
        $datos = [
            'nombre' => $this->put('nombre'),
            'descripcion' => $this->put('descripcion'),
            'duracion' => $this->put('duracion'),
            'estado' => $this->put('estado'),
        ];
        if ($datos != null) {
            $this->db->update('tarea', $datos, ['id_tarea' => $id]);
            $data = $this->db->get_where('tarea', ['id_tarea' => $id])->result();
            $this->response($data, REST_Controller::HTTP_OK);
        }
    }
    
    public function index_delete($id = null)
    {
        if ($id != null) {
            $this->db->delete('tarea', ['id_tarea' => $id]);
            $this->response("Registro eliminado", REST_Controller::HTTP_OK);
        }
    }


}
