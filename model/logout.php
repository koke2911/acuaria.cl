<?php

session_start();
session_destroy();
echo "<script>location.href='../pages/login.php';</script>";
