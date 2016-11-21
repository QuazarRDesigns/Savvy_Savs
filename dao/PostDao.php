<?php

/**
 * Description of PostDao
 *
 * @author QuazarRDesigns
 */
class PostDao {

    /** @var PDO */
    private $db = null;

    public function __destruct() {
// close db connection
        $this->db = null;
    }

    /**
     * Find all {@link Post}s by search criteria.
     * @return array array of {@link Post}s
     */
    public function find($sql) {
        $result = array();
        foreach ($this->query($sql) as $row) {
            $post = new Post();
            PostMapper::map($post, $row);
            $result[$post->getId()] = $post;
        }
        return $result;
    }

    /**
     * Find {@link Post} by identifier.
     * @return Post Post or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM posts WHERE id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $post = new Post;
        PostMapper::map($post, $row);
        return $post;
    }

    /**
     * Save {@link Post}.
     * @param Post $post {@link Post} to be saved
     * @return Post saved {@link Post} instance
     */
    public function save(Post $post) {
        if ($post->getId() === null) {
            return $this->insert($post);
        }
        return $this->update($post);
    }

    /**
     * Delete {@link Post} by identifier.
     * @param int $id {@link Post} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = 'DELETE FROM posts WHERE id = ' . (int) $id;
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

//    private function getFindSql(PostSearchCriteria $search = null) {
//        $sql = 'SELECT * FROM post WHERE deleted = 0 ';
//        $orderBy = ' priority, due_on';
//        if ($search !== null) {
//            if ($search->getStatus() !== null) {
//                $sql .= 'AND status = ' . $this->getDb()->quote($search->getStatus());
//                switch ($search->getStatus()) {
//                    case Post::STATUS_PENDING:
//                        $orderBy = 'due_on, priority';
//                        break;
//                    case Post::STATUS_DONE:
//                    case Post::STATUS_VOIDED:
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
     * @return Post
     * @throws Exception
     */
    private function insert(Post $post) {
        $post->setId(null);
        $sql = '
            INSERT INTO posts (id, text, title, date_created, user_id)
                VALUES (:id, :text, :title, :date_created, :user_id)';
        return $this->execute($sql, $post);
    }

    /**
     * @return Post
     * @throws Exception
     */
    private function update(Post $post) {
        $sql = '
            UPDATE posts SET
                text = :text,
                title = :title,
                date_created = :date_created,
                user_id = :user_id
            WHERE
                id = :id';
        return $this->execute($sql, $post);
    }

    /**
     * @return Post
     * @throws Exception
     */
    private function execute($sql, Post $post) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($post));
        if ($post->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
//        if (!$statement->rowCount()) {
//            throw new NotFoundException('Post with ID "' . $post->getId() . '" does not exist.');
//        }
        return $post;
    }

    private function getParams(Post $post) {
        $params = array(
            ':id' => $post->getId(),
            ':text' => $post->getText(),
            ':title' => $post->getTitle(),
            ':date_created' => $post->getDate_created(),
            ':user_id' => $post->getUser_id()
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
// Post log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }
}