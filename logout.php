<?php
session_start();
session_destroy();
header("Location: /shoe-store-main/login.php");
exit;