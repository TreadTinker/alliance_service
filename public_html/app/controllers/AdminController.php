<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Подключаем логгер
require_once __DIR__ . '/../core/Logger.php';

class AdminController
{
    private $auth;
    private $candidateModel;
    private $verificationModel;
    private $logger;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->candidateModel = new Candidate();
        $this->verificationModel = new VerificationData();
        $this->logger = Logger::getInstance();

        log_info('AdminController initialized');
    }

    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard()
    {
        log_info('Admin dashboard accessed');

        // Проверяем авторизацию и роль
        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            log_warning('Unauthorized access attempt to admin dashboard');
            header('Location: /login?redirect=admin');
            exit;
        }

        $user = $this->auth->getUser();
        $stats = $this->getStats();

        // Передаем данные в view
        require_once __DIR__ . '/../views/admin/pages/dashboard/index.php';
    }


    public function tables() 
    {
        log_info("Admin pages Tables");

        if (!$this->auth->isLoggedIn() || (!$this->auth->isAdmin() && !$this->auth->isModerator())) {
            log_warning('Unauthorized access attempt to admin dashboard');
            header('Location: /login?redirect=admin');
            exit;
        }

        require_once __DIR__ . '/../views/admin/pages/tables/index.php';
    }
    /**
     * Получить реальную статистику через модели
     */
    private function getStats()
    {
        try {
            log_info('Getting admin statistics');

            // Данные из Candidate модели
            log_debug('Fetching candidate statistics');
            $totalCandidates = $this->candidateModel->getTotalCount();
            $activeCandidates = $this->candidateModel->getTotalCount(['status' => 'active']);

            log_debug('Candidate stats', [
                'total' => $totalCandidates,
                'active' => $activeCandidates
            ]);

            // Данные из VerificationData модели
            log_debug('Fetching verification statistics');
            $totalVerifications = $this->verificationModel->getTotalCount();
            $totalEarnings = $this->verificationModel->getTotalEarnings();

            log_debug('Fetching recent verifications with names');
            $recentVerifications = $this->verificationModel->getRecentVerificationsWithNames(5);
            log_debug('Recent verifications result', [
                'count' => count($recentVerifications),
                'data' => $recentVerifications
            ]);

            $overallStats = $this->verificationModel->getOverallStats();
            $recentCount = $this->verificationModel->getRecentCount(7);

            $stats = [
                'total_candidates' => $totalCandidates,
                'active_candidates' => $activeCandidates,
                'total_verifications' => $totalVerifications,
                'total_earnings' => $totalEarnings,
                'recent_verifications' => $recentVerifications,
                'total_hours' => $overallStats['total_hours'] ?? 0,
                'total_orders' => $overallStats['total_orders'] ?? 0,
                'unique_couriers' => $overallStats['unique_couriers'] ?? 0,
                'recent_verifications_count' => $recentCount
            ];

            log_info('Admin statistics retrieved successfully', ['stats_summary' => [
                'candidates' => $stats['total_candidates'],
                'verifications' => $stats['total_verifications'],
                'earnings' => $stats['total_earnings']
            ]]);

            return $stats;
        } catch (Exception $e) {
            log_error('Error getting admin stats', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->getFallbackStats();
        }
    }

    /**
     * Запасная статистика (если БД недоступна)
     */
    private function getFallbackStats()
    {
        log_warning('Using fallback stats - database might be unavailable');
        return [
            'total_candidates' => 0,
            'active_candidates' => 0,
            'total_verifications' => 0,
            'total_earnings' => 0,
            'recent_verifications' => [],
            'total_hours' => 0,
            'total_orders' => 0,
            'unique_couriers' => 0,
            'recent_verifications_count' => 0
        ];
    }
}
