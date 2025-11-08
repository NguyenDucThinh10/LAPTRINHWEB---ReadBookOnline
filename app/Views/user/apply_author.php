<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký Tác giả</title>
    <style>
        :root { --accent-color: #C5A992; --light-color: #F3F2EC; --body-font: "Raleway", sans-serif; --heading-font: "Prata", Georgia, serif; }
        body { font-family: var(--body-font); background-color: var(--light-color); color: #757575; margin: 40px 0; }
        .container { max-width: 700px; margin: 0 auto; background-color: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 5px 25px rgba(0,0,0,0.07); }
        h1 { font-family: var(--heading-font); color: #333; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #ddd; box-sizing: border-box; }
        .btn-primary { background-color: var(--accent-color); color: white; padding: 12px 25px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Trở thành Tác giả</h1>
        <p>Để đăng tải tác phẩm của mình, vui lòng nhập bút danh bạn muốn sử dụng. Yêu cầu của bạn sẽ được quản trị viên xem xét.</p>
        
        <form action="/account/apply-author" method="POST">
            <div class="form-group">
                <label for="pen_name">Bút danh (Pen Name)</label>
                <input type="text" id="pen_name" name="pen_name" placeholder="Ví dụ: Nguyễn Văn A" required>
            </div>
            <button type="submit" class="btn-primary">Gửi yêu cầu</button>
        </form>
    </div>
</body>
</html>