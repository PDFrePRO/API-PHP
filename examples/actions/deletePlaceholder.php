<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $data, $pdfrepro;

// Delete a certain placeholder of your PDFrePRO account.
try {
    // Create the placeholder, which shall be deleted.
    $placeholderUrl = $pdfrepro->createPlaceholder('Created Placeholder', json_encode($data));

    // Print it onto your console.
    /*
    echo $placeholderUrl . PHP_EOL;
    // */

    // Extract the placeholder ID.
    $placeholderId = substr($placeholderUrl, strrpos($placeholderUrl, '/') + 1);

    // Print it onto your console.
    /*
    echo $placeholderId . PHP_EOL;
    // */

    // Delete the placeholder.
    $pdfrepro->deletePlaceholder($placeholderId);

    // Try to get the deleted placeholder.
    try {
        $placeholder = $pdfrepro->getPlaceholder($placeholderId);

        // Print it onto your console.
        /*
        echo json_encode($placeholder, JSON_PRETTY_PRINT) . PHP_EOL;
        // */
    } catch (Throwable $throwable) {
        // Print it onto your console.
        /*
        echo 'Deleted' . PHP_EOL;
        // */
    }
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
