<?php

/**
 * Description of UserDao
 *
 * @author QuazarRDesigns
 */
class UserDao {

    /** @var PDO */
    private $db = null;

    public function __destruct() {
// close db connection
        $this->db = null;
    }

    /**
     * Find all {@link User}s by search criteria.
     * @return array array of {@link User}s
     */
    public function find($sql) {
        $result = array();
        foreach ($this->query($sql) as $row) {
            $user = new User();
            UserMapper::map($user, $row);
            $result[$user->getId()] = $user;
        }
        return $result;
    }

    /**
     * Find {@link User} by identifier.
     * @return User User or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM users WHERE status != "deleted" AND id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $user = new User;
        UserMapper::map($user, $row);
        return $user;
    }

    /**
     * Save {@link User}.
     * @param User $user {@link User} to be saved
     * @return User saved {@link User} instance
     */
    public function save(User $user) {
        if ($user->getId() === null) {
            return $this->insert($user);
        }
        return $this->update($user);
    }

    /**
     * Delete {@link User} by identifier.
     * @param int $id {@link User} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            UPDATE users SET
                status = :status
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
            ':status' => 'deleted',
            ':id' => $id,
        ));
        return $statement->rowCount() == 1;
    }
    
    public function getUserDetails($username, $password) {
        $row = $this->query('SELECT * FROM `users` WHERE username="' . $username . '"AND password="' . $password . '" ')->fetch();
        if (!$row) {
            return null;
        }
        $user = new User;
        UserMapper::map($user, $row);
        return $user;
        
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

//    private function getFindSql(UserSearchCriteria $search = null) {
//        $sql = 'SELECT * FROM user WHERE deleted = 0 ';
//        $orderBy = ' priority, due_on';
//        if ($search !== null) {
//            if ($search->getStatus() !== null) {
//                $sql .= 'AND status = ' . $this->getDb()->quote($search->getStatus());
//                switch ($search->getStatus()) {
//                    case User::STATUS_PENDING:
//                        $orderBy = 'due_on, priority';
//                        break;
//                    case User::STATUS_DONE:
//                    case User::STATUS_VOIDED:
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
     * @return User
     * @throws Exception
     */
    private function insert(User $user) {
//$now = new DateTime();
        $user->setId(null);
        $user->setStatus('pending');
        $sql = '
            INSERT INTO users (id, first_name, last_name, username, password, status)
                VALUES (:id, :first_name, :last_name, :username, :password, :status)';
        return $this->execute($sql, $user);
    }

    /**
     * @return User
     * @throws Exception
     */
    private function update(User $user) {
        $sql = '
            UPDATE users SET
                first_name = :first_name,
                last_name = :last_name,
                username = :username,
                password = :password,
                status = :status
            WHERE
                id = :id';
        return $this->execute($sql, $user);
    }

    /**
     * @return User
     * @throws Exception
     */
    private function execute($sql, User $user) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($user));
        if (!$user->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
//        if (!$statement->rowCount()) {
//            throw new NotFoundException('User with ID "' . $user->getId() . '" does not exist.');
//        }
        return $user;
    }

    private function getParams(User $user) {
        $params = array(
            ':id' => $user->getId(),
            ':first_name' => $user->getFirstName(),
            ':last_name' => $user->getLastName(),
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword(),
            ':status' => $user->getStatus()
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
// User log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    private static function formatDateTime(DateTime $date) {
        return $date->format(DateTime::ISO8601);
    }

}