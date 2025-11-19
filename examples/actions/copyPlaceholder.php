<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $placeholderId;

// Copy a placeholder of your PDFrePRO account.
try {
    // Copy the placeholder.
    $copiedPlaceholderUrl = $pdfrepro->copyPlaceholder($placeholderId);

    // Print it onto your console.
    /*
    echo $copiedPlaceholderUrl . PHP_EOL;
    // */

    // Extract the placeholder ID.
    $copiedPlaceholderId = substr($copiedPlaceholderUrl, strrpos($copiedPlaceholderUrl, '/') + 1);

    // Print it onto your console.
    /*
    echo $copiedPlaceholderId . PHP_EOL;
    // */

    // Get the copied placeholder.
    $copiedPlaceholder = $pdfrepro->getPlaceholder($copiedPlaceholderId);

    // Print it onto your console.
    /*
    echo json_encode($copiedPlaceholder, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Delete the copied placeholder.
    $pdfrepro->deletePlaceholder($copiedPlaceholderId);
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
