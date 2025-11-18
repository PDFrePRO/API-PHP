<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                             Required Files                                                             \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

require_once (__DIR__ . '/PDFrePROException.class.php');

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                  Class                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

/**
 * @package     PDFrePRO
 * @version     v3.04
 * @author      RICHTER & POWELEIT GmbH
 * @description This class uses the PDFrePRO API to print PDFs.
 *
 * @note This packages requires PHP version 8 or above.
 * @note Before you start using the PDFrePRO API, please make sure you have everything configured correctly in your account in the PDFrePRO
 *       Portal.
 * @note If you intend to integrate the PDFrePRO WYSIWYG editor into your own application, you need to set up a success and abort URL as
 *       follow-up URLs in the PDFrePRO Portal. This lets the editor know where to redirect users to, when they leave the editor.
 *
 * @link https://www.pdfrepro.de/
 * @link https://portal.pdfrepro.de/
 */
class PDFrePRO
{
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                           URL Properties                                                           \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The PDFrePRO host, to which all requests will be sent.
     */
    protected string $host = 'https://api.pdfrepro.de';

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                        Credential Properties                                                       \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The API key, which shall be used for requests.
     */
    protected string $apiKey    = '';

    /**
     * The shared key, which is associated to {@see static::$apiKey}.
     */
    protected string $sharedKey = '';

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                           Host Constants                                                           \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The host of the PDFrePRO WYSIWYG editor.
     */
    const HOST_EDITOR_PDFREPRO = 'https://editor.pdfrepro.de';

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                            URI Constants                                                           \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The URI for requests on the WYSIWYG editor.
     */
    const URI_EDITOR                    = '/editor';

    /**
     * The URI for general requests on placeholders.
     */
    const URI_PLACEHOLDERS              = '/v3/placeholders';

    /**
     * The URI for requests on a specific placeholder.
     */
    const URI_PLACEHOLDERS_ID           = '/v3/placeholders/{id}';

    /**
     * The URI for requests on templates, which are using a specific placeholder.
     */
    const URI_PLACEHOLDERS_ID_TEMPLATES = '/v3/placeholders/{id}/templates';

    /**
     * The URI for general requests on templates.
     */
    const URI_TEMPLATES                 = '/v3/templates';

    /**
     * The URI for requests on a specific template.
     */
    const URI_TEMPLATES_ID              = '/v3/templates/{id}';

    /**
     * The URI for requests on placeholders, which are used by a specific template.
     */
    const URI_TEMPLATES_ID_PLACEHOLDERS = '/v3/templates/{id}/placeholders';

    /**
     * The URI for requests on the WYSIWYG editor, which is using a specific template.
     */
    const URI_TEMPLATES_ID_EDITOR_URL   = '/v3/templates/{id}/editor-url';

    /**
     * The URI for requests on the PDF of a specific template.
     */
    const URI_TEMPLATES_ID_PDF          = '/v3/templates/{id}/pdf';

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                        Validation Constants                                                        \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * This array contains all valid status codes for the response statuses "error" and "fail".
     *
     * @note The valid status codes for the response status "success" are provided to {@see validateResponse()} as an optional parameter.
     */
    const VALID_STATUS_CODES = [
        400,
        401,
        404,
        405,
        406,
        408,
        409,
        411,
        500
    ];

    /**
     * This array contains all valid response statuses.
     */
    const VALID_STATUSES     = [
        'success',
        'error',
        'fail'
    ];

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                            Magic Methods                                                           \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * The constructor of this class.
     *
     * @param string $apiKey    - The API key, which shall be used for requests.
     * @param string $sharedKey - The shared key, which is associated to {@param $apiKey}.
     * @param string $host      - An optional PDFrePRO host, to which all requests shall be sent.
     *
     * @throws PDFrePROException - If {@param $apiKey}, {@param $sharedKey}, or {@param $host} are invalid.
     *
     * @constructor
     */
    public function __construct(string $apiKey, string $sharedKey, string $host = '')
    {
        // Validate the PHP version.
        if (PHP_VERSION_ID < 80000) {
            throw new PDFrePROException('The minimum required PHP version is 8.0.0.', PDFrePROException::CODE_MINIMUM_REQUIRED_PHP_VERSION);
        }

        // Set credentials.
        $this->setApiKey($apiKey);
        $this->setSharedKey($sharedKey);

        // Set the PDFrePRO API host.
        if (!empty ($host)) {
            $this->setHost($host);
        }

        // Set the internal encoding.
        mb_internal_encoding('UTF-8');
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                               Setter                                                               \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Sets the API key, which shall be used for requests.
     *
     * @param string $apiKey - The API key, which shall be used for requests.
     *
     * @throws PDFrePROException - If {@param $apiKey} is invalid.
     */
    public function setApiKey(string $apiKey): void
    {
        if ((mb_strlen($apiKey) !== 20) || !ctype_alnum($apiKey)) {
            throw new PDFrePROException('It has been tried to set an invalid apiKey.');
        }

        $this->apiKey = $apiKey;
    }

    /**
     * Sets the PDFrePRO host, to which all requests shall be sent.
     *
     * @param string $host - The PDFrePRO host, to which all requests shall be sent.
     */
    public function setHost(string $host): void
    {
        if (!empty ($host)) {
            $this->host = $host;
        }
    }

    /**
     * Sets the shared key, which is associated to {@see static::$apiKey}.
     *
     * @param string $sharedKey - The shared key, which is associated to {@see static::$apiKey}.
     *
     * @throws PDFrePROException - If {@param $sharedKey} is invalid.
     */
    public function setSharedKey(string $sharedKey): void
    {
        if ((mb_strlen($sharedKey) !== 64) || !ctype_alnum($sharedKey)) {
            throw new PDFrePROException('It has been tried to set an invalid sharedKey.');
        }

        $this->sharedKey = $sharedKey;
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                        Placeholder Functions                                                       \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Copies an existing placeholder of your PDFrePRO account.
     *
     * @param string $id   - The ID of the placeholder, which shall be copied.
     * @param string $name - An optional name of the copied placeholder.
     *
     * @return string - A relative URL to the copied placeholder.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function copyPlaceholder(string $id, string $name = ''): string
    {
        // Prepare the request.
        $requestData = (object)[];

        if (!empty ($name)) {
            $requestData->name = $name;
        }

        // Send the request.
        $response = $this->sendRequest(
            str_replace('{id}', $id, self::URI_PLACEHOLDERS_ID),
            'POST',
            $requestData,
            validCodes: [201]
        );

        // Validate the response.
        $this->validateUrl($response, self::URI_PLACEHOLDERS);

        return $response->url;
    }

    /**
     * Creates a new placeholder for your PDFrePRO account.
     *
     * @param string $name - The name of the new placeholder.
     * @param string $data - The data of the new placeholder.
     *
     * @return string - A relative URL to the new placeholder.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function createPlaceholder(string $name, string $data): string
    {
        // Check, whether {@param $data} can be properly JSON decoded.
        if (false === json_decode($data)) {
            throw new PDFrePROException(json_last_error_msg(), json_last_error());
        }

        // Send the request.
        $response = $this->sendRequest(
            self::URI_PLACEHOLDERS,
            'POST',
            (object)['name' => $name, 'data' => $data],
            validCodes: [201]
        );

        // Validate the response.
        $this->validateUrl($response, self::URI_PLACEHOLDERS);

        return $response->url;
    }

    /**
     * Deletes an existing placeholder of your PDFrePRO account.
     *
     * @param string $id - The ID of the placeholder, which shall be deleted.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function deletePlaceholder(string $id): void
    {
        // Send the request.
        $this->sendRequest(str_replace('{id}', $id, self::URI_PLACEHOLDERS_ID), 'DELETE', validCodes: [204]);
    }

    /**
     * Gets all placeholders of your PDFrePRO account.
     *
     * @return array - All placeholders of your PDFrePRO account.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getAllPlaceholders(): array
    {
        // Send the request.
        $response = $this->sendRequest(self::URI_PLACEHOLDERS, httpCode: $httpCode, validCodes: [200, 204]);

        // Check, whether the HTTP status code indicates no content.
        if (204 === $httpCode) {
            $response->placeholders = []; // Provide an empty array of placeholders.
        }

        // Validate the response.
        $this->validatePlaceholders($response);

        return $response->placeholders;
    }

    /**
     * Gets a specific placeholder of your PDFrePRO account.
     *
     * @param string $id - The ID of the placeholder, which shall be requested.
     *
     * @return object - The requested placeholder of your PDFrePRO account.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getPlaceholder(string $id): object
    {
        // Send the request.
        $placeholder = $this->sendRequest(str_replace('{id}', $id, self::URI_PLACEHOLDERS_ID));

        // Validate the response.
        $this->validatePlaceholder($placeholder, $id);

        return $placeholder;
    }

    /**
     * Gets all templates of your PDFrePRO account, which are using a specific placeholder.
     *
     * @param string $id - The ID of the placeholder, which is used by the requested templates.
     *
     * @return array - All templates of your PDFrePRO account, which are using the specified placeholder.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getTemplatesByPlaceholder(string $id): array
    {
        // Send the request.
        $response = $this->sendRequest(
            str_replace('{id}', $id, self::URI_PLACEHOLDERS_ID_TEMPLATES),
            httpCode  : $httpCode,
            validCodes: [200, 204]
        );

        // Check, whether the HTTP status code indicates no content.
        if (204 === $httpCode) {
            $response->templates = []; // Provide an empty array of templates.
        }

        // Validate the response.
        $this->validateTemplates($response);

        return $response->templates;
    }

    /**
     * Updates an existing placeholder of your PDFrePRO account.
     *
     * @param string $id   - The ID of the placeholder, which shall be updated.
     * @param string $name - An optional new name of the placeholder.
     * @param string $data - An optional new data of the placeholder.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function updatePlaceholder(string $id, string $name = '', string $data = ''): void
    {
        // Prepare the request.
        $requestData = (object)[];

        if (!empty ($name)) {
            $requestData->name = $name;
        }
        if (!empty ($data)) {
            // Check, whether {@param $data} can be properly JSON decoded.
            if (false === json_decode($data)) {
                throw new PDFrePROException(json_last_error_msg(), json_last_error());
            }

            $requestData->data = $data;
        }

        // Send the request.
        $response = $this->sendRequest(str_replace('{id}', $id, self::URI_PLACEHOLDERS_ID), 'PUT', $requestData);

        // Validate the response.
        $this->validateUrl($response, self::URI_PLACEHOLDERS_ID, $id);
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                         Template Functions                                                         \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Copies an existing template of your PDFrePRO account.
     *
     * @param string $id          - The ID of the template, which shall be copied.
     * @param string $name        - An optional name of the copied template.
     * @param string $description - An optional description of the copied template.
     *
     * @return string - A relative URL to the copied template.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function copyTemplate(string $id, string $name = '', string $description = ''): string
    {
        // Prepare the request.
        $requestData = (object)[];

        if (!empty ($name)) {
            $requestData->name = $name;
        }
        if (!empty ($description)) {
            $requestData->description = $description;
        }

        // Send the request.
        $response = $this->sendRequest(
            str_replace('{id}', $id, self::URI_TEMPLATES_ID),
            'POST',
            $requestData,
            validCodes: [201]
        );

        // Validate the response.
        $this->validateUrl($response, self::URI_TEMPLATES);

        return $response->url;
    }

    /**
     * Creates a new template for your PDFrePRO account.
     *
     * @param string $name           - The name of the new template.
     * @param string $description    - An optional description of the new template.
     * @param array  $placeholderIds - Optional IDs of all placeholders, which shall be used by the new template.
     *
     * @return string - A relative URL to the new template.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function createTemplate(string $name, string $description = '', array $placeholderIds = []): string
    {
        // Send the request.
        $response = $this->sendRequest(
            self::URI_TEMPLATES,
            'POST',
            (object)['name' => $name, 'description' => $description, 'placeholderIds' => $placeholderIds],
            validCodes: [201]
        );

        // Validate the response.
        $this->validateUrl($response, self::URI_TEMPLATES);

        return $response->url;
    }

    /**
     * Deletes an existing template of your PDFrePRO account.
     *
     * @param string $id - The ID of the template, which shall be deleted.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function deleteTemplate(string $id): void
    {
        // Send the request.
        $this->sendRequest(str_replace('{id}', $id, self::URI_TEMPLATES_ID), 'DELETE', validCodes: [204]);
    }

    /**
     * Gets all templates of your PDFrePRO account.
     *
     * @return array - All templates of your PDFrePRO account.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getAllTemplates(): array
    {
        // Send the request.
        $response = $this->sendRequest(self::URI_TEMPLATES, httpCode: $httpCode, validCodes: [200, 204]);

        // Check, whether the HTTP status code indicates no content.
        if (204 === $httpCode) {
            $response->templates = []; // Provide an empty array of templates.
        }

        // Validate the response.
        $this->validateTemplates($response);

        return $response->templates;
    }

    /**
     * Gets a URL, which opens the WYSIWYG editor, to edit an existing template of your PDFrePRO account.
     *
     * @param string $id - The ID of the template, which shall be opened in the WYSIWYG editor.
     *
     * @return string - A URL to open the WYSIWYG editor.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getEditorUrl(string $id): string
    {
        // Send the request.
        $response = $this->sendRequest(str_replace('{id}', $id, self::URI_TEMPLATES_ID_EDITOR_URL));

        // Validate the response.
        $this->validateUrl($response, self::HOST_EDITOR_PDFREPRO . self::URI_EDITOR, uriSuffix: '?');

        return $response->url;
    }

    /**
     * Gets a Base64-encoded PDF of an existing template of your PDFrePRO account.
     *
     * @param  string $id       - The ID of the template, which shall be printed as PDF.
     * @param ?object $data     - The data for the placeholders, which are used by the template.
     *                            @example (object)[<placeholderName> => (object)[<placeholderDataName> => <placeholderDataValue>]];
     * @param  string $language - The language, in which the PDF shall be printed.
     *                            @note This language must be defined in the settings of your API key.
     *                            @example 'en-GB'
     *
     * @return string - A Base64-encoded PDF of the specified template of your PDFrePRO account.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getPDF(string $id, ?object $data = null, string $language = ''): string
    {
        // Check, whether {@param $data} can be properly JSON encoded.
        $dataString = json_encode($data ?? (object)[]);

        if (false === $dataString) {
            throw new PDFrePROException(json_last_error_msg(), json_last_error());
        }

        // Send the request.
        $response = $this->sendRequest(
            str_replace('{id}', $id, self::URI_TEMPLATES_ID_PDF),
            'POST',
            (object)['data' => $dataString, 'language' => $language],
            validCodes: [201, 429]
        );

        // Validate the response.
        $this->validatePdf($response);

        return $response->pdf;
    }

    /**
     * Gets all placeholders of your PDFrePRO account, which are used by a specific template.
     *
     * @param string $id - The ID of the template, which uses the requested placeholders.
     *
     * @return array - All placeholders of your PDFrePRO account, which are used by the specified template.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getPlaceholdersByTemplate(string $id): array
    {
        // Send the request.
        $response = $this->sendRequest(
            str_replace('{id}', $id, self::URI_TEMPLATES_ID_PLACEHOLDERS),
            httpCode  : $httpCode,
            validCodes: [200, 204]
        );

        // Check, whether the HTTP status code indicates no content.
        if (204 === $httpCode) {
            $response->placeholders = []; // Provide an empty array of placeholders.
        }

        // Validate the response.
        $this->validatePlaceholders($response);

        return $response->placeholders;
    }

    /**
     * Gets a specific template of your PDFrePRO account.
     *
     * @param string $id - The ID of the template, which shall be requested.
     *
     * @return object - The requested template of your PDFrePRO account.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function getTemplate(string $id): object
    {
        // Send the request.
        $template = $this->sendRequest(str_replace('{id}', $id, self::URI_TEMPLATES_ID));

        // Validate the response.
        $this->validateTemplate($template, $id);

        return $template;
    }

    /**
     * Updates an existing template of your PDFrePRO account.
     *
     * @param  string $id             - The ID of the template, which shall be updated.
     * @param  string $name           - An optional new name of the template.
     * @param  string $description    - An optional new description of the template.
     * @param ?array  $placeholderIds - The IDs of all placeholders, which shall be used by the template.
     *                                  @note Providing an array (even an empty one), removes all existing usages of placeholders by the
     *                                        template.
     *
     * @throws PDFrePROException - If the request could not be sent properly, or the response is invalid or contains an error.
     */
    public function updateTemplate(
         string $id,
         string $name           = '',
         string $description    = '',
        ?array  $placeholderIds = null
    ): void {
        // Prepare the request.
        $requestData = (object)[];

        if (!empty ($name)) {
            $requestData->name = $name;
        }
        if (!empty ($description)) {
            $requestData->description = $description;
        }
        if (null !== $placeholderIds) {
            $requestData->placeholderIds = $placeholderIds;
        }

        // Send the request.
        $response = $this->sendRequest(str_replace('{id}', $id, self::URI_TEMPLATES_ID), 'PUT', $requestData);

        // Validate the response.
        $this->validateUrl($response, self::URI_TEMPLATES_ID, $id);
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                        Validation Functions                                                        \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Validates a PDF, which were returned from a PDFrePRO host.
     *
     * @param object $response - The response, which contains the PDF, which shall be validated.
     *
     * @throws PDFrePROException - If the PDF is not valid.
     */
    protected function validatePdf(object $response): void
    {
        if (!isset ($response->pdf) || !is_string($response->pdf)) {
            throw new PDFrePROException('The response is invalid, due to an invalid PDF.');
        }
    }

    /**
     * Validates a placeholder, which were returned from a PDFrePRO host.
     *
     * @param object $placeholder - The placeholder, which shall be validated.
     * @param string $id          - The unique ID of the placeholder, which shall be validated; if available.
     *
     * @throws PDFrePROException - If the placeholder is not valid.
     */
    protected function validatePlaceholder(object $placeholder, string $id = ''): void
    {
        if (
            !isset (
                $placeholder->id,
                $placeholder->name,
                $placeholder->lastModificationDate,
                $placeholder->numberOfReferencedTemplates
            )                                                      ||
            !is_string($placeholder->id)                           ||
            !is_string($placeholder->name)                         ||
            !is_string($placeholder->lastModificationDate)         ||
            !is_integer($placeholder->numberOfReferencedTemplates) ||
            (0 > $placeholder->numberOfReferencedTemplates)        ||
            ('' !== $id) && (!isset ($placeholder->rawData) || !is_string($placeholder->rawData) || ($id !== $placeholder->id))
        ) {
            throw new PDFrePROException('The response is invalid, due to an invalid placeholder.');
        }
    }

    /**
     * Validates an array of placeholders, which were returned from a PDFrePRO host.
     *
     * @param object $response - The response, which contains the placeholders, which shall be validated.
     *
     * @throws PDFrePROException - If the array of placeholders is not valid, or contains an invalid placeholder.
     */
    protected function validatePlaceholders(object $response): void
    {
        if (!isset ($response->placeholders) || !is_array($response->placeholders)) {
            throw new PDFrePROException('The response is invalid, due to missing or invalid placeholders.');
        }

        foreach ($response->placeholders as $placeholder) {
            $this->validatePlaceholder($placeholder);
        }
    }

    /**
     * Validates a response, which were returned from a PDFrePRO host.
     *
     * @param object $response   - The response, which shall be validated.
     * @param array  $validCodes - All valid HTTP status codes, which are expected for the response status "success".
     *
     * @throws PDFrePROException - If the response is not valid or contains an error.
     */
    protected function validateResponse(object $response, array $validCodes): void
    {
        // Check, whether the properties "code", "status" and "data" are available.
        if (!isset ($response->code, $response->status, $response->data)) {
            throw new PDFrePROException('The response is invalid, due to a missing "code", "status" or "data" property.');
        }

        // Check, whether the properties "code" and "status" are valid.
        if (
            !in_array($response->code  , [...$validCodes, ...self::VALID_STATUS_CODES], true) ||
            !in_array($response->status, self::VALID_STATUSES                         , true)
        ) {
            throw new PDFrePROException('The response is invalid, due to an invalid "code" or "status" property.');
        }

        // Check, whether the response contains an error.
        if ('success' === $response->status) {
            // Check, whether the property "data" is an object.
            if (!is_object($response->data)) {
                throw new PDFrePROException('The response is invalid, due to an invalid "data" property.');
            }
        } else {
            // Check, whether the property "message" is available.
            if (!isset ($response->message)) {
                throw new PDFrePROException('The response is invalid, due to a missing "message" property.');
            }

            // Check, whether the properties "data" and "message" are strings.
            if (!is_string($response->data) || !is_string($response->message)) {
                throw new PDFrePROException('The response is invalid, due to an invalid "data" or "message" property.');
            }

            // Throw a proper throwable.
            throw new PDFrePROException("$response->data: $response->message", $response->code);
        }
    }

    /**
     * Validates a template, which were returned from a PDFrePRO host.
     *
     * @param object $template - The template, which shall be validated.
     * @param string $id       - The unique ID of the template, which shall be validated; if available.
     *
     * @throws PDFrePROException - If the template is not valid.
     */
    protected function validateTemplate(object $template, string $id = ''): void
    {
        if (
            !isset ($template->id, $template->name, $template->lastModificationDate) ||
            !is_string($template->id)                                                ||
            !is_string($template->name)                                              ||
            !is_string($template->lastModificationDate)                              ||
            ('' !== $id) && ($id !== $template->id)
        ) {
            throw new PDFrePROException('The response is invalid, due to an invalid template.');
        }
    }

    /**
     * Validates an array of templates, which were returned from a PDFrePRO host.
     *
     * @param object $response - The response, which contains the templates, which shall be validated.
     *
     * @throws PDFrePROException - If the array of templates is not valid, or contains an invalid template.
     */
    protected function validateTemplates(object $response): void
    {
        if (!isset ($response->templates) || !is_array($response->templates)) {
            throw new PDFrePROException('The response is invalid, due to missing or invalid templates.');
        }

        foreach ($response->templates as $template) {
            $this->validateTemplate($template);
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
     * @throws PDFrePROException - If the URL is not valid.
     */
    protected function validateUrl(
        object $response,
        string $expectedUrl,
        string $id        = '',
        string $uriSuffix = '/'
    ): void {
        $expectedUrl = '' === $id ? "$expectedUrl$uriSuffix" : str_replace('{id}', $id, $expectedUrl);

        if (
            !isset ($response->url)    ||
            !is_string($response->url) ||
            ('' === $id ? !str_starts_with($response->url, $expectedUrl) : $expectedUrl !== $response->url)
        ) {
            throw new PDFrePROException('The response is invalid, due to an invalid URL.');
        }
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                          Request Functions                                                         \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * Executes a cURL session.
     *
     * @param  CurlHandle $curl     - The handle to the cURL session, which shall be executed.
     * @param ?integer    $httpCode - This output parameter will hold the HTTP status code of the executed cURL session.
     *
     * @return object - The response of the executed cURL session.
     *
     * @throws PDFrePROException - If the cURL session could not be executed, properly.
     */
    protected function executeCurl(CurlHandle $curl, ?int &$httpCode): object
    {
        // Execute the cURL session.
        $result = curl_exec($curl);

        if (false === $result) {
            throw new PDFrePROException(curl_error($curl), curl_errno($curl));
        }

        // Set the HTTP status code of the executed cURL session.
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (false === $httpCode) {
            throw new PDFrePROException(curl_error($curl), curl_errno($curl));
        }

        // Close the cURL session.
        curl_close($curl);

        // Check, whether a proper response has been received.
        $result = json_decode($result);

        if (!is_object($result)) {
            throw new PDFrePROException('An invalid response has been received.');
        }

        return $result;
    }

    /**
     * Initializes a new cURL session.
     *
     * @param  string $resource - The resource, which shall be requested.
     * @param  string $method   - The HTTP method for the request.
     * @param ?object $data     - The data, which shall be sent with the request.
     *
     * @return CurlHandle - A handle to a cURL session.
     *
     * @throws PDFrePROException - If the cURL session could not be initialized, properly.
     */
    protected function initializeCurl(string $resource, string $method, ?object $data): CurlHandle
    {
        // Initialize the cURL session.
        $curl = curl_init("$this->host$resource");

        if (!$curl) {
            throw new PDFrePROException('The cURL session could not be initialized.');
        }

        // Set general cURL options.
        $result = curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        if (!$result) {
            throw new PDFrePROException(curl_error($curl), curl_errno($curl));
        }

        // Initialize the headers array.
        $accept        = 'application/json;charset=utf-8';
        $contentType   = '';
        $contentLength = '';
        $headers       = ["Accept: $accept"];

        // Set further cURL options, depending on the request method.
        if (in_array($method, ['POST', 'PUT'])) {
            // Convert the request data into a string.
            $dataString = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);

            // Add the request data to the cURL session.
            $result = curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);

            if (!$result) {
                throw new PDFrePROException(curl_error($curl), curl_errno($curl));
            }

            // Add the content headers to the headers array.
            $contentType   = 'application/json;charset=utf-8';
            $contentLength = strlen($dataString);
            $headers[]     = "Content-Type: $contentType";
            $headers[]     = "Content-Length: $contentLength";
        }

        // Add the date header to the headers array.
        $timezone = date_default_timezone_get();

        date_default_timezone_set('GMT');

        $date      = date('D, d M Y G:i:s', time()) . ' GMT';
        $headers[] = "Date: $date";

        date_default_timezone_set($timezone);

        // Extract host and port of the host URL.
        $host = parse_url($this->host, PHP_URL_HOST);
        $port = parse_url($this->host, PHP_URL_PORT);

        if (!empty ($port)) {
            $host .= ":$port";
        }

        // Add the authorization header to the headers array.
        $hash  = $this->apiKey                                 . '\n';
        $hash .= $method                                       . '\n';
        $hash .= $resource                                     . '\n';
        $hash .= $host                                         . '\n';
        $hash .= (isset ($dataString) ? md5($dataString) : '') . '\n';
        $hash .= $accept                                       . '\n';
        $hash .= $contentType                                  . '\n';
        $hash .= $contentLength                                . '\n';
        $hash .= $date                                         . '\n';

        $hash      = hash_hmac('sha256', $hash, $this->sharedKey, false);
        $headers[] = "Authorization: SharedKey $this->apiKey:$hash";

        // Set all headers of the headers array to the cURL session.
        $result = curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if (!$result) {
            throw new PDFrePROException(curl_error($curl), curl_errno($curl));
        }

        return $curl;
    }

    /**
     * Sends a request.
     *
     * @param  string  $resource   - The resource, which shall be requested.
     * @param  string  $method     - The HTTP method for the request.
     * @param ?object  $data       - The Data, which shall be sent with the request.
     * @param ?integer $httpCode   - This output parameter will hold the HTTP status code of the request.
     * @param  array   $validCodes - All valid HTTP status codes, which are expected for a success of this response.
     *
     * @return object - The response of the request.
     *
     * @throws PDFrePROException - If the request could not be sent, properly.
     */
    protected function sendRequest(
         string  $resource,
         string  $method     = 'GET',
        ?object  $data       = null,
        ?int    &$httpCode   = 0,
         array   $validCodes = [200]
    ): object {
        // Send the request.
        $response = $this->executeCurl($this->initializeCurl($resource, $method, $data), $httpCode);

        // Check, whether the HTTP status code indicates no content.
        if (204 === $httpCode) {
            $response = (object)['code' => 204, 'status' => 'success', 'data' => (object)[]]; // Provide a valid response body.
        }

        // Validate the response.
        $this->validateResponse($response, $validCodes);

        // Return the response's data.
        return $response->data;
    }
}
