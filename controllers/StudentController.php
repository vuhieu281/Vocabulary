<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Department.php';

class StudentController
{
    private $student;
    private $department;

    public function __construct($db)
    {
        $this->student = new Student($db);
        $this->department = new Department($db);
    }

    public function index()
    {
        $students = $this->student->all();
        include __DIR__ . '/../views/student/student_list.php';
    }

    public function create()
    {
        $departments = $this->department->all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->student->create($_POST);
            header('Location: index.php');
            exit;
        }
        include __DIR__ . '/../views/student/student_form.php';
    }

    public function edit($id)
    {
        $departments = $this->department->all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->student->update($id, $_POST);
            header('Location: index.php');
            exit;
        }
        $student = $this->student->find($id);
        include __DIR__ . '/../views/student/student_form.php';
    }

    public function delete($id)
    {
        $this->student->delete($id);
        header('Location: index.php');
        exit;
    }
}
