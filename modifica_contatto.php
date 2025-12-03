<?php

    // importo la connessione col file db (db.php)
    require 'db.php';

    // controllo se il form è stato inviato tramite POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $id = intval($_POST['id']);
        $nome = trim($_POST['nome']);
        $telefono = trim($_POST['telefono']);
        $email = trim($_POST['email']);

       
    // Validazione base
    if (!$id || empty($nome) || empty($telefono) || empty($email)) {
        die("Errore: tutti i campi sono obbligatori. <a href='index.php'>Torna indietro</a>");
    }

    // Prepared statement per aggiornare il contatto
    $stmt = $conn->prepare("UPDATE contatti SET nome = ?, telefono = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $telefono, $email, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        die("Errore durante l'aggiornamento: " . $stmt->error);
    }
}
    // Recupero ID passato tramite GET
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
    die("ID non valido. <a href='index.php'>Torna indietro</a>");
}

    // Recupero dati del contatto tramite prepared statement
    $stmt = $conn->prepare("SELECT id, nome, telefono, email FROM contatti WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Controllo se il contatto esiste
    if ($result->num_rows === 0) {
    die("Contatto non trovato. <a href='index.php'>Torna indietro</a>");
}

    $row = $result->fetch_assoc();
    
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica contatto</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>
<body>

    <div class="container">

    <h1>Modifica Contatto</h1>

    <form method="POST">
        
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required>

        <label>Telefono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($row['telefono']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

        <button type="submit" class="buttonSave">Salva</button>
    </form>

    <a href="index.php" class="button">Torna ai contatti</a>

</div>

</body>
</html>
