<?php

require_once '../config/config.php';

logout();

header('Location: ../login.php');
exit;