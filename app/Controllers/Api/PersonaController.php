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
		$persona = new PersonaModel();
		$perList = $persona->findAll();

		if ($perList) $listFull = $this->addTelefonos($perList);
		$data = $listFull;

		$response = [
			'status' => 200,
			"error" => false,
			'messages' => 'Lista de personas',
			'data' => $data
		];

		return $this->respond($response);
	}

	/**
	 * personaDetalle($id)
	 * Devuelve el registro de una persona
	 */
	public function personaDetalle($id_persona)
	{
		$persona = new PersonaModel();

		$perList = $persona->find($id_persona);

		if ($perList) $data = $this->addTelefonos($perList);

		if (!empty($data)) {
			$response = [
				'status' => 200,
				"error" => false,
				'messages' => 'Registro de persona con id ' . $id_persona,
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
					'message' => 'Persona no encontrada',
					'data' => []
				];
			}
		}

		return $this->respond($response);
	}

	/**
	 * personaDelete($idPer)
	 */
	public function personaDelete($idPer)
	{
		$emp = new PersonaModel();
		$data = $emp->find($idPer);

		if (!empty($data)) {
			$emp->delete($idPer);
			$response = [
				'status' => 200,
				"error" => false,
				'message' => 'Persona eliminada correctamente',
				'data' => []
			];
		} else {
			$response = [
				'status' => 500,
				"error" => true,
				'message' => "Persona con id: $idPer No encontrda",
				'data' => []
			];
		}

		return $this->respond($response);
	}



	/**
	 * personaSearch($buscar:string)
	 * Devuelve lista de personas que contengan $buscar
	 */
	public function personaSearch($buscar)
	{
		$db = db_connect();

		// $query = $db->query("SELECT * from s9_personas WHERE nombre LIKE '%$buscar%'");

		$q = "SELECT * FROM s9_personas WHERE nombre LIKE '%" . $db->escapeLikeString($buscar) . "%' ESCAPE '!'";
		$query = $db->query($q);
		$data = $query->getResultArray();

		$response = [
			'status' => 200,
			"error" => false,
			'messages' => "Lista de personas conteniendo $buscar",
			'data' => $data
		];
		return $this->respond($response);
	}


	////////////////////// FUNCIONES AUX //////////////////////

	/**
	 * addTelefonos($perList:array[])
	 * Añadir el array de teléfonos al array de personas $perList
	 */
	function addTelefonos($perList)
	{
		if (!$perList) return ([]);
		$persona = new PersonaModel();

		if (isset($perList[0]['id'])) {
			// Hay varias personas en $perList[]
			foreach ($perList as $per) {
				$per += ['telefonos' => []];
			}
			for ($x = 0; $x < count($perList); $x++) {
				$id_persona = $perList[$x]['id'];
				$telList = $persona->getTelefonosPersona($id_persona);
				$conta = 0;
				foreach ($telList as $tel) {
					$perList[$x]['telefonos'][$conta]['label'] = $tel['label'];
					$perList[$x]['telefonos'][$conta]['telefono'] = $tel['telefono'];
					$conta++;
				}
			}
		} else {
			// Solo hay una persona en $perList
			$perList += ['telefonos' => []];
			$id_persona = $perList['id'];
			$telList = $persona->getTelefonosPersona($id_persona);
			$conta = 0;
			foreach ($telList as $tel) {
				$perList['telefonos'][$conta]['label'] = $tel['label'];
				$perList['telefonos'][$conta]['telefono'] = $tel['telefono'];
				$conta++;
			}
		}

		return ($perList);
	}
}
