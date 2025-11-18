<?php

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                             Required Files                                                             \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

require_once (__DIR__ . '/../../src/PDFrePRO.class.php');
require_once (__DIR__ . '/../../src/PDFrePROException.class.php');

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                                 Usages                                                                 \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

use PHPUnit\Framework\TestCase;

//****************************************************************************************************************************************\\
//                                                                                                                                        \\
//                                                               Test Class                                                               \\
//                                                                                                                                        \\
//****************************************************************************************************************************************\\

/**
 * @see PDFrePRO
 */
class PDFrePROTest extends TestCase
{
    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                            General Tests                                                           \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     */
    public function testConstructorWithValidApiKeyAndValidSharedKey(): void
    {
        // Run the test.
        new PDFrePRO('8244ead107b08deea5fe', '7244ead107b08deea5fe8a785a06ee98ef7f2b333435a7c0323fe7d070124582');
    }

    /**
     */
    public function testConstructorWithValidApiKeyAndInvalidSharedKey(): void
    {
        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        new PDFrePRO('8244ead107b08deea5fe', 'This shared key is invalid!');
    }

    /**
     */
    public function testConstructorWithInvalidApiKeyAndValidSharedKey(): void
    {
        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        new PDFrePRO('This API key is invalid!', '7244ead107b08deea5fe8a785a06ee98ef7f2b333435a7c0323fe7d070124582');
    }

    /**
     */
    public function testConstructorWithInvalidApiKeyAndInvalidSharedKey(): void
    {
        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        new PDFrePRO('This API key is invalid!', 'This shared key is invalid, too!');
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                       Tests for Placeholders                                                       \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * @throws PDFrePROException
     */
    public function testFunctionCreatePlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => '/v3/placeholders/03129a759ad8bf8a87a50a883dad53dc152c9092'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 201, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $url = $pdfrepro->createPlaceholder('Test-Name', 'Test-Data');

        // Assert the test.
        $this->assertEquals($data->url, $url);
    }

    /**
     */
    public function testFunctionCreatePlaceholderWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->createPlaceholder('Test-Name', 'Test-Data');
    }

    /**
     */
    public function testFunctionCreatePlaceholderWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->createPlaceholder('Test-Name', 'Test-Data');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetPlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)[
            'id'                          => '03129a759ad8bf8a87a50a883dad53dc152c9092',
            'name'                        => 'Test-Name',
            'lastModificationDate'        => '2017-08-31',
            'numberOfReferencedTemplates' => 0,
            'rawData'                     => 'Test-Data'
        ];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $placeholder = $pdfrepro->getPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data, $placeholder);
    }

    /**
     */
    public function testFunctionGetPlaceholderWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getPlaceholder('Test-ID');
    }

    /**
     */
    public function testFunctionGetPlaceholderWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getPlaceholder('Test-ID');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetTemplatesByPlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['templates' => [(object)[
            'id'                   => '03129a759ad8bf8a87a50a883dad53dc152c9092',
            'name'                 => 'Test-Name',
            'description'          => 'Test-Description',
            'lastModificationDate' => '2017-08-31'
        ]]];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $templates = $pdfrepro->getTemplatesByPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data->templates, $templates);
    }

    /**
     */
    public function testFunctionGetTemplatesByPlaceholderWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getTemplatesByPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionGetTemplatesByPlaceholderWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getTemplatesByPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetAllPlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['placeholders' => [(object)[
            'id'                          => '03129a759ad8bf8a87a50a883dad53dc152c9092',
            'name'                        => 'Test-Name',
            'lastModificationDate'        => '2017-08-31',
            'numberOfReferencedTemplates' => 0
        ]]];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $placeholders = $pdfrepro->getAllPlaceholders();

        // Assert the test.
        $this->assertEquals($data->placeholders, $placeholders);
    }

    /**
     */
    public function testFunctionGetAllPlaceholdersWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getAllPlaceholders();
    }

    /**
     */
    public function testFunctionGetAllPlaceholdersWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getAllPlaceholders();
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionUpdatePlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => '/v3/placeholders/03129a759ad8bf8a87a50a883dad53dc152c9092'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $success = $pdfrepro->updatePlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092', 'Test-Name', '{"Test":"Data"}');

        // Assert the test.
        $this->assertTrue($success);
    }

    /**
     */
    public function testFunctionUpdatePlaceholderWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->updatePlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092', 'Test-Name', '{"Test":"Data"}');
    }

    /**
     */
    public function testFunctionUpdatePlaceholderWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->updatePlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092', 'Test-Name', '{"Test":"Data"}');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionCopyPlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => '/v3/placeholders/03129a759ad8bf8a87a50a883dad53dc152c9092'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 201, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $url = $pdfrepro->copyPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data->url, $url);
    }

    /**
     */
    public function testFunctionCopyPlaceholderWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->copyPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionCopyPlaceholderWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->copyPlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionDeletePlaceholderWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 204, 'status' => 'success', 'data' => (object)[]]);

        // Run the test.
        $success = $pdfrepro->deletePlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertTrue($success);
    }

    /**
     */
    public function testFunctionDeletePlaceholderWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->deletePlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionDeletePlaceholderWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->deletePlaceholder('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    //************************************************************************************************************************************\\
    //                                                                                                                                    \\
    //                                                         Tests for Templates                                                        \\
    //                                                                                                                                    \\
    //************************************************************************************************************************************\\

    /**
     * @throws PDFrePROException
     */
    public function testFunctionCreateTemplateWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => '/v3/templates/03129a759ad8bf8a87a50a883dad53dc152c9092'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 201, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $url = $pdfrepro->createTemplate('Test-Name');

        // Assert the test.
        $this->assertEquals($data->url, $url);
    }

    /**
     */
    public function testFunctionCreateTemplateWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->createTemplate('Test-Name');
    }

    /**
     */
    public function testFunctionCreateTemplateWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->createTemplate('Test-Name');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetTemplateWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)[
            'id'                   => '03129a759ad8bf8a87a50a883dad53dc152c9092',
            'name'                 => 'Test-Name',
            'description'          => 'Test-Description',
            'lastModificationDate' => '2017-08-31'
        ];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $template = $pdfrepro->getTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data, $template);
    }

    /**
     */
    public function testFunctionGetTemplateWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getTemplate('Test-ID');
    }

    /**
     */
    public function testFunctionGetTemplateWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getTemplate('Test-ID');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetPlaceholdersByTemplateWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['placeholders' => [(object)[
            'id'                          => '03129a759ad8bf8a87a50a883dad53dc152c9092',
            'name'                        => 'Test-Name',
            'lastModificationDate'        => '2017-08-31',
            'numberOfReferencedTemplates' => 0
        ]]];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $placeholders = $pdfrepro->getPlaceholdersByTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data->placeholders, $placeholders);
    }

    /**
     */
    public function testFunctionGetPlaceholdersByTemplateWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getPlaceholdersByTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionGetPlaceholdersByTemplateWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getPlaceholdersByTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetAllTemplatesWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['templates' => [(object)[
            'id'                   => '03129a759ad8bf8a87a50a883dad53dc152c9092',
            'name'                 => 'Test-Name',
            'description'          => 'Test-Description',
            'lastModificationDate' => '2017-08-31'
        ]]];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $templates = $pdfrepro->getAllTemplates();

        // Assert the test.
        $this->assertEquals($data->templates, $templates);
    }

    /**
     */
    public function testFunctionGetAllTemplatesWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getAllTemplates();
    }

    /**
     */
    public function testFunctionGetAllTemplatesWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getAllTemplates();
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetEditorUrlWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => 'https://editor.pdfrepro.de/'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $url = $pdfrepro->getEditorUrl('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data->url, $url);
    }

    /**
     */
    public function testFunctionGetEditorUrlWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getEditorUrl('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionGetEditorUrlWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getEditorUrl('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionGetPDFWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['pdf' => 'Base64-encoded PDF string'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 201, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $pdf = $pdfrepro->getPDF('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data->pdf, $pdf);
    }

    /**
     */
    public function testFunctionGetPDFWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->getPDF('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionGetPDFWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->getPDF('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionUpdateTemplateWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => '/v3/templates/03129a759ad8bf8a87a50a883dad53dc152c9092'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 200, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $success = $pdfrepro->updateTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092', 'Test-Name');

        // Assert the test.
        $this->assertTrue($success);
    }

    /**
     */
    public function testFunctionUpdateTemplateWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->updateTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092', 'Test-Name');
    }

    /**
     */
    public function testFunctionUpdateTemplateWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->updateTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092', 'Test-Name');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionCopyTemplateWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $data     = (object)['url' => '/v3/templates/03129a759ad8bf8a87a50a883dad53dc152c9092'];
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 201, 'status' => 'success', 'data' => $data]);

        // Run the test.
        $url = $pdfrepro->copyTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertEquals($data->url, $url);
    }

    /**
     */
    public function testFunctionCopyTemplateWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->copyTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionCopyTemplateWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->copyTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     * @throws PDFrePROException
     */
    public function testFunctionDeleteTemplateWithValidSuccessResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['code' => 204, 'status' => 'success', 'data' => (object)[]]);

        // Run the test.
        $success = $pdfrepro->deleteTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');

        // Assert the test.
        $this->assertTrue($success);
    }

    /**
     */
    public function testFunctionDeleteTemplateWithValidErrorResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)[
            'code'    => 500,
            'status'  => 'fail',
            'message' => 'Something went wrong!',
            'data'    => 'Internal Server Error'
        ]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);
        $this->expectExceptionCode(500);

        // Run the test.
        $pdfrepro->deleteTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }

    /**
     */
    public function testFunctionDeleteTemplateWithInvalidResponse(): void
    {
        // Prepare the test.
        $pdfrepro = $this->getMockBuilder(PDFrePRO::class)->disableOriginalConstructor()->onlyMethods(['executeCurl'])->getMock();

        $pdfrepro->method('executeCurl')->willReturn((object)['ode' => 200, 'tatus' => 'success', 'ata' => (object)[]]);

        // Assert the test.
        $this->expectException(PDFrePROException::class);

        // Run the test.
        $pdfrepro->deleteTemplate('03129a759ad8bf8a87a50a883dad53dc152c9092');
    }
}
