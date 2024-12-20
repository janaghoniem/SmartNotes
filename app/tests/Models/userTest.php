<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Config\Database;

class UserTest extends TestCase
{
    protected $dbMock;
    protected $mysqliMock;
    protected $stmtMock;

    protected function setUp(): void
    {
        // Mock the Database connection and mysqli object
        $this->dbMock = $this->createMock(Database::class);
        $this->mysqliMock = $this->createMock(\mysqli::class);
        $this->stmtMock = $this->createMock(\mysqli_stmt::class);

        // Simulate Database::getInstance()->getConnection() to return the mysqliMock
        $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);

        // Mock the prepare() method of mysqli to return the stmtMock
        $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);

        // Ensure the prepare method is called
        $this->stmtMock->expects($this->once())  // Expecting it to be called exactly once
            ->method('bind_param')                 // Mock bind_param method to do nothing
            ->willReturn(true);
        
        // Mock execute() to return true (indicating successful query execution)
        $this->stmtMock->method('execute')->willReturn(true);

        // Set the mock Database instance to be used in the User model
        Database::setInstance($this->dbMock);
    }

    public function testInsertUser()
    {
        // Sample data to insert
        $data = [
            'username' => 'testuser',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'country' => 'Test Country',
            'user_type' => 1
        ];

        // Call insertUser method and verify it returns true
        $result = User::insertUser($data);

        // Assert that the result is true (indicating successful insert)
        $this->assertTrue($result, "The user should be inserted successfully.");
    }
}
?>
