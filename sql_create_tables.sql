-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS OnlineLibrary
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Sử dụng cơ sở dữ liệu
USE OnlineLibrary;

-- =====================================================
-- BẢNG CATEGORY (bổ sung để liên kết với BOOKS)
-- =====================================================
CREATE TABLE Category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- =====================================================
-- BẢNG USERS
-- =====================================================
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    role ENUM('reader', 'admin', 'author') DEFAULT 'reader',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- BẢNG BOOKS
-- =====================================================
CREATE TABLE Books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category_id INT,
    description TEXT,
    file_url VARCHAR(255),
    cover_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id) REFERENCES Category(category_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =====================================================
-- BẢNG REVIEWS
-- =====================================================
CREATE TABLE Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_user
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_book
        FOREIGN KEY (book_id) REFERENCES Books(book_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
-- =====================================================
-- BẢNG CHAPTERS (BẢNG MỚI DÀNH CHO CHỨC NĂNG ĐỌC SÁCH)
-- =====================================================
CREATE TABLE Chapters (
    chapter_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    chapter_number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Khóa ngoại liên kết tới bảng Booksbooks
    CONSTRAINT fk_chapters_book
        FOREIGN KEY (book_id) REFERENCES Books(book_id)
        ON DELETE CASCADE -- Nếu sách bị xóa, các chương cũng sẽ bị xóa theo
        ON UPDATE CASCADE,
        
    -- Đảm bảo mỗi sách không thể có 2 chương trùng số thứ tự
    CONSTRAINT uq_book_chapter UNIQUE (book_id, chapter_number)
);

-- =====================================================
-- BẢNG SHELFITEMS (TỦ SÁCH CỦA NGƯỜI DÙNG)
-- =====================================================
CREATE TABLE ShelfItems (
    shelf_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    status ENUM('want_to_read','reading','finished') DEFAULT 'want_to_read',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_shelf_user
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_shelf_book
        FOREIGN KEY (book_id) REFERENCES Books(book_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_user_book UNIQUE (user_id, book_id)
);
=======

CREATE TABLE AuthorRequests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    author_name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Liên kết tới bảng Users
    CONSTRAINT fk_request_user
        FOREIGN KEY (user_id) REFERENCES Users(user_id)
        ON DELETE CASCADE,
        
    -- Đảm bảo mỗi user chỉ có 1 yêu cầu 'pending' tại một thời điểm
    -- (Chúng ta sẽ xử lý logic này trong code, nhưng UNIQUE ở đây 
    --  để ngăn 1 user gửi nhiều yêu cầu)
    CONSTRAINT uq_user_id UNIQUE (user_id)
);

ALTER TABLE Users
ADD COLUMN author_name VARCHAR(255) NULL DEFAULT NULL AFTER email;

