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

class KmaTaskController extends Controller {
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

     /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $task_id
     * @param string $task_name
     * @param string $task_description
     * @param string $status_id
     * @param string $work_id
     * @param string $level_id
     * @param date $work_start
	 * @param date $work_end
     * @param string $user_respond
     * @param string $user_support
     */
    public function createKmaTask($task_id, $task_name, $task_description, $status_id, $work_id, $level_id, $work_start, 
    $work_end, $user_respond, $user_support) {
        $query = $this->db->getQueryBuilder();
        $query->insert('kma_task_item')
                ->values([
                    'task_id' => $query->createNamedParameter($task_id),
                    'task_name' => $query->createNamedParameter($task_name),
                    'task_description' => $query->createNamedParameter($task_description),
                    'status_id' => $query->createNamedParameter($status_id),
                    'work_id' => $query->createNamedParameter($work_id),
                    'level_id' => $query->createNamedParameter($level_id),
                    'work_start' => $query->createNamedParameter($work_start),
                    'work_end' => $query->createNamedParameter($work_end),
                    'user_respond' => $query->createNamedParameter($user_respond),
                    'user_support' => $query->createNamedParameter($user_support),
                ])
                ->execute();
            return new DataResponse(['status' => 'success']);
            
        // $currentUser = $this->userSession->getUser();
        // $uid = $currentUser->getUID();

		// if ($this->groupManager->isAdmin($uid)) {
        //     $work = $this->db->getQueryBuilder();
        //     $work->select('*')
        //         ->from('oc_kma_work')
        //         ->where($work->expr()->eq('kma_work_id', $user->createNamedParameter($kma_work_id)));
        //     $result = $work->execute();
        //     $data = $result->fetch();
        //     if ($data === false) {
        //         return new DataResponse(["Don't have this work"], Http::STATUS_NOT_FOUND);
        //     }

        //     $query = $this->db->getQueryBuilder();
        //     $query->insert('kma_task_in_work')
        //         ->values([
        //             'kma_task_id' => $query->createNamedParameter($kma_task_id),
        //             'kma_work_id' => $query->createNamedParameter($kma_work_id),
        //             'task_name' => $query->createNamedParameter($task_name),
        //             'content' => $query->createNamedParameter($content),
        //             'status' => $query->createNamedParameter($status),
        //         ])
        //         ->execute();
        //     return new DataResponse(['status' => 'success']);
        // }
        // else {
        //     return new DataResponse(['No admin']);
        // }
        
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getAllKmaTask() {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_task_item');

        $result = $query->execute();
        $tasks = $result->fetchAll();
        return ['tasks' => $tasks];
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_task_id
     */
    public function getKmaTaskInWork($kma_task_id) {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_task_in_work')
            ->where($query->expr()->eq('kma_task_id', $query->createNamedParameter($kma_task_id)));

        $result = $query->execute();
        $data = $result->fetchAll();
        if ($data === false) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        return new DataResponse([
            'Ma tac vu' => $data['kma_task_id'],
            'Ma cong viec' => $data['kma_work_id'],
            'Ten tac vu' => $data['task_name'],
            'Noi dung' => $data['content'],
            'Trang thai' => $data['status'],
        ]);
    }
    
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_task_id
     * @param string $kma_work_id
     * @param string $task_name
     * @param string $content
	 * @param string $status
     * @return JSONResponse
     */
    public function updateInfoTask($kma_task_id, $kma_work_id, $task_name = null, $content = null, $status = null) {
        $query = $this->db->prepare('UPDATE `oc_kma_task_in_work` SET `task_name` = COALESCE(?, `task_name`), 
                                                            `content` = COALESCE(?, `content`), 
                                                            `status` = COALESCE(?, `status`), 
                                                                WHERE `kma_task_id` = ?');
        $query->execute(array($task_name, $content, $status, $kma_work_id, $kma_task_id));
        return new JSONResponse(array('status' => 'success'));
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_task_id
     */
    public function deleteKmaTask($kma_task_id) {
        $query = $this->db->getQueryBuilder();
        $query->delete('kma_task_in_work')
            ->where($query->expr()->eq('kma_task_id', $query->createNamedParameter($kma_task_id)))
            ->execute();
        return new DataResponse(['status' => 'success']);
    }

    

}