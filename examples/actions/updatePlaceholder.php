<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $placeholderId;

// Update a certain placeholder of your PDFrePRO account.
try {
    // Get the placeholder.
    $placeholder = $pdfrepro->getPlaceholder($placeholderId);

    // Print it onto your console.
    /*
    echo 'Before Update:'                             . PHP_EOL;
    echo json_encode($placeholder, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Update the placeholder.
    $pdfrepro->updatePlaceholder($placeholderId, 'Update Name');

    // Get the updated placeholder.
    $updatedPlaceholder = $pdfrepro->getPlaceholder($placeholderId);

    // Print it onto your console.
    /*
    echo 'After Update:'                                     . PHP_EOL;
    echo json_encode($updatedPlaceholder, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Restore the placeholder.
    $pdfrepro->updatePlaceholder($placeholderId, $placeholder->name);

    // Get the placeholder.
    $restoredPlaceholder = $pdfrepro->getPlaceholder($placeholderId);

    // Print it onto your console.
    /*
    echo 'After Restore:'                                     . PHP_EOL;
    echo json_encode($restoredPlaceholder, JSON_PRETTY_PRINT) . PHP_EOL;
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
