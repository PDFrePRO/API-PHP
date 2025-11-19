<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $templateId;

// Get a certain template of your PDFrePRO account.
try {
    $template = $pdfrepro->getTemplate($templateId);

    // Do something with the retrieved template.

    // Version 1: Print it onto your console.
    /*
    echo json_encode($template, JSON_PRETTY_PRINT) . PHP_EOL;
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
