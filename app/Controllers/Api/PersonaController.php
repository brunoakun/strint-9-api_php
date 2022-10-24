<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PersonaModel;

class PersonaController extends ResourceController
{
	public function addPersona()
	{
		$rules = [
			"nombre" => "required",
			"email" => "required|valid_email|is_unique[Personas.email]|min_length[6]",
			"salario" => "required",
		];

		$messages = [
			"nombre" => [
				"required" => "Nombre obigatorio"
			],
			"email" => [
				"required" => "Email obigatorio",
				"valid_email" => "El eMail no parece válido",
				"is_unique" => "Este eMail ya existe"
			],
			"salario" => [
				"required" => "Salario obligatorio"
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

			$emp = new PersonaModel();

			$data['nombre'] = $this->request->getVar("nombre");
			$data['email'] = $this->request->getVar("email");
			$data['salario'] = $this->request->getVar("salario");

			$emp->save($data);

			$response = [
				'status' => 200,
				'error' => false,
				'message' => 'Persona añadida correctamente',
				'data' => []
			];
		}

		return $this->respond($response);
	}


	/**
	 * personaList()
	 * Listar toda la tabla de personas
	 */
	public function personaList()
	{
		$emp = new PersonaModel();
		$response = [
			'status' => 200,
			"error" => false,
			'messages' => 'Lista de personas',
			'data' => $emp->findAll()
		];

		return $this->respond($response);
	}

	/**
	 * personaDetalle($id)
	 * Devuelve el registro de una persona
	 */
	public function personaDetalle($idPer)
	{		
		$persona = new PersonaModel();
		$data = $persona->find($idPer);

		if (!empty($data)) {
			$response = [
				'status' => 200,
				"error" => false,
				'messages' => 'Registro de persona con id ' . $idPer,
				'data' => $data
			];
		} else {
			$response = [
				'status' => 500,
				"error" => true,
				'messages' => 'Id no encontrado',
				'data' => []
			];
		}
		
		return $this->respond($response);
	}


	/**
	 * updatePersona($idPer)
	 */
	public function updatePersona($idPer)
	{
		$rules = [
			"nombre" => "required",
			"email" => "required|valid_email|min_length[6]",
			"salario" => "required",
		];

		$messages = [
			"nombre" => [
				"required" => "Nombre is required"
			],
			"email" => [
				"required" => "Email required",
				"valid_email" => "Email address is not in format"
			],
			"salario" => [
				"required" => "Salario is required"
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

			$emp = new PersonaModel();

			if ($emp->find($idPer)) {

				$data['nombre'] = $this->request->getVar("nombre");
				$data['email'] = $this->request->getVar("email");
				$data['salario'] = $this->request->getVar("salario");

				$emp->update($idPer, $data);

				$response = [
					'status' => 200,
					'error' => false,
					'message' => 'Persona updated successfully',
					'data' => []
				];
			} else {
				$response = [
					'status' => 500,
					"error" => true,
					'messages' => 'Persona no encontrada',
					'data' => []
				];
			}
		}

		return $this->respond($response);
	}

	/**
	 * deletePersona($idPer)
	 */
	public function deletePersona($idPer)
	{
		$emp = new PersonaModel();

		$data = $emp->find($idPer);

		if (!empty($data)) {
			$emp->delete($idPer);
			$response = [
				'status' => 200,
				"error" => false,
				'messages' => 'Persona deleted successfully',
				'data' => []
			];
		} else {
			$response = [
				'status' => 500,
				"error" => true,
				'messages' => "Persona con id: $idPer No encontrda",
				'data' => []
			];
		}

		return $this->respond($response);
	}

	////////////////////////////////////////////

	public function list2()
	{
		$emp = new PersonaModel();
		$db = db_connect();

		$query   = $db->query('SELECT * from s9_personas WHERE id=2');
		$results = $query->getResultArray();

		$response = [
			'status' => 200,
			"error" => false,
			'messages' => 'Lista de personas con id=2',
			'data' => $results
		];
		return $this->respond($response);
	}
}
