<?php
//Tilda Källström 2022
class Alerts
{
  private $db;
  function __construct()
  {
    $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
    if ($this->db->connect_errno > 0) {
      die("Fel vid anslutning: " . $this->db->connect_error);
    }
  }

  public function getAlerts($username)
  {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM alert JOIN comments on alert.commentid = comments.commentid JOIN blogposts on comments.postid = blogposts.postid where blogposts.author = '$username' LIMIT 5;";
    $result = $this->db->query($sql);
    return $result;
  }

  public function deleteAlert($id)
  {
    $id = intval($id);
    $sql = "DELETE FROM alert WHERE id=$id";
    return $this->db->query($sql);
  }
}
