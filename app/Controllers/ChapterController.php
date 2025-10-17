<?php
// File: app/Controllers/ChapterController.php

require_once '../app/Core/Database.php';
require_once '../app/Models/Chapter.php';

class ChapterController {

    public function read() {
        // 1. Lấy ID chương từ URL
        $chapterId = $_GET['id'] ?? null;
        if (!$chapterId) {
            die("ID chương không hợp lệ.");
        }

        // 2. Kết nối CSDL và gọi Model
        $database = new Database();
        $db = $database->getConnection();
        $chapterModel = new Chapter($db);

        // 3. Lấy nội dung chương hiện tại
        $chapter = $chapterModel->findById($chapterId);
        if (!$chapter) {
            die("Không tìm thấy chương này.");
        }

        // 4. Tìm chương trước và chương sau
        $prevChapter = $chapterModel->findPreviousChapter($chapter['book_id'], $chapter['chapter_number']);
        $nextChapter = $chapterModel->findNextChapter($chapter['book_id'], $chapter['chapter_number']);

        // 5. Gọi View để hiển thị, mang theo tất cả dữ liệu đã chuẩn bị
        require_once '../app/Views/chapters/read.php';
    }
}