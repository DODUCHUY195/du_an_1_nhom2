<?php
// Đường dẫn base cho phần frontend/admin của dự án
// Project của bạn đang nằm ở: D:\laragon\www\du_an_1_nhom2
// => URL trên trình duyệt sẽ là: http://localhost/du_an_1_nhom2/...
// Router chính đang ở thư mục /app nên BASE_URL trỏ vào /app/

// Dùng cho tất cả route trong app (index.php trong /app)
define('BASE_URL', 'http://localhost/du_an_1_nhom2/app/');

// Nếu sau này có tách riêng giao diện admin, có thể đổi lại sau.
// Hiện tại có thể cho ADMIN dùng chung entry /app
define('BASE_URL_ADMIN', 'http://localhost/du_an_1_nhom2/app/');

// Cấu hình database
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');

// Thông tin đăng nhập MySQL (Laragon mặc định)
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');   // nếu bạn có đặt mật khẩu cho MySQL thì sửa ở đây

// Tên database bạn vừa tạo trong phpMyAdmin và import file .sql vào.
// Hiện nhóm đang dùng tên: du_an_test (theo file du_an_test.sql)
// Nếu nhóm trưởng yêu cầu tên khác (ví dụ: du_an_1_nhom2) thì sửa đúng tên đó.
define('DB_NAME', 'du_an_test');

// Đường dẫn vật lý trên máy (dùng cho việc include file, load view, v.v.)
define('PATH_ROOT', __DIR__ . '/../');
define('PATH_VIEW', __DIR__ . '/../views');
