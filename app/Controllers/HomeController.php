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
            'messages' => 'Para la lista de los endPoints de la API ',
            'data' => ['https://sprint09.cerolab.com/myLista']
        ];
        return $this->respond($response);
    }

    public function myLista()
    {
        $response = nl2br('<h1>EndPoints de la API: </h1>
+---------+-------------------------------+-----------------------------------------------------------+-----------------+--------------------+
| Method  | Route                         | Handler                                                   | Before Filters  | After Filters      |
+---------+-------------------------------+-----------------------------------------------------------+-----------------+--------------------+
| GET     | /                             | \App\Controllers\HomeController::index                    | cors            | toolbar            |
| GET     | myLista                       | \App\Controllers\HomeController::myLista                  | cors            | toolbar            |
| GET     | test                          | \App\Controllers\HomeController::myLista                  | cors            | toolbar            |
| GET     | datadonut                     | \App\Controllers\GraficosController::dataDonut            | cors            | toolbar            |

| GET     | api/personas/list             | \App\Controllers\Api\PersonaController::personaList       | cors            | toolbar            |
| GET     | api/personas/list/(.*)        | \App\Controllers\Api\PersonaController::personaSearch/$1  | cors            | toolbar            |
| GET     | api/personas/detalle/([0-9]+) | \App\Controllers\Api\PersonaController::personaDetalle/$1 | cors            | toolbar            |
| GET     | api/personas/delete/([0-9]+)  | \App\Controllers\Api\PersonaController::personaDelete/$1  | cors            | toolbar            |
| POST    | api/personas/new              | \App\Controllers\Api\PersonaController::personaNew        | cors            | toolbar            |
| POST    | api/personas/update/([0-9]+)  | \App\Controllers\Api\PersonaController::personaUpdate/$1  | cors            | toolbar            |

| GET     | api/libros/list               | \App\Controllers\Api\PersonaController::personaList       | cors            | toolbar            |
| GET     | api/libros/list/(.*)          | \App\Controllers\Api\PersonaController::personaSearch/$1  | cors            | toolbar            |
| GET     | api/libros/detalle/([0-9]+)   | \App\Controllers\Api\PersonaController::personaDetalle/$1 | cors            | toolbar            |
| GET     | api/libros/delete/([0-9]+)    | \App\Controllers\Api\PersonaController::personaDelete/$1  | authFilter cors | authFilter toolbar |
| POST    | api/libros/new                | \App\Controllers\Api\PersonaController::personaNew        | authFilter cors | authFilter toolbar |
| POST    | api/libros/update/([0-9]+)    | \App\Controllers\Api\PersonaController::personaUpdate/$1  | authFilter cors | authFilter toolbar |

| GET     | api/profile                   | \App\Controllers\Api\UserController::details              | cors            | toolbar            |
| GET     | api/usrList                   | \App\Controllers\Api\UserController::usrList              | authFilter cors | authFilter toolbar |
| POST    | api/register                  | \App\Controllers\Api\UserController::register             | cors            | toolbar            |
| POST    | api/login                     | \App\Controllers\Api\UserController::login                | cors            | toolbar            |

| OPTIONS | (.*)                          | \App\Controllers\                                         | cors cors       | cors toolbar       |
| CLI     | ci(.*)                        | \CodeIgniter\CLI\CommandRunner::index/$1                  |                 |                    |
+---------+-------------------------------+-----------------------------------------------------------+-----------------+--------------------+
');
        return $this->respond($response);
    }
}

