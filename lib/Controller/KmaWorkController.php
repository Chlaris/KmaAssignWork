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

class KmaWorkController extends Controller {
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
     * @param string $kma_noti_id
     * @param string $content
     * @param string $sender_id
	 * @param string $receiver_id
     * @param string $assignment_time
     * @param string $isNew
     */
    public function createKmaNoti($kma_noti_id, $content, $sender_id, $receiver_id, $assignment_time, $isNew) {
        $currentUser = $this->userSession->getUser();
        $uid = $currentUser->getUID();

		if ($this->groupManager->isAdmin($uid)) {
            $user1 = $this->db->getQueryBuilder();
            $user1->select('*')
                ->from('accounts')
                ->where($user->expr()->eq('uid', $user->createNamedParameter($receiver_id)));
            $result1 = $user1->execute();
            $data1 = $result1->fetch();
            if ($data1 === false) {
                return new DataResponse(["Don't have assigned person's account"], Http::STATUS_NOT_FOUND);
            }

            $user2 = $this->db->getQueryBuilder();
            $user2->select('*')
                ->from('accounts')
                ->where($user->expr()->eq('uid', $user->createNamedParameter($sender_id)));
            $result2 = $user2->execute();
            $data2 = $result2->fetch();
            if ($data2 === false) {
                return new DataResponse(["Don't have supporter's account"], Http::STATUS_NOT_FOUND);
            }

            $query = $this->db->getQueryBuilder();
            $query->insert('kma_work_noti')
                ->values([
                    'kma_noti_id' => $query->createNamedParameter($kma_noti_id),
                    'content' => $query->createNamedParameter($content),
                    'sender_id' => $query->createNamedParameter($sender_id),
                    'receiver_id' => $query->createNamedParameter($receiver_id),
                    'assignment_time' => $query->createNamedParameter($assignment_time),
                    'isNew' => $query->createNamedParameter($isNew),
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
    public function getAllKmaNoti() {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_work_noti');

        $result = $query->execute();
        $noti = $result->fetchAll();
        return ['notifs' => $noti];
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_noti_id
     */
    public function getKmaWork($kma_work_id) {
        $query = $this->db->getQueryBuilder();
        $query->select('*')
            ->from('kma_work_noti')
            ->where($query->expr()->eq('kma_noti_id', $query->createNamedParameter($kma_noti_id)));

        $result = $query->execute();
        $data = $result->fetchAll();
        if ($data === false) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        return new DataResponse([
            'Ma thong bao' => $data['kma_noti_id'],
            'Noi dung' => $data['content'],
            'Ma nguoi gui' => $data['sender_id'],
            'Ma nguoi nhan' => $data['receiver_id'],
            'Thoi gian giao viec' => $data['assignment_time'],
            'isNew' => $data['isNew'],
        ]);
    }
    
    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_noti_id
     * @param string $content
     * @param string $sender_id
	 * @param string $receiver_id
     * @param string $assignment_time
     * @param string $isNew
     * @return JSONResponse
     */
    public function updateInfoKMAUser($kma_noti_id, $content = null, $sender_id = null, $receiver_id = null, $assignment_time = null, $isNew = null) {
        $query = $this->db->prepare('UPDATE `oc_work_noti` SET `work_name` = COALESCE(?, `work_name`), 
                                                            `content` = COALESCE(?, `content`), 
                                                            `sender_id` = COALESCE(?, `sender_id`), 
                                                            `receiver_id` = COALESCE(?, `receiver_id`),
                                                            `assignment_time` = COALESCE(?, `assignment_time`),
                                                            `isNew` = COALESCE(?, `isNew`)
                                                                WHERE `kma_noti_id` = ?');
        $query->execute(array($content, $sender_id, $receiver_id, $assignment_time, $isNew, $kma_noti_id));
        return new JSONResponse(array('status' => 'success'));
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $kma_noti_id
     */
    public function deleteKmaWork($kma_work_id) {
        $query = $this->db->getQueryBuilder();
        $query->delete('kma_work_noti')
            ->where($query->expr()->eq('kma_noti_id', $query->createNamedParameter($kma_noti_id)))
            ->execute();
        return new DataResponse(['status' => 'success']);
    }

    

}