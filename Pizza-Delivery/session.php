<?php

//Se a sessão não estiver iniciada/resumida, inicia/resume 
if (session_status() !== PHP_SESSION_ACTIVE){session_start ();}

//Se logado iniciou com a sessão id e id é = a sessão atual
$logado = isset($_SESSION['id']) && session_id() == $_SESSION ['id'];