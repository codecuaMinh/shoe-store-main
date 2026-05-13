<?php
session_start();
session_destroy();
echo "Session cleared! <a href='/shoe-store-main/'>Back to Home</a>";
?>