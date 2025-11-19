<?php
// File: app/Controllers/ChapterController.php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Chapter;
use App\Models\Book;

class ChapterController {

    public function read($id = null) { // Nhận $id làm tham số
        
        // --- SỬA LỖI Ở ĐÂY ---
        // Sử dụng trực tiếp biến $id đã được truyền vào
        $chapterId = (int)$id;

        // Câu lệnh kiểm tra bây giờ sẽ hoạt động đúng
        if ($chapterId <= 0) {
            die("ID chương không hợp lệ hoặc không được cung cấp từ URL.");
        }

        // Toàn bộ phần code còn lại của bạn là ĐÚNG và không cần thay đổi
        $db = Database::getConnection();
        $chapterModel = new Chapter($db);

        $chapter = $chapterModel->findById($chapterId);
        
        if (!$chapter) {
            http_response_code(404);
            die("Không tìm thấy chương này trong cơ sở dữ liệu.");
        }

        $bookModel = new Book($db);
        $bookModel->incrementViews($chapter['book_id']);
        
        $prevChapter = $chapterModel->findPreviousChapter($chapter['book_id'], $chapter['chapter_number']);
        $nextChapter = $chapterModel->findNextChapter($chapter['book_id'], $chapter['chapter_number']);

        require_once ROOT_PATH . '/app/Views/chapters/read.php';
    }
}