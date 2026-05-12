<?php
session_start();
session_destroy();
echo "Session đã xóa! <a href='/shoe-store/'>Về trang chủ</a>";
?>