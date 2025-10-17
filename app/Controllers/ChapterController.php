<?php
// File: app/Controllers/ChapterController.php
namespace App\Controllers;
use App\Core\Database;
use App\Models\Chapter;

class ChapterController {

    public function read() {
        $chapterId = $_GET['id'] ?? null;
        if (!$chapterId) {
            die("ID chương không hợp lệ.");
        }

        // CÁCH KẾT NỐI MỚI - GỌN HƠN VÀ HIỆU QUẢ HƠN
        $db = Database::getConnection();

        // Phần còn lại giữ nguyên
        $chapterModel = new Chapter($db);
        $chapter = $chapterModel->findById($chapterId);
        
        if (!$chapter) {
            die("Không tìm thấy chương này.");
        }

        $prevChapter = $chapterModel->findPreviousChapter($chapter['book_id'], $chapter['chapter_number']);
        $nextChapter = $chapterModel->findNextChapter($chapter['book_id'], $chapter['chapter_number']);

        require_once '../app/Views/chapters/read.php';
    }
}