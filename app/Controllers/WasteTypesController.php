<?php

namespace App\Controllers;

use App\Models\WasteTypesModel;
use CodeIgniter\Controller;

class WasteTypesController extends Controller {
    protected $wasteTypesModel;

    public function __construct() {
        $this->wasteTypesModel = new WasteTypesModel();
    }

    public function index() {
        $data['wasteTypes'] = $this->wasteTypesModel->findAll();
        return view('waste_types/index', $data);
    }

    public function create() {
        if ($this->request->getMethod() === 'post') {
            $validation = $this->validate($this->wasteTypesModel->validationRules);
            if ($validation) {
                $this->wasteTypesModel->save($this->request->getPost());
                return redirect()->to('/waste_types')->with('success', 'Waste type created successfully.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }
        return view('waste_types/create');
    }

    public function edit($id) {
        $wasteType = $this->wasteTypesModel->find($id);
        if (!$wasteType) {
            return redirect()->to('/waste_types')->with('error', 'Waste type not found.');
        }

        if ($this->request->getMethod() === 'post') {
            $validation = $this->validate($this->wasteTypesModel->validationRules);
            if ($validation) {
                $this->wasteTypesModel->update($id, $this->request->getPost());
                return redirect()->to('/waste_types')->with('success', 'Waste type updated successfully.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('waste_types/edit', ['wasteType' => $wasteType]);
    }

    public function delete($id) {
        $this->wasteTypesModel->delete($id);
        return redirect()->to('/waste_types')->with('success', 'Waste type deleted successfully.');
    }
}
