<?php

/**
 * Description of CommentDao
 *
 * @author QuazarRDesigns
 */
class CommentDao {

    /** @var PDO */
    private $db = null;

    public function __destruct() {
// close db connection
        $this->db = null;
    }

    /**
     * Find all {@link Comment}s by search criteria.
     * @return array array of {@link Comment}s
     */
    public function find($sql) {
        $result = array();
        foreach ($this->query($sql) as $row) {
            $comment = new Comment();
            CommentMapper::map($comment, $row);
            $result[$comment->getId()] = $comment;
        }
        return $result;
    }

    /**
     * Find {@link Comment} by identifier.
     * @return Comment Comment or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM comments WHERE id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $comment = new Comment;
        CommentMapper::map($comment, $row);
        return $comment;
    }

    /**
     * Save {@link Comment}.
     * @param Comment $comment {@link Comment} to be saved
     * @return Comment saved {@link Comment} instance
     */
    public function save(Comment $comment) {
        if ($comment->getId() === null) {
            return $this->insert($comment);
        }
        return $this->update($comment);
    }

    /**
     * Delete {@link Comment} by identifier.
     * @param int $id {@link Comment} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            DELETE FROM comments WHERE id = ' . (int) $id;
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
        ));
        return $statement->rowCount() == 1;
    }

    /**
     * @return PDO
     */
    private function getDb() {
        if ($this->db !== null) {
            return $this->db;
        }
        $config = Config::getConfig("db");
        try {
            $this->db = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (Exception $ex) {
            throw new Exception('DB connection error: ' . $ex->getMessage());
        }
        return $this->db;
    }

//    private function getFindSql(CommentSearchCriteria $search = null) {
//        $sql = 'SELECT * FROM comment WHERE deleted = 0 ';
//        $orderBy = ' priority, due_on';
//        if ($search !== null) {
//            if ($search->getStatus() !== null) {
//                $sql .= 'AND status = ' . $this->getDb()->quote($search->getStatus());
//                switch ($search->getStatus()) {
//                    case Comment::STATUS_PENDING:
//                        $orderBy = 'due_on, priority';
//                        break;
//                    case Comment::STATUS_DONE:
//                    case Comment::STATUS_VOIDED:
//                        $orderBy = 'due_on DESC, priority';
//                        break;
//                    default:
//                        throw new Exception('No order for status: ' . $search->getStatus());
//                }
//            }
//        }
//        $sql .= ' ORDER BY ' . $orderBy;
//        return $sql;
//    }
    /**
     * @return Comment
     * @throws Exception
     */
    private function insert(Comment $comment) {
//$now = new DateTime();
        $comment->setId(null);
        $sql = '
            INSERT INTO comments (id, comment, date_created, user_id, post_id)
                VALUES (:id, :comment, :date_created, :user_id, :post_id)';
        return $this->execute($sql, $comment);
    }

    /**
     * @return Comment
     * @throws Exception
     */
    private function update(Comment $comment) {
        $sql = '
            UPDATE comments SET
                comment = :comment,
                date_created = :date_created,
                user_id = :user_id,
                post_id = :post_id
            WHERE
                id = :id';
        return $this->execute($sql, $comment);
    }

    /**
     * @return Comment
     * @throws Exception
     */
    private function execute($sql, Comment $comment) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($comment));
        if ($comment->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
//        if (!$statement->rowCount()) {
//            throw new NotFoundException('Comment with ID "' . $comment->getId() . '" does not exist.');
//        }
        return $comment;
    }

    private function getParams(Comment $comment) {
        $params = array(
            ':id' => $comment->getId(),
            ':comment' => $comment->getComment(),
            ':date_created' => $comment->getDate_created(),
            ':user_id' => $comment->getUser_id(),
            ':post_id' => $comment->getPost_id()
        );
        return $params;
    }

    private function executeStatement(PDOStatement $statement, array $params) {
        if (!$statement->execute($params)) {
            self::throwDbError($this->getDb()->errorInfo());
        }
    }

    /**
     * @return PDOStatement
     */
    private function query($sql) {
        $statement = $this->getDb()->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
        return $statement;
    }

    private static function throwDbError(array $errorInfo) {
// Comment log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    private static function formatDateTime(DateTime $date) {
        return $date->format(DateTime::ISO8601);
    }

}
