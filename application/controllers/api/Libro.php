<?php

require APPPATH . 'libraries/Rest_Controller.php';
class Libro extends REST_Controller
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
            $this->db->select('l.isbn,l.titulo,l.autor,g.titulo as genero');
            $this->db->join('genero g','g.id_genero = l.genero');
            $data = $this->db->get_where('libro l', ['isbn' => $id])->row_array();
            if ($data == null) {
                $this->response("El registro con ID $id no existe", REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->db->select('l.isbn,l.titulo,l.autor,g.titulo as genero');
            $this->db->join('genero g','g.id_genero = l.genero');
            $data = $this->db->get('libro l')->result();
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        $datos = [
            'isbn' => $this->post('isbn'),
            'titulo' => $this->post('titulo'),
            'autor' => $this->post('autor'),
            'genero' => $this->post('genero'),
        ];
        if ($datos != null) {
            $this->db->insert('libro', $datos);
            $this->db->select_max('isbn');
            $id = $this->db->get('libro')->row_array();
            $data = $this->db->get_where('libro', ['isbn' => $id['isbn']])->result();
            $this->response($data, REST_Controller::HTTP_CREATED);
        }
    }

    public function index_put()
    {
        $id = $this->put('isbn');
        $datos = [
            'titulo' => $this->put('titulo'),
            'autor' => $this->put('autor'),
            'genero' => $this->put('genero'),
        ];
        if ($datos != null) {
            $this->db->update('libro', $datos, ['isbn' => $id]);
            $data = $this->db->get_where('libro', ['isbn' => $id])->result();
            $this->response($data, REST_Controller::HTTP_OK);
        }
    }
    
    public function index_delete($id = null)
    {
        if ($id != null) {
            $this->db->delete('libro', ['isbn' => $id]);
            $this->response("Registro eliminado", REST_Controller::HTTP_OK);
        }
    }


}
