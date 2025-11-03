<?php
require_once __DIR__ . '/../models/Department.php';

class DepartmentController
{
    private $department;

    public function __construct($db)
    {
        $this->department = new Department($db);
    }

    public function index()
    {
        $departments = $this->department->all();
        include __DIR__ . '/../views/department/department_list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->department->create($_POST['name']);
            header('Location: index.php?action=departments');
            exit;
        }
        include __DIR__ . '/../views/department/department_form.php';
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->department->update($id, $_POST['name']);
            header('Location: index.php?action=departments');
            exit;
        }
        $dept = $this->department->find($id);
        include __DIR__ . '/../views/department/department_form.php';
    }

    public function delete($id)
    {
        $this->department->delete($id);
        header('Location: index.php?action=departments');
        exit;
    }
}
