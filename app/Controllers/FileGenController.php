<?php


use App\Models\file;
// require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Models/file_class.php';


// $controller = new FileGenController();
// $controller->save();
class FileGenController
{
    private $fileModel;

    public function __construct()
    {

    }

    // public function getFileContent($fileId) {
    //     return $this->fileModel->getFileContentById($fileId);
    // }

    // public function getFolderId($fileId) {
    //     return $this->fileModel->getFolderIdByFileId($fileId);
    // }

    public function setFileModel($fileModel)
    {
        $this->fileModel = $fileModel;
    }
    public function setHttpClient($client)
    {
        $this->httpClient = $client;
    }

    public function save()
    {
        $conn = new mysqli('localhost', 'root', '', 'smartnotes_db');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                isset($_POST['name'], $_POST['user_id'], $_POST['folder_id'], $_POST['content'], $_POST['file_type'])
            ) {
                $name = htmlspecialchars($_POST['name']);
                $user_id = (int) $_POST['user_id'];
                $folder_id = (int) $_POST['folder_id'];
                $content = json_decode($_POST['content'], true);
                $file_type = (int) $_POST['file_type'];
                $summary = $content['S'] ?? '';

                $escaped_content = $conn->real_escape_string(json_encode(['S' => $summary]));

                $note_id = file::create($name, $user_id, $folder_id, $escaped_content, $file_type);

                if ($note_id) {
                    return "<div>Summary saved successfully! Note ID: $note_id</div>";
                } else {
                    return "<div>Error saving the summary.</div>";
                }
            } else {
                return "<div>Invalid data received.</div>";
            }
        } else {
            return "<div>Invalid request method.</div>";
        }
    }

    public function saveQA()
    {
        $conn = new mysqli('localhost', 'root', '', 'smartnotes_db');
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if all the required fields are set
            if (
                isset($_POST['name'], $_POST['user_id'], $_POST['folder_id'], $_POST['content'], $_POST['file_type'])
            ) {
                // Debug: Print the form data to see if everything is being passed correctly
                error_log('Form data: ' . print_r($_POST, true));
    
                $name = htmlspecialchars($_POST['name']);
                $user_id = (int) $_POST['user_id'];
                $folder_id = (int) $_POST['folder_id'];
                
                $content = json_decode($_POST['content'], true);
                $file_type = (int) $_POST['file_type'];
    
                // Extract QA content
                $qa = $content['QA'] ?? '';
    
                // Escape the content for safety
                $escaped_content = $conn->real_escape_string(json_encode(['QA' => $qa]));
    
                // Use the file class to save the file data
                $note_id = file::create($name, $user_id, $folder_id, $escaped_content, $file_type);
    
                if ($note_id) {
                    return "<div>Q&A saved successfully! Note ID: $note_id</div>";
                } else {
                    return "<div>Error saving the Q&A.</div>";
                }
            } else {
                return "<div>Invalid data received.</div>";
            }
        } else {
            return "<div>Invalid request method.</div>";
        }
    }
    
    

    public function generateSummary($text)
    {
        $client = new GuzzleHttp\Client();
        $prompt = "summarize the following text: " . $text;

        try {
            $response = $client->request('POST', 'http://localhost:3000/summarize', [
                'json' => [
                    'prompt' => $prompt
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['summary'] ?? 'No summary available';
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function generateMCQ($text)
    {
        $client = new GuzzleHttp\Client();
        $mcq_prompt = "Generate many multiple-choice questions and their answers based on the following text: " . $text . " Format: 
        **Question x:** 
        - Question text 
        a) Option A 
        b) Option B 
        c) Option C 
        d) Option D 
        **Answer: c)**";

        try {
            $response = $client->request('POST', 'http://localhost:3000/summarize', [
                'json' => [
                    'prompt' => $mcq_prompt
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            $_SESSION['mcq'] = $data['summary'] ?? 'No multiple-choice questions available';
            header('Location: mcqquiz.php');
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function generateQA($text)
    {
        $client = new GuzzleHttp\Client();
        $qa_prompt = "Generate questions and answers from the following text: " . $text . "\nPlease format the output as follows: \nQuestion 1: <question text>\nAnswer 1: <answer text>\nQuestion 2: <question text>\nAnswer 2: <answer text>";

        try {
            $response = $client->request('POST', 'http://localhost:3000/summarize', [
                'json' => [
                    'prompt' => $qa_prompt
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            $_SESSION['qa'] = $data['summary'] ?? 'No questions and answers available';
            header('Location: ../Views/NEWflashcards.php');
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

?>