<?php



class User {
    public $SessionID;
    public $Username;
    public $Password;
}

class Thread {
    public $Title;
    public $Author;
    public $Date;
    public $Content;
    public $Last_Comment;
    public $AmountOfReplies;
    public $Time_Since_Last_Activity;
    public $Time;

}

class Comment {
    public $CommentID;
    public $Thread;
    public $Author;
    public $Date;
    public $Content;
    public $Time;
}

class DB
{
    //can be put into include("db.inc.php") and loaded in constructor!
    public $host = "localhost";
    public $databaseName = "forum";
    public $username = "root";     //for mamp
    public $password = "klapot";     //for mamp

    /**
     * @return string
     */
    private function getConnectionString()
    {
        $dns = "mysql:host=$this->host;dbname=$this->databaseName";
        return $dns;
    }

    /**
     * @return PDO
     */
    private function createConnection()
    {
        $conn = new PDO($this->getConnectionString(), $this->username, $this->password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    }

    /**
     * @param TodoDb $instance
     * @return array of Todo items
     */
    public static function getTodos($instance): array
    {
        $sql = "SELECT todoId, description, done FROM Todos ORDER BY TodoId";

        $conn = $instance->createConnection();
        $stmtSelect = $conn->prepare($sql);
        $stmtSelect->execute();

        $rows = $stmtSelect->fetchAll(PDO::FETCH_CLASS, "Todo");

        $conn = null;
        return $rows;
    }

    /**
     * @return array of Thread items
     */
    public function getAllThreads(): array
    {
        $sql = "SELECT * FROM Thread ORDER BY Time_Since_Last_Activity DESC";

        $conn = $this->createConnection();
        $stmtSelect = $conn->prepare($sql);
        $stmtSelect->execute();

        $rows = $stmtSelect->fetchAll(PDO::FETCH_CLASS, "Thread");

        $conn = null;
        return $rows;
    }

    /**
     * @return array of Comment items
     */
    public function getAllComments($threadtitle): array
    {
        $sql = "SELECT * FROM Comment WHERE Thread = :threadtitle ORDER BY Time ASC";

        $conn = $this->createConnection();
        $stmtSelect = $conn->prepare($sql);
        $stmtSelect->bindValue(":threadtitle", $threadtitle, PDO::PARAM_STR);
        $stmtSelect->execute();

        $rows = $stmtSelect->fetchAll(PDO::FETCH_CLASS, "Comment");

        $conn = null;
        return $rows;
    }


    /**
     * @param string $username
     * @return User|null
     */
    public function getUser(string $username): ?User
    {
        $sqlEdit = "SELECT * FROM User WHERE Username = :username";

        $conn = $this->createConnection();
        $stmtEdit = $conn->prepare($sqlEdit);
        $stmtEdit->bindValue(":username", $username, PDO::PARAM_STR);

        if ($stmtEdit->execute()) {
            $result = $stmtEdit->fetchObject("User");
            if ($result === false) {
                $result = null;
            }
        } else {
            $result = null;
        }

        $conn = null;
        return $result;
    }

    /**
     * @param string $title
     * @return Thread|null
     */
    public function getThread(string $title): ?Thread
    {
        $sqlEdit = "SELECT * FROM Thread WHERE Title = :username";

        $conn = $this->createConnection();
        $stmtEdit = $conn->prepare($sqlEdit);
        $stmtEdit->bindValue(":username", $title, PDO::PARAM_STR);

        if ($stmtEdit->execute()) {
            $result = $stmtEdit->fetchObject("Thread");
            if ($result === false) {
                $result = null;
            }
        } else {
            $result = null;
        }

        $conn = null;
        return $result;
    }

    /**
     * @param int $commentID
     * @return Thread|null
     */
    public function getCommentFromID(int $commentID): ?Comment
    {
        $sqlEdit = "SELECT * FROM Comment WHERE CommentID = :id";

        $conn = $this->createConnection();
        $stmtEdit = $conn->prepare($sqlEdit);
        $stmtEdit->bindValue(":id", $commentID, PDO::PARAM_INT);

        if ($stmtEdit->execute()) {
            $result = $stmtEdit->fetchObject("Comment");
            if ($result === false) {
                $result = null;
            }
        } else {
            $result = null;
        }

        $conn = null;
        return $result;
    }

    /**
     * @param $todoId
     * @return bool
     */
    public function deleteTodo(int $todoId): bool
    {
        $sqlDelete = "DELETE FROM Todos WHERE TodoId = :todoId";

        $conn = $this->createConnection();
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindValue(":todoId", $todoId, PDO::PARAM_INT);

        $result = false;
        if ($stmtDelete->execute() && $stmtDelete->rowCount() == 1) {
            $result = true;
        }

        $conn = null;
        return $result;
    }

    public function addUser($username, $password)
    {
        $sqlInsert = "INSERT INTO User (Username, Password) VALUES (:username, :password)";

        $conn = $this->createConnection();
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bindValue(":username", $username, PDO::PARAM_STR);
        $stmtInsert->bindValue(":password", $password, PDO::PARAM_STR);

        $result = -1;
        if ($stmtInsert->execute() && $stmtInsert->rowCount() == 1) {
            $result = $conn->lastInsertId();
        }

        $conn = null;
        return $result;
    }

    public function addThread($author, $title, $content)
    {
        $sqlInsert = "INSERT INTO Thread (Title, Author, Date, Content, AmountOfReplies, Time_Since_Last_Activity, Time) 
        VALUES (:title, :author, NOW(), :content, 0, :time1, :time2)";

        $conn = $this->createConnection();
        $stmtInsert = $conn->prepare($sqlInsert);

        $stmtInsert->bindValue(":title", $title, PDO::PARAM_STR);
        $stmtInsert->bindValue(":author", $author, PDO::PARAM_STR);
        $stmtInsert->bindValue(":content", $content, PDO::PARAM_STR);
        $stmtInsert->bindValue(":time1", time(), PDO::PARAM_INT);
        $stmtInsert->bindValue(":time2", time(), PDO::PARAM_INT);

        $result = -1;
        if ($stmtInsert->execute() && $stmtInsert->rowCount() == 1) {
            $result = $conn->lastInsertId();
        }

        $conn = null;
        return $result;
    }

    public function addComment($thread, $author, $content, $time)
    {
        $sqlInsert = "INSERT INTO Comment (Thread, Author, Date, Content, Time) VALUES (:thread, :author, NOW(), :content, :time)";

        $conn = $this->createConnection();
        $stmtInsert = $conn->prepare($sqlInsert);

        $stmtInsert->bindValue(":thread", $thread, PDO::PARAM_STR);
        $stmtInsert->bindValue(":author", $author, PDO::PARAM_STR);
        $stmtInsert->bindValue(":content", $content, PDO::PARAM_STR);
        $stmtInsert->bindValue(":time", $time, PDO::PARAM_INT);

        $result = -1;
        if ($stmtInsert->execute() && $stmtInsert->rowCount() == 1) {
            $result = $conn->lastInsertId();
        }

        $conn = null;
        return $result;
    }

    public function updateThread(Thread $thread, $time, $commentID)
    {
        $sqlEdit = "UPDATE Thread SET Time_Since_Last_Activity = :time, Last_Comment = :commentID, AmountOfReplies = :replies WHERE Title = :title";

        $stmtEdit = $conn = $this->createConnection()->prepare($sqlEdit);

        $stmtEdit->bindValue(":title", $thread->Title, PDO::PARAM_STR);
        $stmtEdit->bindValue(":time", $time, PDO::PARAM_INT);
        $stmtEdit->bindValue(":commentID", $commentID, PDO::PARAM_INT);
        $stmtEdit->bindValue(":replies", $thread->AmountOfReplies + 1, PDO::PARAM_INT);

        $result = false;

        if ($stmtEdit->execute() && $stmtEdit->rowCount() == 1) {
            $result = true;
        }


        $conn = null;
        return $result;
    }

}

function bbCode($content) {
    $output = $content;
    $output = str_replace('[img]', '<img src="', $output);
    $output = str_replace('[/img]', '">', $output);
    $output = str_replace('[i]', '<i>', $output);
    $output = str_replace('[/i]', '</i>', $output);
    $output = str_replace('[b]', '<b>', $output);
    $output = str_replace('[/b]', '</b>', $output);

    return $output;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>