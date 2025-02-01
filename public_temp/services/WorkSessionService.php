<?php

require_once __DIR__ . '/../repository/WorkSessionRepository.php';

class WorkSessionService
{
    private $workSessionRepo;

    public function __construct(WorkSessionRepository $workSessionRepo)
    {
        $this->workSessionRepo = $workSessionRepo;
    }

    public function startWork($userId)
    {
        $activeSession = $this->workSessionRepo->findActiveSessionByUser($userId);
        if ($activeSession) {
            return;
        }

        $this->workSessionRepo->createSession($userId);
    }

    public function stopWork($userId)
    {
        $this->workSessionRepo->stopActiveSession($userId);
    }

    public function hasActiveSession($userId): bool
    {
        return (bool)$this->workSessionRepo->findActiveSessionByUser($userId);
    }

    public function getCurrentSessionWorkTime($userId): int
    {
        return $this->workSessionRepo->getCurrentSessionWorkTime($userId);
    }

    public function getDailyWorkTime($userId): int
    {
        return $this->workSessionRepo->getDailyWorkTime($userId);
    }

    public function getWeeklyWorkTime($userId): int
    {
        return $this->workSessionRepo->getWeeklyWorkTime($userId);
    }

    public function getMonthlyWorkTime($userId): int
    {
        return $this->workSessionRepo->getMonthlyWorkTime($userId);
    }

    public function getYearlyWorkTime($userId): int
    {
        return $this->workSessionRepo->getYearlyWorkTime($userId);
    }
}
