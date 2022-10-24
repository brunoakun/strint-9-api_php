<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BookModel;

class BookController extends ResourceController
{
    /*
    public function addBook()
    {
        $rules = [
            "isbn" => "required",
            "title" => "required",
            "description" => "required | min_length[6]"
        ];

        $messages = [
            "isbn" => [
                "required" => "isbn is required"
            ],
            "title" => [
                "required" => "title required" 
            ],
            "description" => [
                "required" => "description is required",
                "min_length" => "description mínimo 6 caracteres",
            ],
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {

            $emp = new BookModel();

            $data['name'] = $this->request->getVar("name");
            $data['email'] = $this->request->getVar("email");
            $data['phone_no'] = $this->request->getVar("phone_no");

            $emp->save($data);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Book added successfully',
                'data' => []
            ];
        }

        return $this->respond($response);
    }
*/
    public function listBook()
    {
        $emp = new BookModel();

        //log_message('error', $e->getMessage());

        $response = [
            'status' => 200,
            "error" => false,
            'messages' => 'Book list',
            'Environment' => ENVIRONMENT,
            'data' => $emp->findAll()
        ];

        return $this->respond($response);
    }

    
    public function showBook($id_book)
    {
        $emp = new BookModel();

        $data = $emp->find($id_book);
        //$data = $model->where(['id' => $emp_id])->first();

        if (!empty($data)) {

            $response = [
                'status' => 200,
                "error" => false,
                'messages' => 'Single Book data',
                'data' => $data
            ];
        } else {

            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'No Book found',
                'data' => []
            ];
        }

        return $this->respond($response);
    }
    

    public function updateBook($emp_id)
    {
        $rules = [
            "name" => "required",
            "email" => "required|valid_email|min_length[6]",
            "phone_no" => "required",
        ];

        $messages = [
            "name" => [
                "required" => "Name is required"
            ],
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "phone_no" => [
                "required" => "Phone Number is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {

            $emp = new BookModel();

            if ($emp->find($emp_id)) {

                $data['name'] = $this->request->getVar("name");
                $data['email'] = $this->request->getVar("email");
                $data['phone_no'] = $this->request->getVar("phone_no");

                $emp->update($emp_id, $data);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Book updated successfully',
                    'data' => []
                ];
            } else {

                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'No Book found',
                    'data' => []
                ];
            }
        }

        return $this->respond($response);
    }
/*
    public function deleteBook($emp_id)
    {
        $emp = new BookModel();

        $data = $emp->find($emp_id);

        if (!empty($data)) {

            $emp->delete($emp_id);

            $response = [
                'status' => 200,
                "error" => false,
                'messages' => 'Book deleted successfully',
                'data' => []
            ];
        } else {

            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'No Book found',
                'data' => []
            ];
        }

        return $this->respond($response);
    }
    */
}
