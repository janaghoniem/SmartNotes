<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Config\Database;

class UserTest extends TestCase
{
    protected $dbMock;
    protected $mysqliMock;
    protected $stmtMock;
    protected $resultMock;
    private $userFactory;

    protected function setUp(): void
    {
        $this->dbMock = $this->createMock(Database::class);
        $this->mysqliMock = $this->createMock(\mysqli::class);
        $this->stmtMock = $this->createMock(\mysqli_stmt::class);

        $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);
        $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('bind_param')->willReturn(true);
        $this->stmtMock->method('execute')->willReturn(true);

        Database::setInstance($this->dbMock);



        
    }

    

    public function testInsertUser()
    {
        $data = [
            'username' => 'testuser',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'country' => 'Test Country',
            'user_type' => 1
        ];

        $result = User::insertUser($data);
        $this->assertTrue($result, "The user should be inserted successfully.");
    }

    public function testSingletonBehavior()
    {
        $reflection = new \ReflectionClass(User::class);
        $user = $reflection->newInstanceWithoutConstructor();

        User::setInstance($user);

        $this->assertSame($user, User::getInstance(), "getInstance should return the set instance.");
        User::clearInstance();
        $this->assertNull(User::getInstance(), "getInstance should return null after clearInstance is called.");
    }

    // public function testLoadUser()
    // {
    //     $sampleData = [
    //         'id' => 1,
    //         'username' => 'testuser',
    //         'first_name' => 'Test',
    //         'last_name' => 'User',
    //         'email' => 'testuser@example.com',
    //         'password' => 'hashedpassword',
    //         'country' => 'Test Country',
    //         'user_type' => 1,
    //         'created_at' => '2024-01-01'
    //     ];

    //     $resultMock = $this->createMock(\mysqli_result::class);
    //     $resultMock->method('fetch_assoc')->willReturn($sampleData);

    //     $this->stmtMock->method('get_result')->willReturn($resultMock);

    //     $user = User::createForTesting(1);
    //     $user->loadUser(1);

    //     $this->assertEquals($sampleData['username'], $user->username, "Username should match the loaded data.");
    // }

    // public function testGetUserById()
    // {
    //     $resultMock = $this->createMock(\mysqli_result::class);
    //     $resultMock->method('fetch_assoc')->willReturn([
    //         'id' => 1,
    //         'username' => 'testuser',
    //         'first_name' => 'Test',
    //         'last_name' => 'User',
    //         'email' => 'testuser@example.com',
    //         'password' => 'hashedpassword',
    //         'country' => 'Test Country',
    //         'user_type' => 1
    //     ]);

    //     $this->stmtMock->method('get_result')->willReturn($resultMock);

    //     $user = User::createForTesting(1);
    //     $user->loadUserById(1);

    //     $this->assertEquals(1, $user->id, "The user ID should match the expected value.");
    // }

    public function testUpdateUser()
    {
        // Mock database as before
        $this->dbMock = $this->createMock(Database::class);
        $this->mysqliMock = $this->createMock(\mysqli::class);
        $this->stmtMock = $this->createMock(\mysqli_stmt::class);
    
        $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);
        $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->once())
            ->method('bind_param')
            ->with(
                "ssssssii",
                "updatedUsername",
                "UpdatedFirstName",
                "UpdatedLastName",
                "updatedemail@example.com",
                "newHashedPassword",
                "UpdatedCountry",
                2,
                1
            )
            ->willReturn(true);
        $this->stmtMock->method('execute')->willReturn(true);
        Database::setInstance($this->dbMock);
    
        // Use createForTesting to instantiate the User object
        $user = User::createForTesting([
            'id' => 1,
            'username' => "updatedUsername",
            'first_name' => "UpdatedFirstName",
            'last_name' => "UpdatedLastName",
            'email' => "updatedemail@example.com",
            'password' => "newHashedPassword",
            'country' => "UpdatedCountry",
            'userType_obj' => (object) ['id' => 2],
        ]);
    
        $result = $user->updateUser();
        $this->assertTrue($result, "updateUser should return true for successful execution.");
    }
    










    public function testGetUserByEmail()
{
    // Mock the Database and its dependencies
    $this->dbMock = $this->createMock(Database::class);
    $this->mysqliMock = $this->createMock(\mysqli::class);
    $this->stmtMock = $this->createMock(\mysqli_stmt::class);
    $this->resultMock = $this->createMock(\mysqli_result::class);

    $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);
    $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);

    // Mock bind_param to do nothing and return true
    $this->stmtMock->method('bind_param')->willReturn(true);

    // Mock execute to return true
    $this->stmtMock->method('execute')->willReturn(true);

    // Mock get_result to return the mocked result set
    $this->stmtMock->method('get_result')->willReturn($this->resultMock);

    // Simulate a valid user record
    $sampleData = [
        'id' => 1,
        'username' => 'testuser',
        'email' => 'testuser@example.com',
    ];

    $this->resultMock->method('fetch_assoc')->willReturn($sampleData);

    // Set the mock Database instance
    Database::setInstance($this->dbMock);

    // Call the method under test
    $user = User::getUserByEmail('testuser@example.com');

    // Verify the result
    $this->assertInstanceOf(User::class, $user, "getUserByEmail should return an instance of User.");
    $this->assertEquals(1, $user->id, "The returned User's ID should match the expected value.");
}

public function testGetUserByEmailNotFound()
{
    // Mock the Database and its dependencies
    $this->dbMock = $this->createMock(Database::class);
    $this->mysqliMock = $this->createMock(\mysqli::class);
    $this->stmtMock = $this->createMock(\mysqli_stmt::class);
    $this->resultMock = $this->createMock(\mysqli_result::class);

    $this->dbMock->method('getConnection')->willReturn($this->mysqliMock);
    $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);

    // Mock bind_param to do nothing and return true
    $this->stmtMock->method('bind_param')->willReturn(true);

    // Mock execute to return true
    $this->stmtMock->method('execute')->willReturn(true);

    // Mock get_result to return the mocked result set
    $this->stmtMock->method('get_result')->willReturn($this->resultMock);

    // Simulate no user found
    $this->resultMock->method('fetch_assoc')->willReturn(null);

    // Set the mock Database instance
    Database::setInstance($this->dbMock);

    // Call the method under test
    $user = User::getUserByEmail('nonexistent@example.com');

    // Verify the result
    $this->assertNull($user, "getUserByEmail should return null when the user is not found.");
}


public function testDeleteUser()
{
    // Create a mock User object
    $user = $this->createMock(User::class);
    $user->id = 1; // Set the user ID

    // Mock the bind_param() method to do nothing (return true)
    $this->stmtMock->method('bind_param')->willReturn(true);

    // Mock execute() to return true (indicating successful execution)
    $this->stmtMock->method('execute')->willReturn(true);

    // Call the deleteUser method under test
    $result = User::deleteUser($user);

    // Assert that the result is true (indicating the user was successfully deleted)
    $this->assertTrue($result, "The user should be deleted successfully.");
}




// public function testGetAllUsers()
// {
//     $users = $this->userFactory::getAllUsers(2); // Get all users with user_type = 2

//     // Assert that we get the correct array of users
//     $this->assertCount(1, $users);
//     $this->assertInstanceOf(User::class, $users[0]);

//     // Check if the user properties match the mock data
//     $this->assertEquals(1, $users[0]->id);
//     $this->assertEquals('user1', $users[0]->username);
//     $this->assertEquals('user1@example.com', $users[0]->email);
// }
}


?>