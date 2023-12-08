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

class KmaCommentController extends Controller {
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
     * @param integer $comment_id
     * @param string $user_create
     * @param longtext $message
     */
    public function createKmaComment($comment_id, $user_create, $message) {
        $query = $this->db->getQueryBuilder();
        $query->insert('kma_comments')
                ->values([
                    'comment_id' => $query->createNamedParameter($comment_id),
                    'user_create' => $query->createNamedParameter($user_create),
                    'message' => $query->createNamedParameter($message),
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
    public function getAllTaskComments() {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_comments');

        $result = $query->execute();
        $comments = $result->fetchAll();
        return ['comments' => $comments];
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $comment_id
     */
    public function getKmaComment($comment_id) {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_comments')
            ->where($query->expr()->eq('comment_id', $query->createNamedParameter($comment_id)));

        $result = $query->execute();
        $data = $result->fetchAll();
        if ($data === false) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        return new DataResponse([
            'Ma binh luan' => $data['comment_id'],
            'Nguoi viet' => $data['user_create'],
            'Noi dung' => $data['message'],
        ]);
    }
    
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param integer $comment_id
     * @param string $user_create
     * @param longtext $message
     * @return JSONResponse
     */
    public function updateComment($comment_id, $user_create = null, $message = null) {
        $query = $this->db->prepare('UPDATE `oc_kma_comments` SET `user_create` = COALESCE(?, `user_create`), 
                                                            `message` = COALESCE(?, `message`),  
                                                                WHERE `comment_id` = ?');
        $query->execute(array($user_create, $message, $comment_id));
        return new JSONResponse(array('status' => 'success'));
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param integer $comment_id
     */
    public function deleteComment($comment_id) {
        $query = $this->db->getQueryBuilder();
        $query->delete('kma_comments')
            ->where($query->expr()->eq('comment_id', $query->createNamedParameter($comment_id)))
            ->execute();
        return new DataResponse(['status' => 'success']);
    }

    

}