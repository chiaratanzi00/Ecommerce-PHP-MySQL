    <?php
    // Importo connessione al database
    require 'db.php';

    // Recupero ID ordine da GET
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        die("ID non valido. <a href='ordini.php'>Torna indietro</a>");
    }

    // Verifico che l’ordine esista
    $stmt = $conn->prepare("SELECT id FROM ordini WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows === 0) {
        die("Ordine non trovato. <a href='ordini.php'>Torna indietro</a>");
    }

    // Elimino l’ordine (prepared statement)
    $stmt = $conn->prepare("DELETE FROM ordini WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ordini.php");
        exit;
    } else {
        die("Errore durante l'eliminazione: " . $stmt->error);
    }
    ?>