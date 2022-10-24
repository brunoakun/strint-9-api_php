<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class HomeController extends ResourceController
{
    public function index()
    {
        $response = [
            'status' => 500,
            "error" => true,
            'messages' => 'llamada como api/  ',
            'data' => ['(get) employee/list','(post) employee/add','(put) employee/update/num','...']
        ];
        return $this->respond($response);
    }

    public function myLista()
    {
        $response = [
            'status' => 200,
            "error" => false,
            'messages' => 'Hola Lista',
            'data' => []
        ];
        return $this->respond($response);
    }
}

