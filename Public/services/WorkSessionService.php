<?php

require_once __DIR__ . '/../models/WorkSessionRepository.php';

class WorkSessionService
{
    private $workSessionRepo;

    public function __construct(WorkSessionRepository $workSessionRepo)
    {
        $this->workSessionRepo = $workSessionRepo;
    }

    public function startWork($id_user)
    {
        $activeSession = $this->workSessionRepo->findActiveSessionByUser($id_user);
        if ($activeSession) {
            return;
        }

        $this->workSessionRepo->createSession($id_user);
    }

    public function stopWork($id_user)
    {
        $this->workSessionRepo->stopActiveSession($id_user);
    }
}
