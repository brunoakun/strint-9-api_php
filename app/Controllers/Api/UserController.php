<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use \App\Validation\CustomRules;

class UserController extends ResourceController
{

    /**
     * register(email,password)
     */

    public function register()
    {
        /*
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS");
*/
        $rules = [
            //     "name" => "required",
            "email" => "required|valid_email|is_unique[users.email]|min_length[6]",
            //       "phone_no" => "required|mobileValidation[phone_no]",
            "password" => "required",
            //        "user_level" => "required",
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
                "required" => "Phone Number is required",
                "mobileValidation" => "Phone is not in format"
            ],
            "password" => [
                "required" => "password is required"
            ],
            "user_level" => [
                "required" => "user_level is required"
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

            $userModel = new UserModel();

            // Userlevel por defecto
            $userLevel = $this->request->getVar("user_level");
            if (!$userLevel) $userLevel = '1';

            $data = [
                "name" => $this->request->getVar("name"),
                "email" => $this->request->getVar("email"),
                "user_level" => $userLevel,
                "phone_no" => $this->request->getVar("phone_no"),
                "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
            ];

            if ($userModel->insert($data)) {

                $response = [
                    'status' => 200,
                    "error" => false,
                    'message' => 'Successfully, user has been registered',
                    'data' => []
                ];
            } else {

                $response = [
                    'status' => 500,
                    "error" => true,
                    'message' => 'Failed to create user',
                    'data' => []
                ];
            }
        }

        return $this->respond($response);
    }


    /**
     * logIn()
     */
    public function login()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS");

        $rules = [
            "email" => "required|valid_email|min_length[6]",
            "password" => "required",
        ];

        $messages = [
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "password" => [
                "required" => "password is required"
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
            $userModel = new UserModel();

            $userdata = $userModel->where("email", $this->request->getVar("email"))->first();

            if (!empty($userdata)) {

                if (password_verify($this->request->getVar("password"), $userdata['password'])) {

                    $key = getenv('JWT_SECRET');

                    $iat = time(); // current timestamp value
                    $nbf = $iat + 1;
                    $exp = $iat + 3600; // 1 hora

                    $payload = array(
                        "iat" => $iat, // issued at
                        "nbf" => $nbf, // not before in seconds
                        "exp" => $exp, // expire time in seconds
                        "data" => array(
                            'id' => $userdata['id'],
                            'email' => $userdata['email'],
                            'role' => $userdata['user_level'],
                        ),
                    );

                    $token = JWT::encode($payload, $key, 'HS256');
                    $response = [
                        'status' => 200,
                        'error' => false,
                        'message' => 'User logged In successfully',
                        'data' => [
                            'token' => $token
                        ]
                    ];
                } else {

                    $response = [
                        'status' => 500,
                        'error' => true,
                        'message' => 'Credenciales incorrectas',
                        'data' => []
                    ];
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => true,
                    'message' => 'Usuario no encontrado',
                    'data' => []
                ];
            }
        }
        return $this->respond($response);
    }


    /**
     * details(token)
     */
    public function details()
    {
        $key = getenv('JWT_SECRET');

        try {
            //$header = $this->request->getHeader("Authorization");
            $token = $this->request->getServer("HTTP_AUTHORIZATION");
            $token = str_replace('Bearer ', '', $token);
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            if ($decoded) {
                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'User details',
                    'data' => [
                        'profile' => $decoded->data
                    ]
                ];
            }
        } catch (Exception $ex) {
            $response = [
                'status' => 401,
                'error' => true,
                'message' => 'Access denied',
                'data' => []
            ];
        }
        return $this->respond($response);
    }


    /**
     * usrList()
     * Lista todos los usuarios -  Requiere token en el header
     */
    public function usrList()
    {
        $key = getenv('JWT_SECRET');
        $list = new UserModel();

        try {
            //log_message('error', $e->getMessage());
            $token = $this->request->getServer("HTTP_AUTHORIZATION");
            $token = str_replace('Bearer ', '', $token);
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            if ($decoded) {
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'usr List',
                    'Environment' => ENVIRONMENT,
                    'data' => $list->findAll()
                ];
            }
        } catch (Exception $ex) {
            $response = [
                'status' => 401,
                'error' => true,
                'message' => 'Access denied',
                'data' => ["{'ex':$ex}"]
            ];
        }

        return $this->respond($response);
    }


    /**
     * existeEmail($buscar:string)
     * return: bool
     */
    public function existeemail($buscar)
    {
        $user = new UserModel();
        $count = $user
            ->where('email', $buscar)
            ->countAllResults();

        $response = [
            'status' => 200,
            "error" => false,
            'messages' => "comprobar si existe eMail $buscar",
            'data' => $count
        ];
        return $this->respond($response);
    }
}
