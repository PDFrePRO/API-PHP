<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $placeholderId;

// Get all templates, which are used in a certain placeholder.
try {
    $templates = $pdfrepro->getTemplatesByPlaceholder($placeholderId);

    // Do something with the retrieved templates.

    // Version 1: Print them onto your console.
    /*
    echo json_encode($templates, JSON_PRETTY_PRINT) . PHP_EOL;
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
