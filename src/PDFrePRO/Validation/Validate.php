<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                              Declarations                                                              \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

declare (strict_types = 1);

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                Namespace                                                               \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

namespace PDFrePRO\Validation;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                 Usages                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

use PDFrePRO\Exception\HttpException;
use PDFrePRO\Validation\Enumeration\ValidStatus;
use PDFrePRO\Validation\Enumeration\ValidStatusCode;
use PDFrePRO\Validation\Exception\InvalidParameterException\InvalidApiKeyException;
use PDFrePRO\Validation\Exception\InvalidParameterException\InvalidSharedKeyException;
use PDFrePRO\Validation\Exception\InvalidResourceException\InvalidPdfException;
use PDFrePRO\Validation\Exception\InvalidResourceException\InvalidPlaceholderException;
use PDFrePRO\Validation\Exception\InvalidResourceException\InvalidPlaceholdersException;
use PDFrePRO\Validation\Exception\InvalidResourceException\InvalidTemplateException;
use PDFrePRO\Validation\Exception\InvalidResourceException\InvalidTemplatesException;
use PDFrePRO\Validation\Exception\InvalidResourceException\InvalidUrlException;
use PDFrePRO\Validation\Exception\InvalidResponseException;
use PDFrePRO\Validation\Exception\MissingResourceException\MissingPdfException;
use PDFrePRO\Validation\Exception\MissingResourceException\MissingPlaceholdersException;
use PDFrePRO\Validation\Exception\MissingResourceException\MissingTemplatesException;
use PDFrePRO\Validation\Exception\MissingResourceException\MissingUrlException;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                  Class                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

/**
 * @package     PDFrePRO
 * @version     v3.04
 * @author      RICHTER & POWELEIT GmbH
 * @description This class provides several validation functionalities, which are used by the PDFrePRO library to ensure, that everything
 *              works as intended.
 * @link        https://www.pdfrepro.de/
 */
class Validate
{
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                          Static Functions                                                          \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Validates an API key, which shall be used for requests to a PDFrePRO host.
     *
     * @param string $apiKey - The API key, which shall be validated.
     *
     * @throws InvalidApiKeyException - If the API key is invalid.
     */
    public static function apiKey(string $apiKey): void
    {
        if ((mb_strlen($apiKey) !== 20) || !ctype_alnum($apiKey)) {
            throw new InvalidApiKeyException('The provided API key is invalid.');
        }
    }

    /**
     * Validates a PDF, which were returned from a PDFrePRO host.
     *
     * @param object $response - The response, which contains the PDF, which shall be validated.
     *
     * @throws InvalidPdfException - If the PDF is invalid.
     * @throws MissingPdfException - If the PDF is missing.
     */
    public static function pdf(object $response): void
    {
        if (!isset ($response->pdf)) {
            throw new MissingPdfException('The response is invalid, due to a missing PDF.');
        }
        if (!is_string($response->pdf)) {
            throw new InvalidPdfException('The response is invalid, due to an invalid PDF.');
        }
    }

    /**
     * Validates a placeholder, which were returned from a PDFrePRO host.
     *
     * @param object $placeholder - The placeholder, which shall be validated.
     * @param string $id          - The unique ID of the placeholder, which shall be validated; if available.
     *
     * @throws InvalidPlaceholderException - If the placeholder is invalid.
     */
    public static function placeholder(object $placeholder, string $id = ''): void
    {
        if (
            !isset (
                $placeholder->id,
                $placeholder->name,
                $placeholder->lastModificationDate,
                $placeholder->numberOfReferencedTemplates
            )                                                  ||
            !is_string($placeholder->id)                       ||
            !is_string($placeholder->name)                     ||
            !is_string($placeholder->lastModificationDate)     ||
            !is_int($placeholder->numberOfReferencedTemplates) ||
            (0 > $placeholder->numberOfReferencedTemplates)    ||
            ('' !== $id) && (!isset ($placeholder->rawData) || !is_string($placeholder->rawData) || ($id !== $placeholder->id))
        ) {
            throw new InvalidPlaceholderException('The response is invalid, due to an invalid placeholder.');
        }
    }

    /**
     * Validates an array of placeholders, which were returned from a PDFrePRO host.
     *
     * @param object $response - The response, which contains the placeholders, which shall be validated.
     *
     * @throws InvalidPlaceholderException  - If the response contains an invalid placeholder.
     * @throws InvalidPlaceholdersException - If the response contains an invalid array of placeholders.
     * @throws MissingPlaceholdersException - If the response contains no array of placeholders.
     */
    public static function placeholders(object $response): void
    {
        if (!isset ($response->placeholders)) {
            throw new MissingPlaceholdersException('The response is invalid, due to missing placeholders.');
        }
        if (!is_array($response->placeholders)) {
            throw new InvalidPlaceholdersException('The response is invalid, due to invalid placeholders.');
        }

        foreach ($response->placeholders as $placeholder) {
            static::placeholder($placeholder);
        }
    }

    /**
     * Validates a response, which were returned from a PDFrePRO host.
     *
     * @param object $response   - The response, which shall be validated.
     * @param array  $validCodes - All valid HTTP status codes, which are expected for the response status "success".
     *
     * @throws HttpException            - If the response contains an HTTP error.
     * @throws InvalidResponseException - If the response is invalid.
     */
    public static function response(object $response, array $validCodes): void
    {
        // Check, whether the properties "code", "status" and "data" are available.
        if (!isset ($response->code, $response->status, $response->data)) {
            throw new InvalidResponseException('The response is invalid, due to a missing "code", "status" or "data" property.');
        }

        // Check, whether the properties "code" and "status" are valid.
        if (
            !in_array($response->code  , [...$validCodes, ...ValidStatusCode::values()], true) ||
            !in_array($response->status, ValidStatus::values()                         , true)
        ) {
            throw new InvalidResponseException('The response is invalid, due to an invalid "code" or "status" property.');
        }

        // Check, whether the response contains an error.
        if ('success' === $response->status) {
            // Check, whether the property "data" is an object.
            if (!is_object($response->data)) {
                throw new InvalidResponseException('The response is invalid, due to an invalid "data" property.');
            }
        } else {
            // Check, whether the property "message" is available.
            if (!isset ($response->message)) {
                throw new InvalidResponseException('The response is invalid, due to a missing "message" property.');
            }

            // Check, whether the properties "data" and "message" are strings.
            if (!is_string($response->data) || !is_string($response->message)) {
                throw new InvalidResponseException('The response is invalid, due to an invalid "data" or "message" property.');
            }

            // Throw a proper throwable.
            throw new HttpException("$response->data: $response->message", $response->code);
        }
    }

    /**
     * Validates a shared key, which shall be used for requests to a PDFrePRO host.
     *
     * @param string $sharedKey - The shared key, which shall be validated.
     *
     * @throws InvalidSharedKeyException - If the shared key is invalid.
     */
    public static function sharedKey(string $sharedKey): void
    {
        if ((mb_strlen($sharedKey) !== 64) || !ctype_alnum($sharedKey)) {
            throw new InvalidSharedKeyException('The provided shared key is invalid.');
        }
    }

    /**
     * Validates a template, which were returned from a PDFrePRO host.
     *
     * @param object $template - The template, which shall be validated.
     * @param string $id       - The unique ID of the template, which shall be validated; if available.
     *
     * @throws InvalidTemplateException - If the template is invalid.
     */
    public static function template(object $template, string $id = ''): void
    {
        if (
            !isset ($template->id, $template->name, $template->lastModificationDate) ||
            !is_string($template->id)                                                ||
            !is_string($template->name)                                              ||
            !is_string($template->lastModificationDate)                              ||
            ('' !== $id) && (
                !isset ($template->usedPlaceholders)   ||
                !is_array($template->usedPlaceholders) ||
                !array_all($template->usedPlaceholders, function (mixed $value, mixed $key): bool {
                    return is_string($value);
                })                                     ||
                ($id !== $template->id)
            )
        ) {
            throw new InvalidTemplateException('The response is invalid, due to an invalid template.');
        }
    }

    /**
     * Validates an array of templates, which were returned from a PDFrePRO host.
     *
     * @param object $response - The response, which contains the templates, which shall be validated.
     *
     * @throws InvalidTemplateException  - If the response contains an invalid template.
     * @throws InvalidTemplatesException - If the response contains an invalid array of templates.
     * @throws MissingTemplatesException - If the response contains no array of templates.
     */
    public static function templates(object $response): void
    {
        if (!isset ($response->templates)) {
            throw new MissingTemplatesException('The response is invalid, due to missing templates.');
        }
        if (!is_array($response->templates)) {
            throw new InvalidTemplatesException('The response is invalid, due to invalid templates.');
        }

        foreach ($response->templates as $template) {
            static::template($template);
        }
    }

    /**
     * Validates a URL, which were returned from a PDFrePRO host.
     *
     * @param object $response    - The response, which contains the URL, which shall be validated.
     * @param string $expectedUrl - The expected URL, to which the URL, which will be validated, shall point.
     * @param string $id          - The unique ID of the data object, to which the URL, which will be validated, shall point.
     * @param string $uriSuffix   - The suffix for the expected URL, which is used, if no unique ID is provided.
     *
     * @throws InvalidUrlException - If the URL is invalid.
     * @throws MissingUrlException - If the URL is missing.
     */
    public static function url(
        object $response,
        string $expectedUrl,
        string $id        = '',
        string $uriSuffix = '/'
    ): void {
        if (!isset ($response->url)) {
            throw new MissingUrlException('The response is invalid, due to a missing URL.');
        }

        $expectedUrl = '' === $id ? "$expectedUrl$uriSuffix" : str_replace('{id}', $id, $expectedUrl);

        if (!is_string($response->url) || ('' === $id ? !str_starts_with($response->url, $expectedUrl) : $expectedUrl !== $response->url)) {
            throw new InvalidUrlException('The response is invalid, due to an invalid URL.');
        }
    }
}
