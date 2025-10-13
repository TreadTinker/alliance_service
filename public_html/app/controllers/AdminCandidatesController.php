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

        // Устанавливаем переменные для layout
        $pageTitle = "Таблица кандидатов";
        $currentSection = "candidates";

        // Начинаем буферизацию вывода
        ob_start();
        include __DIR__ . '/../views/admin/pages/candidates/index.php';
        $content = ob_get_clean();

        // Подключаем основной layout
        include __DIR__ . '/../views/admin/layout/admin.php';
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
        $verifications = $this->verificationModel->getByCourier($courierId, 0);

        // Статистика за ВСЕ время
        $stats = $this->getCandidateTotalStats($verifications);

        // Устанавливаем переменные для layout
        $pageTitle = "Кандидат: " . $candidate['full_name'];
        $currentSection = "candidates";

        ob_start();
        include __DIR__ . '/../../views/admin/pages/candidates/show.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/admin/layout/admin.php';
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
            $verifications = $this->verificationModel->getByCourier($courierId);

            if (empty($verifications)) {
                return $this->getEmptyStats();
            }

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
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="candidates_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
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
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        $errors = [];

        if ($_POST) {
            try {
                $this->candidateModel->createCandidate($_POST);
                $this->redirect('/admin/candidates?success=candidate_added');
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        $departments = $this->candidateModel->getUniqueDepartments();
        $cities = $this->candidateModel->getUniqueCities();

        $pageTitle = "Добавление кандидата";
        $currentSection = "candidates";

        ob_start();
        include __DIR__ . '/../../views/admin/pages/candidates/add.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/admin/layout/admin.php';
    }

    /**
     * Редактирование курьера
     */
    public function edit($courierId)
    {
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

        $departments = $this->candidateModel->getUniqueDepartments();
        $cities = $this->candidateModel->getUniqueCities();

        $pageTitle = "Редактирование кандидата";
        $currentSection = "candidates";

        ob_start();
        include __DIR__ . '/../../views/admin/pages/candidates/edit.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/admin/layout/admin.php';
    }

    /**
     * Данные сверок кандидата
     */
    public function verifications($courierId)
    {
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            $this->redirect('/?error=access_denied');
        }

        $candidate = $this->candidateModel->findByCourierId($courierId);

        if (!$candidate) {
            $this->redirect('/admin/candidates?error=candidate_not_found');
        }

        $verifications = $this->verificationModel->getByCourier($courierId);

        $pageTitle = "Сверки кандидата: " . $candidate['full_name'];
        $currentSection = "candidates";

        ob_start();
        include __DIR__ . '/../../views/admin/candidates/verifications.php';
        $content = ob_get_clean();

        include __DIR__ . '/../views/admin/layout/admin.php';
    }
}