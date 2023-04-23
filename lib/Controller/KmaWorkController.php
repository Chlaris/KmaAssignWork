<?php
namespace OCA\KmaAssignWork\Controller;

use OCP\IRequest;
use OCP\IDBConnection;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\OCS\OCSNotFoundException;

use OCP\IUserSession;
use OCP\IGroupManager;

class KMAWorkController extends Controller {
    private $db;

    /** @var IUserSession */
	protected $userSession;
    /** @var IGroupManager|Manager */ // FIXME Requires a method that is not on the interface
	protected $groupManager;

    public function __construct($AppName, IRequest $request, IDBConnection $db, IUserSession $userSession, IGroupManager $groupManager) {
        parent::__construct($AppName, $request, $userSession, $groupManager);
        $this->db = $db;
        $this->userSession = $userSession;
        $this->groupManager = $groupManager;
    }

    // public function __construct($AppName, IRequest $request, IDBConnection $db) {
    //     parent::__construct($AppName, $request);
    //     $this->db = $db;
    // }

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 * 
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */

     /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_work_id
     * @param string $work_name
     * @param string $level
	 * @param string $status
     * @param string $progress
     * @param string $assignment_time
     * @param string $end_time
     * @param string $assigned_person_id
     * @param string $supporter_id
     * @param string $attached_files
     */
    public function createKmaWork($kma_work_id, $work_name, $level, $status, $progress, $assignment_time, $end_time, $assigned_person_id, $supporter_id, $attached_files) {
        $currentUser = $this->userSession->getUser();
        $uid = $currentUser->getUID();

		if ($this->groupManager->isAdmin($uid)) {
            $user1 = $this->db->getQueryBuilder();
            $user1->select('*')
                ->from('accounts')
                ->where($user->expr()->eq('uid', $user->createNamedParameter($assigned_person_id)));
            $result1 = $user1->execute();
            $data1 = $result1->fetch();
            if ($data1 === false) {
                return new DataResponse(["Don't have assigned person's account"], Http::STATUS_NOT_FOUND);
            }

            $user2 = $this->db->getQueryBuilder();
            $user2->select('*')
                ->from('accounts')
                ->where($user->expr()->eq('uid', $user->createNamedParameter($supporter_id)));
            $result2 = $user2->execute();
            $data2 = $result2->fetch();
            if ($data2 === false) {
                return new DataResponse(["Don't have supporter's account"], Http::STATUS_NOT_FOUND);
            }

            $query = $this->db->getQueryBuilder();
            $query->insert('kma_work')
                ->values([
                    'kma_work_id' => $query->createNamedParameter($kma_work_id),
                    'work_name' => $query->createNamedParameter($work_name),
                    'level' => $query->createNamedParameter($level),
                    'status' => $query->createNamedParameter($status),
                    'progress' => $query->createNamedParameter($progress),
                    'assignment_time' => $query->createNamedParameter($assignment_time),
                    'end_time' => $query->createNamedParameter($end_time),
                    'assigned_person_id' => $query->createNamedParameter($assigned_person_id),
                    'supporter_id' => $query->createNamedParameter($supporter_id),
                    'attached_files' => $query->createNamedParameter($attached_files),
                    // Add other desired columns here
                ])
                ->execute();
            return new DataResponse(['status' => 'success']);
        }
        else {
            return new DataResponse(['No admin']);
        }
        
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getAllKmaWork() {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_work');

        $result = $query->execute();
        $work = $result->fetchAll();
        return ['works' => $work];
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_work_id
     */
    public function getKmaWork($kma_work_id) {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_work')
            ->where($query->expr()->eq('kma_work_id', $query->createNamedParameter($kma_work_id)));

        $result = $query->execute();
        $data = $result->fetchAll();
        if ($data === false) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        return new DataResponse([
            'Ma cong viec' => $data['kma_work_id'],
            'Ten cong viec' => $data['work_name'],
            'Muc do uu tien' => $data['level'],
            'Trang thai' => $data['status'],
            'Tien do' => $data['progress'],
            'Thoi gian giao viec' => $data['assignment_time'],
            'Thoi gian ket thuc' => $data['end_time'],
            'Nguoi phu trach' => $data['assigned_person_id'],
            'Nguoi ho tro' => $data['supporter_id'],
            'Tep dinh kem' => $data['attached_files'],
        ]);
    }
    
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_work_id
     * @param string $work_name
     * @param string $level
	 * @param string $status
     * @param string $progress
     * @param string $assignment_time
     * @param string $end_time
     * @param string $assigned_person_id
     * @param string $supporter_id
     * @param string $attached_files
     * @return JSONResponse
     */
    public function updateInfoKMAUser($kma_work_id, $work_name = null, $level = null, $status = null, $progress = null, $assignment_time = null, $end_time = null, $assigned_person_id = null, $supporter_id = null, $attached_files = null) {
        $query = $this->db->prepare('UPDATE `oc_kma_work` SET `work_name` = COALESCE(?, `work_name`), 
                                                            `level` = COALESCE(?, `level`), 
                                                            `status` = COALESCE(?, `status`), 
                                                            `progress` = COALESCE(?, `progress`),
                                                            `assignment_time` = COALESCE(?, `assignment_time`),
                                                            `end_time` = COALESCE(?, `end_time`),
                                                            `assigned_person_id` = COALESCE(?, `assigned_person_id`),
                                                            `supporter_id` = COALESCE(?, `supporter_id`),
                                                            `attached_files` = COALESCE(?, `attached_files`)
                                                                WHERE `kma_work_id` = ?');
        $query->execute(array($work_name, $level, $status, $progress, $assignment_time, $end_time, $assigned_person_id, $supporter_id, $attached_files, $kma_work_id));
        return new JSONResponse(array('status' => 'success'));
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_work_id
     */
    public function deleteKmaWork($kma_work_id) {
        $query = $this->db->getQueryBuilder();
        $query->delete('kma_work')
            ->where($query->expr()->eq('kma_work_id', $query->createNamedParameter($kma_work_id)))
            ->execute();
        return new DataResponse(['status' => 'success']);
    }

    

}