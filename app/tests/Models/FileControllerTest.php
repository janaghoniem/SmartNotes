<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\FileController;
use App\Models\Note;

class FileControllerTest extends TestCase
{
    private $controller;
    private $noteMock;

    protected function setUp(): void
    {
        // Mock the Note model
        $this->noteMock = $this->createMock(Note::class);

        // Inject the mock into the controller using the setter method
        $this->controller = new FileController();
        $this->controller->setFileModel($this->noteMock);
    }

    public function testGetFileContentSuccess()
    {
        $fileId = 1;
        $expectedContent = "This is the file content.";

        // Mock the getContentById method to return the expected content
        $this->noteMock->method('getContentById')->willReturn($expectedContent);

        $content = $this->controller->getFileContent($fileId);

        $this->assertEquals($expectedContent, $content);
    }

    public function testGetFileContentNotFound()
    {
        $fileId = 1;

        // Mock the getContentById method to return null
        $this->noteMock->method('getContentById')->willReturn(null);

        $content = $this->controller->getFileContent($fileId);

        $this->assertNull($content);
    }

    public function testSaveFileContentSuccess()
    {
        $fileId = 1;
        $content = "Updated file content.";

        // Mock the updateFileContent method to return true
        $this->noteMock->method('updateFileContent')->willReturn(true);

        $result = $this->controller->saveFileContent($fileId, $content);

        $this->assertTrue($result);
    }

    public function testSaveFileContentFailure()
    {
        $fileId = 1;
        $content = "Updated file content.";

        // Mock the updateFileContent method to return false
        $this->noteMock->method('updateFileContent')->willReturn(false);

        $result = $this->controller->saveFileContent($fileId, $content);

        $this->assertFalse($result);
    }
}
?>