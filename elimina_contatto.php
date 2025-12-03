<?php

    // importo la connessione col file db (db.php)
    require 'db.php';

    // Recupero ID dalla query string
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
    die("ID non valido. <a href='index.php'>Torna indietro</a>");
}

    //  Verifico se il contatto esiste
    $stmt = $conn->prepare("SELECT id FROM contatti WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
    die("Contatto non trovato. <a href='index.php'>Torna indietro</a>");
}

    // Elimino il contatto (prepared statement)
    $stmt = $conn->prepare("DELETE FROM contatti WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
    header("Location: index.php");
    exit;
}   else {
    die("Errore durante l'eliminazione: " . $stmt->error);
}
?>

