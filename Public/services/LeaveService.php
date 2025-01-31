<?php

require_once __DIR__ . '/../repository/LeaveRepository.php';
require_once __DIR__ . '/../repository/LeaveTypeRepository.php';


class LeaveService
{
    private $leaveRepo;
    private $leaveTypeRepo;

    public function __construct(LeaveRepository $leaveRepo, LeaveTypeRepository $leaveTypeRepo)
    {
        $this->leaveRepo = $leaveRepo;
        $this->leaveTypeRepo = $leaveTypeRepo;
    }

    public function getLeaves($userId)
    {
        return $this->leaveRepo->findAllByUser($userId);
    }

    public function createLeave($userId, $leaveType, $dateStart, $dateEnd, $reason, $additionalNotes)
    {
        return $this->leaveRepo->createLeave($userId, $leaveType, $dateStart, $dateEnd, $reason, $additionalNotes);
    }

    public function getAllLeaveTypes()
    {
        return $this->leaveTypeRepo->findAllTypes();
    }


    public function leaveTypeToString($type)
    {

        switch ($type) {
            case 1: return 'VACATION LEAVE';
            case 2: return 'LEAVE ON DEMAND';
            case 3: return 'UNPAID LEAVE';
            case 4: return 'SPECIAL LEAVE';
            case 5: return 'PARENTAL LEAVE';
            case 6: return 'SICK LEAVE';
            case 7: return 'CHILD CARE LEAVE';
            default: return 'OTHER LEAVE';
        }
    }

    public function statusToString($status)
    {
        switch ($status) {
            case 1: return 'pending';
            case 2: return 'approved';
            case 3: return 'rejected';
            default: return 'unknown';
        }
    }
}
