<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $placeholderId;

// Get a certain placeholder of your PDFrePRO account.
try {
    $placeholder = $pdfrepro->getPlaceholder($placeholderId);

    // Do something with the retrieved placeholder.

    // Version 1: Print it onto your console.
    /*
    echo json_encode($placeholder, JSON_PRETTY_PRINT) . PHP_EOL;
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
