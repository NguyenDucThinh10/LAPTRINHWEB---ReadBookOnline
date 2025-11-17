<?php
// File: app/Controllers/Admin/BookController.php
// (Hãy chắc chắn đường dẫn và tên file là chính xác)

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
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
     * (R)EAD: Hiển thị danh sách tất cả sách
     * (Được gọi bởi: GET /admin/books)
     */
    public function index()
    {
        $bookModel = new Book($this->db);
        $books = $bookModel->getAllBooks();
        
        return $this->view('admin/books/index', [
            'books' => $books
        ]);
    }

    /**
     * (U)PDATE - Part 1: Hiển thị form sửa sách
     * (Được gọi bởi: GET /admin/books/edit)
     */
    public function edit()
    {
        if (!isset($_GET['id'])) {
            header('Location: /admin/books');
            exit;
        }

        $bookId = $_GET['id'];
        
        $bookModel = new Book($this->db);
        $book = $bookModel->findById($bookId);

        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAll();

        if (!$book) {
            header('Location: /admin/books'); // Không tìm thấy sách
            exit;
        }

        return $this->view('admin/books/edit', [
            'book' => $book,
            'categories' => $categories
        ]);
    }

    /**
     * (U)PDATE - Part 2: Xử lý cập nhật
     * (Được gọi bởi: POST /admin/books/update)
     */
    public function update()
    {
        if (empty($_POST['book_id']) || empty($_POST['title'])) {
            header('Location: /admin/books?error=missing_data');
            exit;
        }

        $bookId = $_POST['book_id'];
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category_id' => $_POST['category_id']
        ];

        $bookModel = new Book($this->db);
        $bookModel->update($bookId, $data);

        header('Location: /admin/books?status=book_updated');
        exit;
    }

    /**
     * (D)ELETE: Xử lý xóa sách
     * (Được gọi bởi: POST /admin/books/delete)
     */
    public function delete()
    {
        if (empty($_POST['book_id'])) {
            header('Location: /admin/books?error=missing_data');
            exit;
        }
        
        $bookId = $_POST['book_id'];

        $bookModel = new Book($this->db);
        $bookModel->delete($bookId);

        header('Location: /admin/books?status=book_deleted');
        exit;
    }
}