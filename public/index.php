<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/StudentController.php';
require_once __DIR__ . '/../controllers/DepartmentController.php';

$db = (new Database())->getConnection();
$studentCtrl = new StudentController($db);
$deptCtrl = new DepartmentController($db);

$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        $studentCtrl->create();
        break;
    case 'edit':
        $studentCtrl->edit($id);
        break;
    case 'delete':
        $studentCtrl->delete($id);
        break;

    case 'departments':
        $deptCtrl->index();
        break;
    case 'create_department':
        $deptCtrl->create();
        break;
    case 'edit_department':
        $deptCtrl->edit($id);
        break;
    case 'delete_department':
        $deptCtrl->delete($id);
        break;

    default:
        $studentCtrl->index();
        break;
}
