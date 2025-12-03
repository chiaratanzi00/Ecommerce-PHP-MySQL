<?php

    // Importo la connessione al database
    require 'db.php';

    //  Query sicura per ottenere tutti i contatti
    $stmt = $conn->prepare("SELECT id, nome, telefono, email FROM contatti ORDER BY nome");
    $stmt->execute();
    $result = $stmt->get_result();

?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce</title>
    <link rel="stylesheet" href="style.css?v<?= time() ?>">
</head>
<body>

    <div class="container">

        <h1>Rubrica contatti</h1>
        <a href="aggiungi_contatto.php" class="button">Aggiungi contatto</a>
        <a href="ordini.php" class="button">Vai su ordini</a>
    

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Azioni</th>
                </tr>
            </thead>


             <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>

                            <td class="actions">
                                <a href="modifica_contatto.php?id=<?= $row['id'] ?>">🖊️</a>
                                <a href="elimina_contatto.php?id=<?= $row['id'] ?>"
                                   onclick="return confirm('Eliminare questo contatto?');">🗑️</a>
                                <a href="aggiungi_ordine.php?contatto_id=<?= $row['id'] ?>">📦</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                
                <?php else : ?>
                    <tr>
                        <td colspan="4" style="text-align:center; font-style:italic;">
                            Nessun contatto presente.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</body>
</html>

