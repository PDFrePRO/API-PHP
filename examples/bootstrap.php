<?php

// Use autoload of **Composer**.
require_once (__DIR__ . '/../vendor/autoload.php');

/*
 * Load the credentials of your PDFrePRO account.
 *
 * @note You need to enter the credentials of your PDFrePRO account. Otherwise, these examples will not work.
 */
include (__DIR__ . '/credentials.php');

// Prepare some variables for later usage.

/**
 * The example data for placeholders and printing.
 */
$data          = json_decode(file_get_contents(__DIR__ . '/data.json'));

/**
 * The ID of one of the placeholders of your PDFrePRO account.
 */
$placeholderId = '<your-placeholder-ID>';

/**
 * The language, in which PDFs shall be printed.
 *
 * @note If left empty, the first language of the template will be used.
 * @note Only the languages, which are defined in the corresponding API key, can be used.
 * @note Only language codes in ISO 639-1 are supported.
 */
$printLanguage = 'en';

/**
 * The ID of one of the templates of your PDFrePRO account.
 */
$templateId    = '<your-template-ID>';

// Use global variables (prevents errors in the IDE).
global $apiKey, $sharedKey;

// Initialize PDFrePRO.
try {
    $pdfrepro = new PDFrePRO($apiKey, $sharedKey);
} catch (Throwable $throwable) {
    echo '<pre>' . $throwable::class . ' (' . $throwable->getCode() . '): ' . $throwable->getMessage() . '</pre>' . PHP_EOL;

    exit (1);
}
