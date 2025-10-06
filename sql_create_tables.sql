-- ================================
-- 0) DATABASE & SETTINGS
-- ================================
CREATE DATABASE IF NOT EXISTS online_book_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;
USE online_book_db;

-- ================================
-- 1) USERS
-- ================================
CREATE TABLE users (
  user_id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(50)  NOT NULL,
  email          VARCHAR(255) NOT NULL,
  password_hash  VARCHAR(255) NOT NULL,
  role           ENUM('reader','admin','author') NOT NULL DEFAULT 'reader',
  is_active      TINYINT(1) NOT NULL DEFAULT 1,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT uq_users_username UNIQUE (username),
  CONSTRAINT uq_users_email    UNIQUE (email)
) ENGINE=InnoDB;

-- ================================
-- 2) CATEGORIES
-- ================================
CREATE TABLE categories (
  category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  -- slug tự sinh từ name (đơn giản): "khoa hoc" -> "khoa-hoc"
  slug        VARCHAR(120) GENERATED ALWAYS AS (REPLACE(LOWER(name),' ','-')) STORED,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_categories_name UNIQUE (name)
) ENGINE=InnoDB;

-- ================================
-- 3) BOOKS
-- ================================
CREATE TABLE books (
  book_id      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title        VARCHAR(255) NOT NULL,
  author       VARCHAR(255) NULL,
  category_id  INT UNSIGNED NULL,
  description  TEXT NULL,
  file_url     VARCHAR(512) NULL,     -- đường dẫn PDF/EPUB (nếu có)
  cover_image  VARCHAR(512) NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_by   BIGINT UNSIGNED NULL,  -- người tạo (admin/author)
  published_at DATETIME NULL,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_books_title (title),
  INDEX idx_books_category (category_id),

  CONSTRAINT fk_books_category
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
    ON UPDATE CASCADE ON DELETE SET NULL,

  CONSTRAINT fk_books_created_by
    FOREIGN KEY (created_by) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ================================
-- 4) BOOK_CONTENT (Chapters)
-- ================================
CREATE TABLE book_content (
  content_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  book_id    BIGINT UNSIGNED NOT NULL,
  chapter_no INT UNSIGNED NOT NULL,   -- số chương >= 1
  title      VARCHAR(255) NULL,
  content    LONGTEXT NULL,           -- nội dung chương (text dài)
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT uq_book_chapter UNIQUE (book_id, chapter_no),
  CONSTRAINT chk_chapter_no CHECK (chapter_no >= 1),

  CONSTRAINT fk_content_book
    FOREIGN KEY (book_id) REFERENCES books(book_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================================
-- 5) REVIEWS (Đánh giá)
--  - Mỗi user chỉ được review 1 lần/1 sách (UNIQUE)
--  - rating 1..5 (CHECK)
-- ================================
CREATE TABLE reviews (
  review_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    BIGINT UNSIGNED NOT NULL,
  book_id    BIGINT UNSIGNED NOT NULL,
  rating     TINYINT UNSIGNED NOT NULL,
  comment    TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT uq_review_once UNIQUE (user_id, book_id),
  CONSTRAINT chk_rating_range CHECK (rating BETWEEN 1 AND 5),

  CONSTRAINT fk_reviews_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT fk_reviews_book
    FOREIGN KEY (book_id) REFERENCES books(book_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ================================
-- 6) USER_BOOK (Tủ sách cá nhân)
--  - Trạng thái: muon_doc / dang_doc / da_doc
--  - Không trùng 1 user - 1 book
-- ================================
CREATE TABLE user_book (
  id        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id   BIGINT UNSIGNED NOT NULL,
  book_id   BIGINT UNSIGNED NOT NULL,
  status    ENUM('muon_doc','dang_doc','da_doc') NOT NULL DEFAULT 'muon_doc',
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT uq_user_book UNIQUE (user_id, book_id),

  CONSTRAINT fk_user_book_user
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT fk_user_book_book
    FOREIGN KEY (book_id) REFERENCES books(book_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB
COMMENT='status: muon_doc=muốn đọc, dang_doc=đang đọc, da_doc=đã đọc';

-- ================================
-- 7) FULLTEXT INDEXES (tìm kiếm nhanh)
-- ================================
-- InnoDB hỗ trợ FULLTEXT trên utf8mb4 (MySQL 5.6+)
ALTER TABLE books
  ADD FULLTEXT ft_books (title, author, description);

ALTER TABLE book_content
  ADD FULLTEXT ft_content (title, content);

-- ================================
-- 8) TRIGGERS (Logic ràng buộc nâng cao)
--  - Chỉ cho phép user có role 'admin' hoặc 'author' gán vào books.created_by
-- ================================
DELIMITER //

CREATE TRIGGER trg_books_check_creator_ins
BEFORE INSERT ON books
FOR EACH ROW
BEGIN
  IF NEW.created_by IS NOT NULL THEN
    IF (SELECT role FROM users WHERE user_id = NEW.created_by) NOT IN ('admin','author') THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'created_by must be a user with role admin or author';
    END IF;
  END IF;
END//
  
CREATE TRIGGER trg_books_check_creator_upd
BEFORE UPDATE ON books
FOR EACH ROW
BEGIN
  IF NEW.created_by IS NOT NULL THEN
    IF (SELECT role FROM users WHERE user_id = NEW.created_by) NOT IN ('admin','author') THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'created_by must be a user with role admin or author';
    END IF;
  END IF;
END//

DELIMITER ;
