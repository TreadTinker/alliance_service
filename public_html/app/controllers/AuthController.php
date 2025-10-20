<?php

class AdminReconciliationController
{
    private $auth;
    private $fileModel;
    private $candidateModel;
    private $logger;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->fileModel = new ReconciliationFile();
        $this->candidateModel = new Candidate();
        $this->logger = Logger::getInstance();

        log_info('AdminReconciliationController initialized');
    }

    /**
     * Список всех загруженных файлов сверок
     */
    public function index()
    {
        log_info('Admin reconciliation files index');

        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            log_warning('Unauthorized access attempt to reconciliation files');
            header('Location: /login?redirect=admin/reconciliation');
            exit;
        }

        // Параметры пагинации и поиска
        $page = $_GET['page'] ?? 1;
        $perPage = $_GET['per_page'] ?? 20;
        $search = $_GET['search'] ?? '';

        // Получаем файлы
        $files = $this->fileModel->getAllWithPagination($page, $perPage, $search);
        $totalCount = $this->fileModel->getTotalCount($search);
        $totalPages = ceil($totalCount / $perPage);

        // Статистика
        $stats = $this->fileModel->getStats();

        require_once __DIR__ . '/../views/admin/pages/reconciliation/index.php';
    }

    /**
     * Детальный просмотр файла сверки
     */
    public function show($id)
    {
        log_info("Showing reconciliation file {$id}");

        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            log_warning('Unauthorized access attempt to reconciliation file');
            header('Location: /login');
            exit;
        }

        $file = $this->fileModel->getWithCandidates($id);
        if (!$file) {
            log_warning("Reconciliation file not found: {$id}");
            header('Location: /admin/reconciliation');
            exit;
        }

        require_once __DIR__ . '/../views/admin/pages/reconciliation/show.php';
    }

    /**
     * Форма загрузки нового файла
     */
    public function create()
    {
        log_info('Reconciliation file create form');

        if (!$this->auth->isLoggedIn() || !$this->auth->isAdmin()) {
            log_warning('Unauthorized access attempt to upload reconciliation file');
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/admin/pages/reconciliation/create.php';
    }

    /**
     * Обработка загрузки файла
     */
    public function store()
    {
        log_info('Storing new reconciliation file');

        if (!$this->auth->isLoggedIn() || !$this->auth->isAdmin()) {
            log_warning('Unauthorized file upload attempt');
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            log_warning('Invalid method for file upload');
            header('Location: /admin/reconciliation/create');
            exit;
        }

        try {
            // Обработка загрузки файла
            $uploadResult = $this->handleFileUpload();
            
            // Создаем запись в БД
            $fileId = $this->fileModel->createFile([
                'file_name' => $uploadResult['original_name'],
                'file_path' => $uploadResult['file_path'],
                'file_size' => $uploadResult['file_size'],
                'description' => $_POST['description'] ?? '',
                'uploaded_by' => $this->auth->getUser()['id'],
                'records_count' => $uploadResult['records_count'],
                'period_from' => !empty($_POST['period_from']) ? $_POST['period_from'] : null,
                'period_to' => !empty($_POST['period_to']) ? $_POST['period_to'] : null,
                'status' => 'completed'
            ]);

            // Обрабатываем данные файла и связываем с кандидатами
            $candidateIds = $this->processFileData($uploadResult['file_path']);
            if (!empty($candidateIds)) {
                $this->fileModel->addCandidatesToFile($fileId, $candidateIds);
            }

            log_info("Reconciliation file uploaded successfully", [
                'file_id' => $fileId,
                'candidates_count' => count($candidateIds)
            ]);

            $_SESSION['success_message'] = 'Файл успешно загружен. Обработано записей: ' . $uploadResult['records_count'];
            header('Location: /admin/reconciliation');
            exit;

        } catch (Exception $e) {
            log_error('Error uploading reconciliation file', ['message' => $e->getMessage()]);
            
            $_SESSION['error_message'] = $e->getMessage();
            $_SESSION['form_data'] = $_POST;
            header('Location: /admin/reconciliation/create');
            exit;
        }
    }

    /**
     * Удаление файла сверки
     */
    public function delete($id)
    {
        log_info("Deleting reconciliation file {$id}");

        if (!$this->auth->isLoggedIn() || !$this->auth->isAdmin()) {
            log_warning('Unauthorized file delete attempt');
            header('Location: /login');
            exit;
        }

        try {
            $file = $this->fileModel->find($id);
            if ($file && file_exists($file['file_path'])) {
                unlink($file['file_path']); // Удаляем физический файл
            }

            $this->fileModel->deleteFile($id);
            
            log_info("Reconciliation file deleted successfully", ['id' => $id]);
            $_SESSION['success_message'] = 'Файл успешно удален';
            
        } catch (Exception $e) {
            log_error('Error deleting reconciliation file', ['message' => $e->getMessage()]);
            $_SESSION['error_message'] = 'Ошибка при удалении файла';
        }

        header('Location: /admin/reconciliation');
        exit;
    }

    /**
     * Скачивание файла
     */
    public function download($id)
    {
        log_info("Downloading reconciliation file {$id}");

        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            log_warning('Unauthorized file download attempt');
            header('Location: /login');
            exit;
        }

        $file = $this->fileModel->find($id);
        if (!$file || !file_exists($file['file_path'])) {
            log_warning("File not found for download: {$id}");
            header('Location: /admin/reconciliation');
            exit;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file['file_name'] . '"');
        header('Content-Length: ' . $file['file_size']);
        readfile($file['file_path']);
        exit;
    }

    /**
     * Вспомогательные методы
     */
    private function handleFileUpload()
    {
        if (!isset($_FILES['reconciliation_file']) || $_FILES['reconciliation_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Ошибка загрузки файла');
        }

        $file = $_FILES['reconciliation_file'];
        $allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Разрешены только CSV и Excel файлы');
        }

        // Создаем директорию для загрузок если не существует
        $uploadDir = __DIR__ . '/../../uploads/reconciliation/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Генерируем уникальное имя файла
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $file['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Ошибка сохранения файла');
        }

        $recordsCount = $this->countRecordsInFile($filePath);

        return [
            'original_name' => $file['name'],
            'file_path' => $filePath,
            'file_size' => $file['size'],
            'records_count' => $recordsCount
        ];
    }

    private function processFileData($filePath)
    {
        // Здесь будет логика обработки файла и извлечения candidate_id
        // Пока возвращаем пустой массив для демонстрации
        $candidateIds = [];
        
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ',');
            
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // Предполагаем, что в файле есть колонка с courier_id
                if (!empty($data[0])) {
                    $courierId = trim($data[0]);
                    $candidate = $this->candidateModel->getByCourierId($courierId);
                    if ($candidate) {
                        $candidateIds[] = $candidate['id'];
                    }
                }
            }
            fclose($handle);
        }
        
        return array_unique($candidateIds);
    }

    private function countRecordsInFile($filePath)
    {
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $count = 0;
            while (fgetcsv($handle, 1000, ',')) {
                $count++;
            }
            fclose($handle);
            return max(0, $count - 1); // Минус заголовок
        }
        return 0;
    }
}