<?php
// File: app/Controllers/Admin/CategoryController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Category;

class CategoryController extends Controller
{
    private $db;

    // Bảo mật: Đảm bảo chỉ Admin mới vào được
    public function __construct()
    {
        if (!isset($_SESSION['role']) || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        $this->db = Database::getConnection();
    }

    /**
     * (R)EAD: Hiển thị danh sách và form thêm mới
     */
    public function index()
    {
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAll();
        
        return $this->view('admin/categories/index', [
            'categories' => $categories
        ]);
    }

    /**
     * (C)REATE: Xử lý lưu thể loại mới
     */
    public function store()
    {
        if (empty($_POST['name'])) {
            header('Location: /admin/categories?error=missing_name');
            exit;
        }

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? ''
        ];

        $categoryModel = new Category($this->db);
        $categoryModel->create($data);

        header('Location: /admin/categories?status=created');
        exit;
    }

    /**
     * (U)PDATE - Part 1: Hiển thị form sửa
     */
    public function edit()
    {
        if (!isset($_GET['id'])) {
            header('Location: /admin/categories');
            exit;
        }

        $categoryModel = new Category($this->db);
        $category = $categoryModel->findById($_GET['id']);

        if (!$category) {
            header('Location: /admin/categories');
            exit;
        }

        return $this->view('admin/categories/edit', [
            'category' => $category
        ]);
    }

    /**
     * (U)PDATE - Part 2: Xử lý cập nhật
     */
    public function update()
    {
        if (empty($_POST['category_id']) || empty($_POST['name'])) {
            header('Location: /admin/categories?error=missing_data');
            exit;
        }

        $categoryId = $_POST['category_id'];
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? ''
        ];

        $categoryModel = new Category($this->db);
        $categoryModel->update($categoryId, $data);

        header('Location: /admin/categories?status=updated');
        exit;
    }

    /**
     * (D)ELETE: Xử lý xóa
     */
    public function delete()
    {
        if (empty($_POST['category_id'])) {
            header('Location: /admin/categories?error=missing_data');
            exit;
        }
        
        $categoryModel = new Category($this->db);
        $categoryModel->delete($_POST['category_id']);

        header('Location: /admin/categories?status=deleted');
        exit;
    }
}