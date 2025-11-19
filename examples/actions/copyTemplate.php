<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $templateId;

// Copy a template of your PDFrePRO account.
try {
    // Copy the template.
    $copiedTemplateUrl = $pdfrepro->copyTemplate($templateId);

    // Print it onto your console.
    /*
    echo $copiedTemplateUrl . PHP_EOL;
    // */

    // Extract the template ID.
    $copiedTemplateId = substr($copiedTemplateUrl, strrpos($copiedTemplateUrl, '/') + 1);

    // Print it onto your console.
    /*
    echo $copiedTemplateId . PHP_EOL;
    // */

    // Get the copied template.
    $copiedTemplate = $pdfrepro->getTemplate($copiedTemplateId);

    // Print it onto your console.
    /*
    echo json_encode($copiedTemplate, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Delete the copied template.
    $pdfrepro->deleteTemplate($copiedTemplateId);
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
