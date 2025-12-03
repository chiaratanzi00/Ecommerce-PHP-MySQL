<?php

    require 'db.php';

    // se il form è stato inviato tramite il metodo POST

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Recupero campi e li pulisco
    $nome = trim($_POST['nome']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);

     
    // Validazione base
    if (empty($nome) || empty($telefono) || empty($email)) {
        die("Tutti i campi sono obbligatori. <a href='aggiungi_contatto.php'>Torna indietro</a>");
    }

    // Prepared statement per sicurezza
    $stmt = $conn->prepare("INSERT INTO contatti (nome, telefono, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $telefono, $email);

    // Esecuzione e redirect
    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        die("Errore durante l'inserimento: " . $stmt->error);
    }
}

?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi contatto</title>
    <link rel="stylesheet" href="style.css?v<?= time() ?>">
</head>
<body>

    <div class="container">

    <h1>Aggiungi Contatto</h1>

    <form method="POST">

        <label>Nome:</label>
        <input name="nome" type="text" required>

        <label>Telefono:</label>
        <input name="telefono" type="text" required>

        <label>Email:</label>
        <input name="email" type="email" required>

        <button type="submit" class="buttonSave">Salva</button>

    </form>

    <a href="index.php" class="button">Torna alla lista</a>

</div>

</body>
</html>