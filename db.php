<?php

    //CONNESSIONE AL DATABASE MySQL USANDO MYSQLI

    //PARAMETRI DI CONNESSIONE AL DATABASE

    $host = "localhost";        //HOSTNAME
    $user = "root";            //USERNAME
    $password = "";           //PASSWORD (viene chiesta durante installazione XAMPP)
    $database = "ecommerce"; //NOME DATABASE di phpmyadmin
    
    //CREAZIONE DELLA CONNESSIONE

    $conn = new mysqli($host, $user, $password, $database);

    //CONTROLLO DELLA CONNESSIONE

    if ($conn->connect_error) {
    die("Errore di connessione al database: " . $conn->connect_error);
    }


    //  Imposta charset UTF-8 (per evitare problemi con accenti e caratteri speciali)
    $conn->set_charset("utf8mb4");

?>


    