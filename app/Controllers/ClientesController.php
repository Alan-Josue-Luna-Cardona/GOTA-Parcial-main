<?php

namespace App\Controllers;

use App\Models\ClientesModel;

class ClientesController extends BaseController
{
    protected $clientesModel;

    public function __construct()
    {
        $this->clientesModel = new ClientesModel();
    }

    // Listado de clientes, por defecto solo los activos
    public function index()
    {
        // El parametro ?mostrar=inactivos permite ver los desactivados tambien
        $mostrarInactivos = $this->request->getGet('mostrar') === 'inactivos';

        if ($mostrarInactivos) {
            $clientes = $this->clientesModel->findAll();
        } else {
            $clientes = $this->clientesModel->where('activo', 1)->findAll();
        }

        return view('clientes/index', [
            'clientes'         => $clientes,
            'mostrarInactivos' => $mostrarInactivos,
        ]);
    }

    // Formulario para crear un cliente nuevo
    public function nuevo()
    {
        return view('clientes/form', [
            'cliente' => null,
        ]);
    }

    // Guarda el cliente nuevo en la base de datos
    public function crear()
    {
        $reglas = [
            'nombre'    => 'required|max_length[100]',
            'direccion' => 'required|max_length[200]',
            'telefono'  => 'permit_empty|max_length[20]',
            'email'     => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($reglas)) {
            return view('clientes/form', [
                'cliente'  => null,
                'errores'  => $this->validator->getErrors(),
            ]);
        }

        // fecha_registro y activo siempre se calculan aqui, nunca desde el formulario,
        // para que nadie los pueda manipular enviando datos ocultos
        $this->clientesModel->insert([
            'nombre'         => $this->request->getPost('nombre'),
            'telefono'       => $this->request->getPost('telefono'),
            'direccion'      => $this->request->getPost('direccion'),
            'email'          => $this->request->getPost('email'),
            'fecha_registro' => date('Y-m-d'),
            'activo'         => 1,
        ]);

        return redirect()->to('/clientes')->with('mensaje', 'Cliente registrado correctamente');
    }

    // Formulario de edicion, precargado con los datos actuales
    public function editar($id)
    {
        $cliente = $this->clientesModel->find($id);

        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado');
        }

        return view('clientes/form', [
            'cliente' => $cliente,
        ]);
    }

    // Actualiza los datos del cliente
    public function actualizar($id)
    {
        $cliente = $this->clientesModel->find($id);

        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado');
        }

        $reglas = [
            'nombre'    => 'required|max_length[100]',
            'direccion' => 'required|max_length[200]',
            'telefono'  => 'permit_empty|max_length[20]',
            'email'     => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($reglas)) {
            return view('clientes/form', [
                'cliente' => $cliente,
                'errores' => $this->validator->getErrors(),
            ]);
        }

        // fecha_registro no se toca en una actualizacion, solo se definio al crear
        $this->clientesModel->update($id, [
            'nombre'    => $this->request->getPost('nombre'),
            'telefono'  => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'email'     => $this->request->getPost('email'),
        ]);

        return redirect()->to('/clientes')->with('mensaje', 'Cliente actualizado correctamente');
    }

    // Soft delete: nunca se borra fisicamente porque Contadores depende de Clientes
    public function desactivar($id)
    {
        $cliente = $this->clientesModel->find($id);

        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado');
        }

        $this->clientesModel->update($id, ['activo' => 0]);

        return redirect()->to('/clientes')->with('mensaje', 'Cliente desactivado correctamente');
    }

    // Reactiva un cliente que había sido desactivado por error.
    // Necesario porque desde el listado con ?mostrar=inactivos se puede
    // ver un cliente inactivo, pero sin esto no había forma de revertirlo.
    public function activar($id)
    {
        $cliente = $this->clientesModel->find($id);

        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente no encontrado');
        }

        $this->clientesModel->update($id, ['activo' => 1]);

        return redirect()->to('/clientes')->with('mensaje', 'Cliente reactivado correctamente');
    }
}