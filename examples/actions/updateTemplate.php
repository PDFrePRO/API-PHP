<?php

// Bootstrap PDFRePRO.
require_once (__DIR__ . '/../bootstrap.php');

// Use global variables (prevents errors in the IDE).
global $pdfrepro, $templateId;

// Update a certain template of your PDFrePRO account.
try {
    // Get the template.
    $template = $pdfrepro->getTemplate($templateId);

    // Print it onto your console.
    /*
    echo 'Before Update:'                          . PHP_EOL;
    echo json_encode($template, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Update the template.
    $pdfrepro->updateTemplate($templateId, 'Update Name', 'Update Description');

    // Get the updated template.
    $updatedTemplate = $pdfrepro->getTemplate($templateId);

    // Print it onto your console.
    /*
    echo 'After Update:'                                  . PHP_EOL;
    echo json_encode($updatedTemplate, JSON_PRETTY_PRINT) . PHP_EOL;
    // */

    // Restore the template.
    $pdfrepro->updateTemplate($templateId, $template->name, $template->description);

    // Get the template.
    $restoredTemplate = $pdfrepro->getTemplate($templateId);

    // Print it onto your console.
    /*
    echo 'After Restore:'                                  . PHP_EOL;
    echo json_encode($restoredTemplate, JSON_PRETTY_PRINT) . PHP_EOL;
    // */
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
