<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro;

// Get all placeholders of your PDFrePRO account.
try {
    $placeholders = $pdfrepro->getAllPlaceholders();

    // Do something with the retrieved placeholders.

    // Version 1: Print them onto your console.
    /*
    // */
    echo json_encode($placeholders, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
