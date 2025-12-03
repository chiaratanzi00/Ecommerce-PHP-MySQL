<?php

    //importo il file db
    require 'db.php';

    // Query con JOIN per ottenere nome del contatto
    $stmt = $conn->prepare("
    SELECT ordini.id, ordini.prodotto, ordini.quantita, ordini.data_di_ordine,
           contatti.nome AS nome_contatto
    FROM ordini
    JOIN contatti ON contatti.id = ordini.contatto_id
    ORDER BY ordini.data_di_ordine DESC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <title>Lista Ordini</title>
</head>
<body>

<div class="container">

    <h1>Lista Ordini</h1>

    <a href="aggiungi_ordine.php" class="button">Aggiungi ordine</a>
    <a href="index.php" class="button">Torna ai contatti</a>

    <table>
        <thead>
            <tr>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Data ordine</th>
                <th>Contatto</th>
                <th>Azioni</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($result->num_rows > 0) : ?>

                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['prodotto']) ?></td>
                        <td><?= htmlspecialchars($row['quantita']) ?></td>
                        <td><?= htmlspecialchars($row['data_di_ordine']) ?></td>
                        <td><?= htmlspecialchars($row['nome_contatto']) ?></td>

                        <td class="actions">
                            <a href="modifica_ordine.php?id=<?= $row['id'] ?>">🖊️</a>
                            <a href="elimina_ordine.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Eliminare questo ordine?');">🗑️</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

            <?php else : ?>
                <tr>
                    <td colspan="5" style="text-align:center; font-style:italic;">
                        Nessun ordine presente.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>