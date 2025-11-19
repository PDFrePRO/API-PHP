<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $data, $pdfrepro;

// Create a new placeholder.
try {
    // Create the new placeholder.
    $placeholderUrl = $pdfrepro->createPlaceholder('New Placeholder', json_encode($data));

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

    // Get the created placeholder.
    $placeholder = $pdfrepro->getPlaceholder($placeholderId);

    // Print it onto your console.
    /*
    echo json_encode($placeholder, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Delete the created placeholder.
    $pdfrepro->deletePlaceholder($placeholderId);
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
