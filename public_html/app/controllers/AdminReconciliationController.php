<?php

// Подключаем логгер
require_once __DIR__ . '/../core/Logger.php';
// Подключаем PHPSpreadsheet
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AdminReconciliationController
{
    private $auth;
    private $fileModel;
    private $candidateModel;
    private $verificationModel;
    private $logger;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->fileModel = new ReconciliationFile();
        $this->candidateModel = new Candidate();
        $this->verificationModel = new VerificationData();
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

            // Обрабатываем данные файла - теперь кандидаты создаются ПЕРВЫМИ
            $processedData = $this->processFileData($uploadResult['file_path'], $uploadResult['extension']);

            // Связываем кандидатов с файлом
            if (!empty($processedData['courier_ids'])) {
                $candidateIds = $this->fileModel->getCandidateIdsByCourierIds($processedData['courier_ids']);
                $this->fileModel->addCandidatesToFile($fileId, $candidateIds);
            }

            log_info("Reconciliation file uploaded successfully", [
                'file_id' => $fileId,
                'records_processed' => $processedData['records_processed'],
                'candidates_linked' => count($candidateIds ?? [])
            ]);

            $_SESSION['success_message'] = sprintf(
                'Файл успешно загружен. Обработано записей: %d.',
                $processedData['records_processed']
            );

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
        $allowedTypes = [
            'text/csv',
            'application/vnd.ms-excel',
            'text/plain',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/octet-stream',
            'application/vnd.ms-excel.sheet.macroEnabled.12'
        ];

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Разрешены только CSV и Excel файлы. Получен тип: ' . $file['type']);
        }

        // Проверяем расширение файла
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['csv', 'xlsx', 'xls'])) {
            throw new Exception('Недопустимое расширение файла. Разрешены: .csv, .xlsx, .xls');
        }

        // Создаем директорию для загрузок если не существует
        $uploadDir = __DIR__ . '/../../uploads/reconciliation/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Генерируем уникальное имя файла
        $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Ошибка сохранения файла');
        }

        $recordsCount = $this->countRecordsInFile($filePath, $extension);

        return [
            'original_name' => $file['name'],
            'file_path' => $filePath,
            'file_size' => $file['size'],
            'records_count' => $recordsCount,
            'extension' => $extension
        ];
    }

    private function processFileData($filePath, $extension)
    {
        $recordsProcessed = 0;
        $courierIds = [];
        $records = [];

        try {
            log_info("Starting file processing", ['file' => $filePath, 'extension' => $extension]);

            if ($extension === 'csv') {
                $data = $this->parseCsvFile($filePath);
            } else {
                $data = $this->parseExcelFile($filePath);
            }

            log_info("File parsed successfully", ['rows_found' => count($data)]);

            // ШАГ 1: Сначала создаем всех кандидатов
            log_info("Step 1: Creating candidates");
            $newCandidatesCount = $this->createMissingCandidates($data);
            log_info("Candidates creation completed", ['new_candidates' => $newCandidatesCount]);

            // ШАГ 2: Затем сохраняем данные верификации
            log_info("Step 2: Saving verification data");
            foreach ($data as $index => $row) {
                if (empty($row['courier_id'])) {
                    log_warning("Empty courier_id in row", ['row_index' => $index]);
                    continue;
                }

                log_info("Processing row", [
                    'row_index' => $index,
                    'courier_id' => $row['courier_id'],
                    'full_name' => $row['full_name'],
                    'worked_hours' => $row['worked_hours'],
                    'total_amount' => $row['total_amount']
                ]);

                // Сохраняем данные в verification_data
                try {
                    $verificationData = [
                        'courier_id' => $row['courier_id'],
                        'worked_hours' => $row['worked_hours'] ?? 0,
                        'orders_count' => $row['orders_count'] ?? 0,
                        'hours_own_bike' => $row['hours_own_bike'] ?? 0,
                        'hours_electric_bike' => $row['hours_electric_bike'] ?? 0,
                        'hours_yandex_bike' => $row['hours_yandex_bike'] ?? 0,
                        'total_amount' => $row['total_amount'] ?? 0,
                        'record_date' => $row['record_date'] ?? date('Y-m-d'),
                        'period_from' => $row['period_from'] ?? null,
                        'period_to' => $row['period_to'] ?? null,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    log_info("Saving verification data", $verificationData);

                    $result = $this->verificationModel->create($verificationData);

                    if ($result) {
                        log_info("Verification data saved successfully", [
                            'courier_id' => $row['courier_id']
                        ]);
                        $courierIds[] = $row['courier_id'];
                        $records[] = $row;
                        $recordsProcessed++;
                    } else {
                        log_error("Failed to save verification data", ['courier_id' => $row['courier_id']]);
                    }
                } catch (Exception $e) {
                    log_error("Exception saving verification data", [
                        'courier_id' => $row['courier_id'],
                        'message' => $e->getMessage()
                    ]);
                }
            }
        } catch (Exception $e) {
            log_error('Error processing file data', ['message' => $e->getMessage()]);
            throw new Exception('Ошибка обработки данных файла: ' . $e->getMessage());
        }

        log_info("File processing completed", [
            'records_processed' => $recordsProcessed,
            'unique_courier_ids' => count(array_unique($courierIds))
        ]);

        return [
            'records_processed' => $recordsProcessed,
            'courier_ids' => array_unique($courierIds),
            'records' => $records
        ];
    }

    private function parseCsvFile($filePath)
    {
        $data = [];

        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $headers = fgetcsv($handle, 1000, ',');

            // Нормализуем названия заголовков
            $normalizedHeaders = array_map(function ($header) {
                return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', $header)));
            }, $headers);

            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (count($row) !== count($normalizedHeaders)) {
                    continue; // Пропускаем некорректные строки
                }

                $rowData = array_combine($normalizedHeaders, $row);

                // Преобразуем числовые значения
                $data[] = [
                    'courier_id' => trim($rowData['courier_id'] ?? $rowData['id_kuriera'] ?? ''),
                    'worked_hours' => floatval(str_replace(',', '.', $rowData['worked_hours'] ?? $rowData['otrabotano_chasov'] ?? 0)),
                    'orders_count' => intval($rowData['orders_count'] ?? $rowData['kolichestvo_zakazov'] ?? 0),
                    'hours_own_bike' => floatval(str_replace(',', '.', $rowData['hours_own_bike'] ?? $rowData['chasy_sobstvennyj_velo'] ?? 0)),
                    'hours_electric_bike' => floatval(str_replace(',', '.', $rowData['hours_electric_bike'] ?? $rowData['chasy_elektro_velo'] ?? 0)),
                    'hours_yandex_bike' => floatval(str_replace(',', '.', $rowData['hours_yandex_bike'] ?? $rowData['chasy_yandex_velo'] ?? 0)),
                    'total_amount' => floatval(str_replace(',', '.', $rowData['total_amount'] ?? $rowData['itogovaya_summa'] ?? 0)),
                    'period_from' => !empty($rowData['period_from']) ? $this->normalizeDate($rowData['period_from']) : null,
                    'period_to' => !empty($rowData['period_to']) ? $this->normalizeDate($rowData['period_to']) : null,
                    'record_date' => $rowData['record_date'] ?? date('Y-m-d')
                ];
            }
            fclose($handle);
        }

        return $data;
    }

    private function parseExcelFile($filePath)
    {
        $data = [];

        try {
            log_info("Parsing Excel file with full candidate data: {$filePath}");

            // Загружаем файл
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            log_info("Excel file loaded", ['rows' => $highestRow]);

            // ОБНОВЛЕННЫЙ MAPPING КОЛОНОК на основе структуры файла
            $columnMapping = [
                'courier_id' => 'A',      // Колонка A - ID Курьера
                'login' => 'B',           // Колонка B - Логин
                'full_name' => 'C',       // Колонка C - ФИО
                'city' => 'D',            // Колонка D - Город
                'courier_service' => 'E', // Колонка E - Курьерская служба
                'worked_hours' => 'F',    // Колонка F - Отработанные часы
                'orders_count' => 'G',    // Колонка G - Кол-во заказов
                'hours_own_bike' => 'H',  // Колонка H - Часов на своем вело
                'hours_electric_bike' => 'I', // Колонка I - Часов на электро вело
                'hours_yandex_bike' => 'J', // Колонка J - Часов на яндекс вело
                'total_amount' => 'W'     // Колонка W - Итоговая сумма (последняя колонка)
            ];

            log_info("Using updated column mapping", $columnMapping);

            // Обрабатываем строки данных (начиная со строки 2, т.к. строка 1 - заголовки)
            for ($row = 2; $row <= $highestRow; $row++) {

                // Извлекаем основные данные
                $courierId = $this->safeGetStringValue($worksheet, $columnMapping['courier_id'] . $row);

                // Пропускаем пустые строки
                if (empty($courierId)) {
                    continue;
                }

                // Извлекаем данные кандидата
                $fullName = $this->safeGetStringValue($worksheet, $columnMapping['full_name'] . $row);
                $city = $this->safeGetStringValue($worksheet, $columnMapping['city'] . $row);
                $courierService = $this->safeGetStringValue($worksheet, $columnMapping['courier_service'] . $row);

                // Извлекаем рабочие данные
                $workedHours = $this->safeGetNumericValue($worksheet, $columnMapping['worked_hours'] . $row);
                $ordersCount = $this->safeGetNumericValue($worksheet, $columnMapping['orders_count'] . $row, true);
                $hoursOwnBike = $this->safeGetNumericValue($worksheet, $columnMapping['hours_own_bike'] . $row);
                $hoursElectricBike = $this->safeGetNumericValue($worksheet, $columnMapping['hours_electric_bike'] . $row);
                $hoursYandexBike = $this->safeGetNumericValue($worksheet, $columnMapping['hours_yandex_bike'] . $row);
                $totalAmount = $this->safeGetNumericValue($worksheet, $columnMapping['total_amount'] . $row);

                // Определяем период из названия файла или используем текущую дату
                $periodDates = $this->extractPeriodFromFileName($filePath);

                $processedRow = [
                    'courier_id' => $courierId,
                    'full_name' => $fullName,
                    'city' => $city,
                    'courier_service' => $courierService,
                    'worked_hours' => $workedHours,
                    'orders_count' => $ordersCount,
                    'hours_own_bike' => $hoursOwnBike,
                    'hours_electric_bike' => $hoursElectricBike,
                    'hours_yandex_bike' => $hoursYandexBike,
                    'total_amount' => $totalAmount,
                    'period_from' => $periodDates['from'],
                    'period_to' => $periodDates['to'],
                    'record_date' => date('Y-m-d')
                ];

                $data[] = $processedRow;

                // Логируем первую строку для отладки
                if ($row === 2) {
                    log_info("First row with candidate data", $processedRow);
                }
            }

            log_info("Excel file parsed successfully", [
                'rows_processed' => count($data),
                'total_rows_in_file' => $highestRow - 1
            ]);
        } catch (Exception $e) {
            log_error('Error parsing Excel file', ['message' => $e->getMessage()]);
            throw new Exception('Ошибка чтения Excel файла: ' . $e->getMessage());
        }

        return $data;
    }

    private function normalizeDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // Если это уже объект DateTime
        if ($dateString instanceof DateTime) {
            return $dateString->format('Y-m-d');
        }

        // Пробуем различные форматы дат
        $formats = ['Y-m-d', 'd.m.Y', 'd/m/Y', 'm/d/Y', 'Y.m.d'];

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        // Если ничего не подошло, возвращаем как есть (может быть уже в правильном формате)
        return $dateString;
    }

    private function createMissingCandidates($processedData)
    {
        $newCandidatesCount = 0;

        log_info("Starting createMissingCandidates with full data", ['records_count' => count($processedData)]);

        foreach ($processedData as $record) {
            $courierId = $record['courier_id'];

            // Проверяем существование кандидата
            $existingCandidate = $this->candidateModel->findByCourierId($courierId);

            if (!$existingCandidate) {
                log_info("Creating new candidate with full data", ['courier_id' => $courierId]);

                // Создаем нового кандидата ТОЛЬКО с реальными данными из файла
                $candidateData = [
                    'courier_id' => $courierId,
                    'full_name' => $record['full_name'],
                    'city' => $record['city'],
                    'phone_number' => '', // Оставляем пустым - заполним вручную позже
                    'manager_name' => '',//$this->assignManagerBasedOnCity($record['city']),
                    'department' => '',//$this->assignDepartmentBasedOnCity($record['city']),
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                log_info("Candidate data from file", $candidateData);

                try {
                    $result = $this->candidateModel->create($candidateData);

                    if ($result) {
                        $newCandidatesCount++;
                        log_info("New candidate created successfully", [
                            'courier_id' => $courierId,
                            'full_name' => $record['full_name'],
                            'city' => $record['city']
                        ]);
                    } else {
                        log_error("Failed to create candidate", ['courier_id' => $courierId]);
                    }
                } catch (Exception $e) {
                    log_error("Exception creating candidate", [
                        'courier_id' => $courierId,
                        'message' => $e->getMessage()
                    ]);
                }
            } else {
                log_info("Candidate already exists", [
                    'courier_id' => $courierId,
                    'existing_name' => $existingCandidate['full_name']
                ]);
            }
        }

        log_info("createMissingCandidates completed", [
            'total_processed' => count($processedData),
            'new_candidates_created' => $newCandidatesCount
        ]);

        return $newCandidatesCount;
    }

    private function countRecordsInFile($filePath, $extension)
    {
        if ($extension === 'csv') {
            if (($handle = fopen($filePath, 'r')) !== FALSE) {
                $count = 0;
                while (fgetcsv($handle, 1000, ',')) {
                    $count++;
                }
                fclose($handle);
                return max(0, $count - 1); // Минус заголовок
            }
        } else {
            // Для Excel файлов считаем реальное количество строк с данными
            try {
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                return max(0, $highestRow - 1); // Минус заголовок
            } catch (Exception $e) {
                log_error('Error counting Excel rows', ['message' => $e->getMessage()]);
                return 0;
            }
        }

        return 0;
    }

    /**
     * Извлечение периода из названия файла
     */
    private function extractPeriodFromFileName($filePath)
    {
        $fileName = basename($filePath);

        // Пытаемся извлечь даты из названия файла
        // Формат: АЛЬЯНС_ГРУПП_ООО_КС_29_09_2025_05_10_2025_Лавка.xlsx
        if (preg_match('/(\d{2})_(\d{2})_(\d{4})_(\d{2})_(\d{2})_(\d{4})/', $fileName, $matches)) {
            $fromDate = "{$matches[3]}-{$matches[2]}-{$matches[1]}"; // YYYY-MM-DD
            $toDate = "{$matches[6]}-{$matches[5]}-{$matches[4]}"; // YYYY-MM-DD
            log_info($fromDate .  "  TO " . $toDate);
            return [
                'from' => $fromDate,
                'to' => $toDate
            ];
        }

        log_info("Не удалось извлечь данные");
        
        // Если не удалось извлечь из названия, используем текущую неделю
        return [
            'from' => date('Y-m-d', strtotime('monday this week')),
            'to' => date('Y-m-d', strtotime('sunday this week'))
        ];
    }

    /**
     * Назначение менеджера на основе города и службы
     */
    private function assignManager($city, $courierService)
    {
        $managersByCity = [
            'Москва' => ['Петрова Мария Ивановна', 'Сидоров Иван Петрович'],
            'Санкт-Петербург' => ['Козлова Анна Дмитриевна', 'Федоров Максим Сергеевич'],
            'Казань' => ['Никитина Елена Александровна'],
        ];

        $defaultManagers = ['Петрова Мария Ивановна', 'Сидоров Иван Петрович'];

        if (isset($managersByCity[$city])) {
            $managers = $managersByCity[$city];
            return $managers[array_rand($managers)];
        }

        return $defaultManagers[array_rand($defaultManagers)];
    }

    /**
     * Назначение отдела на основе города
     */
    private function assignDepartment($city, $courierService)
    {
        $departmentsByCity = [
            'Москва' => '1 отдел',
            'Санкт-Петербург' => '2 отдел',
            'Казань' => '3 отдел',
        ];

        return $departmentsByCity[$city] ?? '1 отдел';
    }

    /**
     * Безопасное получение числового значения
     */
    private function safeGetNumericValue($worksheet, $cellAddress, $isInteger = false)
    {
        try {
            $value = $worksheet->getCell($cellAddress)->getCalculatedValue();

            if (empty($value) || $value === '') {
                return 0;
            }

            // Если это дата Excel, конвертируем
            if (Date::isDateTime($worksheet->getCell($cellAddress))) {
                $value = Date::excelToDateTimeObject($value)->format('Y-m-d');
                return 0; // Дата не является числовым значением
            }

            // Заменяем запятые на точки для дробных чисел
            $normalized = str_replace(',', '.', strval($value));

            if (is_numeric($normalized)) {
                return $isInteger ? intval($normalized) : floatval($normalized);
            }

            return 0;
        } catch (Exception $e) {
            log_warning("Error getting numeric value from cell", [
                'cell' => $cellAddress,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Безопасное получение строкового значения
     */
    private function safeGetStringValue($worksheet, $cellAddress)
    {
        try {
            $value = $worksheet->getCell($cellAddress)->getCalculatedValue();
            return trim($value);
        } catch (Exception $e) {
            log_warning("Error getting string value from cell", [
                'cell' => $cellAddress,
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }
}
