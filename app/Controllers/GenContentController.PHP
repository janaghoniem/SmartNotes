<?php
require_once '../models/FileModel.php';

class FileGenController {
    private $fileModel;

    public function __construct($db) {
        $this->fileModel = new FileModel($db);
    }

    public function getFileContent($fileId) {
        return $this->fileModel->getFileContentById($fileId);
    }

    public function getFolderId($fileId) {
        return $this->fileModel->getFolderIdByFileId($fileId);
    }

    public function saveSummary($fileId, $summary) {
        return $this->fileModel->saveSummary($fileId, $summary);
    }

    public function generateSummary($text) {
        // Simulate API call to summarization service
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', 'http://localhost:3000/summarize', [
                'json' => ['prompt' => "Summarize the following text: $text"]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['summary'] ?? 'No summary available.';
        } catch (Exception $e) {
            return 'Error generating summary: ' . $e->getMessage();
        }
    }

    public function generateMCQs($text) {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', 'http://localhost:3000/summarize', [
                'json' => ['prompt' => "Generate MCQs: $text"]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['summary'] ?? 'No MCQs available.';
        } catch (Exception $e) {
            return 'Error generating MCQs: ' . $e->getMessage();
        }
    }

    public function generateQA($text) {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', 'http://localhost:3000/summarize', [
                'json' => ['prompt' => "Generate Q&A: $text"]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['summary'] ?? 'No Q&A available.';
        } catch (Exception $e) {
            return 'Error generating Q&A: ' . $e->getMessage();
        }
    }
}
