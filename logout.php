<?php
//Tilda Källström 2022
session_start();
session_destroy();
header("Location: index.php");
