<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $templateId;

// Get the editor URL.
try {
    $editorUrl = $pdfrepro->getEditorUrl($templateId);

    // Do something with the retrieved editor URL.

    // Version 1: Print it onto your console.
    /*
    echo $editorUrl . PHP_EOL;
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
