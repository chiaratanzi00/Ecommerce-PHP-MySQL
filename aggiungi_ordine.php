<?php

    //includo il file db
    require 'db.php';

    //  Gestione dell'Invio del Form (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pulizia input
    $contatto_id = intval($_POST['contatto_id']);
    $prodotto = trim($_POST['prodotto']);
    $quantita = intval($_POST['quantita']);
    $data_di_ordine = $_POST['data_di_ordine'];

    // Validazione base
    if ($contatto_id <= 0) {
        die("Errore: devi selezionare un contatto! <a href='aggiungi_ordine.php'>Riprova</a>");
    }

    if (empty($prodotto) || $quantita <= 0 || empty($data_di_ordine)) {
        die("Errore: tutti i campi sono obbligatori. <a href='aggiungi_ordine.php'>Riprova</a>");
    }

    // Controllo che il contatto esista (prepared statement)
    $stmt = $conn->prepare("SELECT id FROM contatti WHERE id = ?");
    $stmt->bind_param("i", $contatto_id);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows == 0) {
        die("Errore: il contatto selezionato non esiste. <a href='aggiungi_ordine.php'>Riprova</a>");
    }

    // Inserimento ordine (prepared)
    $stmt = $conn->prepare("
        INSERT INTO ordini (prodotto, quantita, data_di_ordine, contatto_id)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("sisi", $prodotto, $quantita, $data_di_ordine, $contatto_id);

    if ($stmt->execute()) {
        header("Location: ordini.php");
        exit;
    } else {
        die("Errore inserimento: " . $stmt->error);
    }

    }


    // Recupero elenco contatti per il select
    $contatti = $conn->prepare("SELECT id, nome FROM contatti ORDER BY nome");
    $contatti->execute();
    $lista_contatti = $contatti->get_result();

    // Contatto preselezionato (se arrivo da index.php)
    $contatto_id_preselezionato = isset($_GET['contatto_id']) ? intval($_GET['contatto_id']) : 0;

    // Recupero nome contatto preselezionato
    $nome_contatto = "";
    if ($contatto_id_preselezionato > 0) {
    $stmt = $conn->prepare("SELECT nome FROM contatti WHERE id = ?");
    $stmt->bind_param("i", $contatto_id_preselezionato);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $nome_contatto = $res->fetch_assoc()['nome'];
    }
}

    // Controllo lista contatti vuota
    if ($lista_contatti->num_rows == 0) {
    die("Nessun contatto disponibile! <a href='index.php'>Torna alla rubrica</a>");
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Ordine</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>
<body>

<div class="container">

    <h1>Aggiungi Nuovo Ordine</h1>

    <?php if (!empty($nome_contatto)) : ?>
        <p><strong>Ordine per:</strong> <?= htmlspecialchars($nome_contatto) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Seleziona contatto:</label>
        <select name="contatto_id" required>
            <option value="">-- Scegli un contatto --</option>

            <?php while ($row = $lista_contatti->fetch_assoc()) : ?>
                <option value="<?= $row['id'] ?>"
                    <?= ($row['id'] == $contatto_id_preselezionato) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['nome']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Prodotto:</label>
        <input type="text" name="prodotto" required>

        <label>Quantità:</label>
        <input type="number" name="quantita" min="1" value="1" required>

        <label>Data ordine:</label>
        <input type="date" name="data_di_ordine" value="<?= date('Y-m-d') ?>" required>

        <button type="submit" class="buttonSave">Salva ordine</button>
    </form>

    <a href="ordini.php" class="button">Torna alla lista ordini</a>
    <a href="index.php" class="button">Torna alla rubrica</a>

</div>

</body>
</html>