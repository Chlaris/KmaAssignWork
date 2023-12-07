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

class KmaConnectionController extends Controller {
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
     * @param integer $connection_id
     * @param string $task_id
     * @param integer $file_id
     */
    public function createKmaConnection($connection_id, $task_id, $file_id) {
        $query = $this->db->getQueryBuilder();
        $query->insert('kma_connection')
                ->values([
                    'connection_id' => $query->createNamedParameter($connection_id),
                    'task_id' => $query->createNamedParameter($task_id),
                    'file_id' => $query->createNamedParameter($file_id),
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
    public function getAllKmaConnections() {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_connection');

        $result = $query->execute();
        $connections = $result->fetchAll();
        return ['connections' => $connections];
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param integer $connection_id
     */
    public function getKmaConnection($connection_id) {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_connection')
            ->where($query->expr()->eq('connection_id', $query->createNamedParameter($connection_id)));

        $result = $query->execute();
        $data = $result->fetchAll();
        if ($data === false) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        return new DataResponse([
            'Ma lien ket' => $data['connection_id'],
            'Ma tac vu' => $data['task_id'],
            'Ma file' => $data['file_id'],
        ]);
    }
    
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param integer $connection_id
     * @param string $task_id
     * @param integer $file_id
     * @return JSONResponse
     */
    public function updateConnection($connection_id, $task_id = null, $file_id = null) {
        $query = $this->db->prepare('UPDATE `oc_kma_connection` SET `task_id` = COALESCE(?, `task_id`), 
                                                            `file_id` = COALESCE(?, `file_id`), 
                                                                WHERE `connection_id` = ?');
        $query->execute(array($task_id, $file_id, $connection_id));
        return new JSONResponse(array('status' => 'success'));
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param integer $connection_id
     */
    public function deleteConnection($connection_id) {
        $query = $this->db->getQueryBuilder();
        $query->delete('kma_connection')
            ->where($query->expr()->eq('connection_id', $query->createNamedParameter($connection_id)))
            ->execute();
        return new DataResponse(['status' => 'success']);
    }

    

}