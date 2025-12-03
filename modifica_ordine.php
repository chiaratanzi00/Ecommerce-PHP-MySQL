<?php
    
    // importo la connessione col file db (db.php)
    require 'db.php';

    // controllo se il form è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $prodotto = trim($_POST['prodotto']);
    $quantita = intval($_POST['quantita']);
    $data_di_ordine = $_POST['data_di_ordine'];
        
    // Validazione base
    if ($id <= 0 || empty($prodotto) || $quantita <= 0 || empty($data_di_ordine)) {
        die("Errore: tutti i campi sono obbligatori. <a href='ordini.php'>Torna indietro</a>");
    }

    // Prepared statement per aggiornare ordine
    $stmt = $conn->prepare("
        UPDATE ordini
        SET prodotto = ?, quantita = ?, data_di_ordine = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sisi", $prodotto, $quantita, $data_di_ordine, $id);

    if ($stmt->execute()) {
        header("Location: ordini.php");
        exit;
    } else {
        die("Errore durante l'aggiornamento: " . $stmt->error);
    }
}

    // Recupero ID passato tramite GET
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
    die("ID non valido. <a href='ordini.php'>Torna indietro</a>");
}

    //  Recupero dati ordine tramite prepared statement
    $stmt = $conn->prepare("SELECT * FROM ordini WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Controllo se l’ordine esiste
    if ($result->num_rows === 0) {
        die("Ordine non trovato. <a href='ordini.php'>Torna indietro</a>");
    }

    $row = $result->fetch_assoc();
?>

    <!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifica Ordine</title>
        <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    </head>
    <body>

    <div class="container">

        <h1>Modifica Ordine</h1>

        <form method="POST">

            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <label>Prodotto:</label>
            <input type="text" name="prodotto" value="<?= htmlspecialchars($row['prodotto']) ?>" required>

            <label>Quantità:</label>
            <input type="number" name="quantita" value="<?= htmlspecialchars($row['quantita']) ?>" min="1" required>

            <label>Data ordine:</label>
            <input type="date" name="data_di_ordine" value="<?= htmlspecialchars($row['data_di_ordine']) ?>" required>

            <button type="submit" class="buttonSave">Salva</button>
        </form>

        <a href="ordini.php" class="button">Torna alla lista ordini</a>

    </div>

    </body>
    </html>