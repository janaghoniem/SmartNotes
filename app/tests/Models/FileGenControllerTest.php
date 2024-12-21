<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\FileGenController;
use App\Models\file;
use App\Config\Database;
use Psr\Http\Message\RequestInterface;
use RequestException;

require_once __DIR__ . '/../../models/file_class.php';
class FileGenControllerTest extends TestCase
{
    private $controller;
    private $fileModelMock;
    private $dbMock;
    private $mysqliMock;
    private $clientMock;
    protected function setUp(): void
    {
        // Mock the Database class
       // $this->clientMock = $this->createMock(Client::class);
        $this->dbMock = $this->createMock(Database::class);
        $this->mysqliMock = $this->createMock(\mysqli::class);

        // Set up the mock connection to return the mock mysqli connection
        $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);

        // Set the mock instance to the Database class
        Database::setInstance($this->dbMock);

        // Mock the File class
        $this->fileModelMock = $this->createMock(File::class);

        $this->controller = new FileGenController();
        $this->controller->setFileModel($this->fileModelMock);
    }

    // public function testSaveValidData()
    // {
    //     $_SERVER['REQUEST_METHOD'] = 'POST';
    //     $_POST['name'] = 'Test File';
    //     $_POST['user_id'] = 1;
    //     $_POST['folder_id'] = 2;
    //     $_POST['content'] = json_encode(['S' => 'This is a test summary.']);
    //     $_POST['file_type'] = 1;

    //     // Mock the create method to return a fixed note ID
    //     $this->fileModelMock->method('create')->willReturn(123);

    //     // Mock the prepare method of mysqli
    //     $stmtMock = $this->createMock(\mysqli_stmt::class);
    //     $this->mysqliMock->method('prepare')->willReturn($stmtMock);

    //     // Mock the bind_param and execute methods of mysqli_stmt
    //     $stmtMock->method('bind_param')->willReturn(true);
    //     $stmtMock->method('execute')->willReturn(true);

    //     // Mock the insert_id method using a closure
    //     $this->mysqliMock->method('insert_id')->willReturn(123);

    //     $stmtMock->method('close')->willReturn(true);

    //     $response = $this->controller->save();

    //     $this->assertStringContainsString("Summary saved successfully! Note ID: 123", $response);

    // }

    // Test invalid request method
    public function testSaveInvalidRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET'; // Simulate a non-POST request

        $response = $this->controller->save();

        $this->assertStringContainsString("Invalid request method.", $response);
    }

    // Test missing POST parameters
    public function testSaveMissingParameters()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        // Missing 'name' parameter
        $_POST['user_id'] = 1;
        $_POST['folder_id'] = 2;
        $_POST['content'] = json_encode(['S' => 'This is a test summary.']);
        $_POST['file_type'] = 1;

        $response = $this->controller->save();

        $this->assertStringContainsString("Invalid data received.", $response);
    }

    // Test when the create method fails
    // public function testSaveCreateFails()
    // {
    //     $_SERVER['REQUEST_METHOD'] = 'POST';
    //     $_POST['name'] = 'Test File';
    //     $_POST['user_id'] = 1;
    //     $_POST['folder_id'] = 2;
    //     $_POST['content'] = json_encode(['S' => 'This is a test summary.']);
    //     $_POST['file_type'] = 1;

    //     // Mock the create method to return false (simulating a failure)
    //     $this->fileModelMock->method('create')->willReturn(false);

    //     $response = $this->controller->save();

    //     $this->assertStringContainsString("Error saving the summary.", $response);
    // }



    // public function testGenerateSummarySuccess()
    // {
    //     $text = "This is a test text.";
    //     $expectedSummary = "This is a summary.";

    //     // Mock the response
    //     $response = new Response(200, [], json_encode(['summary' => $expectedSummary]));
    //     $this->clientMock->method('request')->willReturn($response);

    //     // Inject the mock client into the controller
    //     $this->controller->setHttpClient($this->clientMock);

    //     $summary = $this->controller->generateSummary($text);

    //     $this->assertEquals($expectedSummary, $summary);
    // }

    // public function testGenerateSummaryError()
    // {
    //     $text = "This is a test text.";
    //     $expectedErrorMessage = "Error: Request failed";

    //     // Mock the exception
    //     $exception = new Exception($expectedErrorMessage);
    //     $this->clientMock->method('request')->willThrowException($exception);

    //     // Inject the mock client into the controller
    //     $this->controller->setHttpClient($this->clientMock);

    //     $summary = $this->controller->generateSummary($text);

    //     $this->assertStringContainsString("Error: ", $summary);
    // }

    // public function testSaveWithInvalidData()
    // {
    //     $_SERVER['REQUEST_METHOD'] = 'POST';
    //     $_POST = [];

    //     $result = $this->controller->save();
    //     $this->assertStringContainsString('Invalid data received.', $result);
    // }

    // public function testSaveWithInvalidRequestMethod()
    // {
    //     $_SERVER['REQUEST_METHOD'] = 'GET';

    //     $result = $this->controller->save();
    //     $this->assertStringContainsString('Invalid request method.', $result);
    // }


}

?>