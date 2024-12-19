<?php
require_once '../models/file_class.php';
// $controller = new FileGenController();
// $controller->save();
class FileGenController {
    private $fileModel;

    public function __construct() {
        
    }

    // public function getFileContent($fileId) {
    //     return $this->fileModel->getFileContentById($fileId);
    // }

    // public function getFolderId($fileId) {
    //     return $this->fileModel->getFolderIdByFileId($fileId);
    // }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                isset($_POST['name']) &&
                isset($_POST['user_id']) &&
                isset($_POST['folder_id']) &&
                isset($_POST['content']) &&
                isset($_POST['file_type'])
            ) {
                $name = htmlspecialchars($_POST['name']);
                $user_id = (int) $_POST['user_id'];
                $folder_id = (int) $_POST['folder_id'];
                $content = json_decode($_POST['content'], true); // Decode JSON into an associative array
                $file_type = (int) $_POST['file_type'];
                $summary = $content['S'] ?? ''; // Default to an empty string if 'S' doesn't exist

                // Call the model function to save the data
                $note_id = file::create($name, $user_id, $folder_id, json_encode(['S' => $summary]), $file_type);

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
    

    public function generateSummary($text) {
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

    public function generateMCQ($text) {
        $client = new GuzzleHttp\Client();
        $mcq_prompt = "Generate many multiple-choice questions and their answers based on the following text: " . $text;

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

    public function generateQA($text) {
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
            header('Location: NEWflashcards.php');
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}

?>