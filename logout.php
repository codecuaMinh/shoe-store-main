<?php
session_start();
session_destroy();
header("Location: /shoe-store/login.php");
exit;