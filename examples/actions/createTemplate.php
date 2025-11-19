<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro;

// Create a new template.
try {
    // Create the new template.
    $templateUrl = $pdfrepro->createTemplate('New Template', 'This is a new Template.');

    // Print it onto your console.
    /*
    echo $templateUrl . PHP_EOL;
    // */

    // Extract the template ID.
    $templateId = substr($templateUrl, strrpos($templateUrl, '/') + 1);

    // Print it onto your console.
    /*
    echo $templateId . PHP_EOL;
    // */

    // Get te created template.
    $template = $pdfrepro->getTemplate($templateId);

    // Print it onto your console.
    /*
    echo json_encode($template, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Delete the created template.
    $pdfrepro->deleteTemplate($templateId);
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
