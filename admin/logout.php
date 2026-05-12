<?php
session_start();
session_destroy();
header("Location: /shoe-store-main/admin/login.php");
exit;