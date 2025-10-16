<?php

class AdminCandidatesController extends Controller
{
    private $candidateModel;
    private $verificationModel;
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->candidateModel = new Candidate();
        $this->verificationModel = new VerificationData();

        // Проверка прав доступа
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }
    }

    /**
     * Главная страница управления кандидатами
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $department = $_GET['department'] ?? '';
        $city = $_GET['city'] ?? '';
        $status = $_GET['status'] ?? 'active';

        // Получаем кандидатов с фильтрами
        $candidates = $this->getCandidatesWithStats($search, $department, $city, $status);

        // Получаем уникальные значения для фильтров
        $departments = $this->candidateModel->getUniqueDepartments();
        $cities = $this->candidateModel->getUniqueCities();

        $this->render('admin/candidates/index', [
            'candidates' => $candidates,
            'search' => $search,
            'department' => $department,
            'city' => $city,
            'status' => $status,
            'departments' => $departments,
            'cities' => $cities
        ]);
    }

    /**
     * Детальная страница кандидата
     */
    public function show($courierId)
    {
        $candidate = $this->candidateModel->findByCourierId($courierId);

        if (!$candidate) {
            $this->redirect('/admin/candidates?error=candidate_not_found');
        }

        // Получаем ВСЕ записи сверок для хронологии через модель
        $verifications = $this->verificationModel->getByCourier($courierId, 0); // 0 = все записи

        // Статистика за ВСЕ время (агрегируем из всех записей)
        $stats = $this->getCandidateTotalStats($verifications);

        $this->render('admin/candidates/show', [
            'candidate' => $candidate,
            'verifications' => $verifications,
            'stats' => $stats
        ]);
    }

    /**
     * Получить общую статистику за все время из всех записей
     */
    private function getCandidateTotalStats($verifications)
    {
        if (empty($verifications)) {
            return [
                'total_verifications' => 0,
                'total_hours' => 0,
                'total_orders' => 0,
                'total_earnings' => 0
            ];
        }

        $stats = [
            'total_verifications' => count($verifications),
            'total_hours' => 0,
            'total_orders' => 0,
            'total_earnings' => 0
        ];

        foreach ($verifications as $verification) {
            $stats['total_hours'] += $verification['worked_hours'];
            $stats['total_orders'] += $verification['orders_count'];
            $stats['total_earnings'] += $verification['total_amount'];
        }

        return $stats;
    }

    /**
     * Получить кандидатов со статистикой
     */
    private function getCandidatesWithStats($search = '', $department = '', $city = '', $status = 'active')
    {
        // Используем метод where из модели Candidate
        $where = ['status' => $status];

        if (!empty($department)) {
            $where['department'] = $department;
        }

        if (!empty($city)) {
            $where['city'] = $city;
        }

        $candidates = $this->candidateModel->where($where);

        // Если есть поиск - фильтруем по ФИО
        if (!empty($search)) {
            $candidates = array_filter($candidates, function ($candidate) use ($search) {
                return stripos($candidate['full_name'], $search) !== false ||
                    stripos($candidate['courier_id'], $search) !== false;
            });
        }

        // Добавляем статистику для каждого кандидата
        foreach ($candidates as &$candidate) {
            $candidate['stats'] = $this->getCandidateStats($candidate['courier_id']);
        }

        return $candidates;
    }

    /**
     * Получить статистику по кандидату
     */
    private function getCandidateStats($courierId)
    {
        try {
            // Получаем все записи сверок для курьера через модель
            $verifications = $this->verificationModel->getByCourier($courierId);

            if (empty($verifications)) {
                return $this->getEmptyStats();
            }

            // Считаем статистику вручную из всех записей
            $stats = [
                'total_verifications' => count($verifications),
                'total_hours' => 0,
                'total_orders' => 0,
                'total_earnings' => 0,
                'last_verification' => ''
            ];

            foreach ($verifications as $verification) {
                $stats['total_hours'] += $verification['worked_hours'];
                $stats['total_orders'] += $verification['orders_count'];
                $stats['total_earnings'] += $verification['total_amount'];

                // Находим самую свежую дату
                $currentDate = $verification['record_date'];
                if (empty($stats['last_verification']) || $currentDate > $stats['last_verification']) {
                    $stats['last_verification'] = $currentDate;
                }
            }

            return $stats;
        } catch (Exception $e) {
            error_log("Error getting candidate stats: " . $e->getMessage());
            return $this->getEmptyStats();
        }
    }

    /**
     * Пустая статистика
     */
    private function getEmptyStats()
    {
        return [
            'total_verifications' => 0,
            'total_hours' => 0,
            'total_orders' => 0,
            'total_earnings' => 0,
            'last_verification' => 'Нет данных'
        ];
    }

    /**
     * Экспорт данных кандидатов
     */
    public function export()
    {
        // Проверка прав доступа
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        // Логика экспорта
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="candidates_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Добавляем BOM для корректного отображения кириллицы в Excel
        fwrite($output, "\xEF\xBB\xBF");

        fputcsv($output, ['ID Курьера', 'ФИО', 'Город', 'Телефон', 'Менеджер', 'Отдел', 'Статус'], ';');

        $candidates = $this->candidateModel->where([]);
        foreach ($candidates as $candidate) {
            fputcsv($output, [
                $candidate['courier_id'],
                $candidate['full_name'],
                $candidate['city'],
                $candidate['phone_number'],
                $candidate['manager_name'],
                $candidate['department'],
                $candidate['status']
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * Добавление нового курьера
     */
    public function add()
    {
        // Проверка прав доступа
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        $errors = [];
        $success = '';

        if ($_POST) {
            try {
                $this->candidateModel->createCandidate($_POST);
                $this->redirect('/admin/candidates?success=candidate_added');
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Получаем уникальные значения для формы
        $departments = $this->candidateModel->getUniqueDepartments();
        $cities = $this->candidateModel->getUniqueCities();

        $this->render('admin/candidates/add', [
            'departments' => $departments,
            'cities' => $cities,
            'errors' => $errors,
            'success' => $success
        ]);
    }

    /**
     * Редактирование курьера
     */
    public function edit($courierId)
    {
        // Проверка прав доступа
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        $candidate = $this->candidateModel->findByCourierId($courierId);

        if (!$candidate) {
            $this->redirect('/admin/candidates?error=candidate_not_found');
        }

        $errors = [];

        if ($_POST) {
            try {
                $this->candidateModel->updateCandidate($courierId, $_POST);
                $this->redirect('/admin/candidates?success=candidate_updated');
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Получаем уникальные значения для формы
        $departments = $this->candidateModel->getUniqueDepartments();
        $cities = $this->candidateModel->getUniqueCities();

        $this->render('admin/candidates/edit', [
            'candidate' => $candidate,
            'departments' => $departments,
            'cities' => $cities,
            'errors' => $errors
        ]);
    }

    /**
     * Данные сверок кандидата
     */
    public function verifications($courierId)
    {
        // Проверка прав доступа
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        $candidate = $this->candidateModel->findByCourierId($courierId);

        if (!$candidate) {
            $this->redirect('/admin/candidates?error=candidate_not_found');
        }

        $verifications = $this->verificationModel->getByCourier($courierId);

        $this->render('admin/candidates/verifications', [
            'candidate' => $candidate,
            'verifications' => $verifications
        ]);
    }
}
