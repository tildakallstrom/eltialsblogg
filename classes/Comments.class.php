<?php
//Tilda Källström 2022
class Comments
{
    private $db;
    private $message;

    function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if ($this->db->connect_errno > 0) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    }

    public function alert()
    {
        $username = $_GET['username'];
        $sql = "SELECT * FROM comments JOIN blogposts on comments.postid=blogposts.postid join user on user.username=blogposts.author WHERE blogposts.author = $username LIMIT 5;";
        return $this->db->query($sql);
    }

    public function getComments(): array
    {
        $postid = $_GET['postid'];
        $sql = "SELECT * FROM user JOIN comments on user.username=comments.user join blogposts on blogposts.postid=comments.postid WHERE blogposts.postid = $postid ORDER BY commented DESC; ";
        $result = $this->db->query($sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function addComment($username, $postid, $message)
    {
        $username = $_SESSION['username'];
        //postens id
        $postid = intval($postid);

        if (!$this->setMessage($message)) {
            return false;
        }

        $sql = "INSERT INTO comments(user, postid, message)VALUES('$username', $postid, '" . $this->message . "');";

        $result = $this->db->query($sql);
        return $result;
    }

    public function setMessage($message)
    {
        if (filter_var($message)) {
            $message = strip_tags(html_entity_decode($message), '<p><li><ol><a><br><i><b><strong><em>');
            $this->message = $this->db->real_escape_string($message);
            $this->message = $message;
            return true;
        } else {
            return false;
        }
    }

    public function deleteComment($commentid)
    {
        $commentid = intval($commentid);
        $sql = "DELETE FROM comments WHERE commentid=$commentid";
        return $this->db->query($sql);
    }
}
