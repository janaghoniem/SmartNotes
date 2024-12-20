<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use App\Models\User;
use App\Config\Database;

class UserControllerTest extends TestCase {
    protected $userController;
    protected $dbMock;
    protected $mysqliMock;
    protected $stmtMock;
    protected $resultMock;

    protected function setUp(): void {
        // Start the session and mock the $_SESSION variable
        $_SESSION = []; // Clear the session for each test

        // Mock the Database class' getInstance method directly
        $this->dbMock = $this->createMock(Database::class);
        $this->mysqliMock = $this->createMock(\mysqli::class); // Mock mysqli

        // Simulate Database connection being returned
        $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);

        // Mock the prepare() method of mysqli
        $this->stmtMock = $this->createMock(\mysqli_stmt::class);
        $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);

        // Mock execute
        $this->stmtMock->method('execute')->willReturn(true);

        // Create a mock of mysqli_result and mock fetch_assoc()
        $this->resultMock = $this->createMock(\mysqli_result::class);
        $this->resultMock->method('fetch_assoc')->willReturn([
            'id' => 1,
            'email' => 'shahdemad@gmail.com',
            'password' => password_hash('SH12345678', PASSWORD_DEFAULT),
            'user_type' => 2
        ]);

        // Ensure that get_result returns a valid mysqli_result object (not null)
        $this->stmtMock->method('get_result')->willReturn($this->resultMock);

        // Inject the mock Database instance
        Database::setInstance($this->dbMock);

        // Initialize the UserController
        $this->userController = new UserController();
    }

    public function testLogin() {
        // Simulate POST request data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email1'] = 'shahdemad@gmail.com';
        $_POST['password1'] = 'SH12345678';

        // Simulate a logged-in user with a session value for UserID
        $_SESSION['UserID'] = 58;

        // Mock the User singleton to return a user object
        $userMock = $this->createMock(User::class);
        $userMock->method('getInstance')->willReturn($userMock);
        $userMock->id = 1; // Set the mock user's ID

        // Mock User::setInstance to set the mock user
        User::setInstance($userMock);

        // Start output buffering to capture the redirect header
        ob_start();
        
        // Execute the login function from UserController
        $result = $this->userController->login();
        
        // Get the output
        $output = ob_get_clean();

        // Assertions
        $this->assertNotNull($result, "The login function should return a user object on success.");
        $this->assertEquals(1, $result->id, "User ID should match the mocked ID.");
        $this->assertStringContainsString('Location: /smartnotes/app/Views/dashboard.php', $output, "Redirect location should match.");
    }
}


?>