<?php
//Tilda Källström 2022
class Alerts
{

  private $db;


  function __construct()
  {
    //connect to db
    $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
    if ($this->db->connect_errno > 0) {
      die("Fel vid anslutning: " . $this->db->connect_error);
    }
  }
  //get 5 most recent alerts
  public function getAlerts($username)
  {
    $username = $_SESSION['username'];
    // $sql = "SELECT * FROM alert JOIN comments on alert.commentid = comments.commentid JOIN blogposts on comments.postid=blogposts.postid JOIN user on user.username=blogposts.author LIMIT 5;";
    $sql = "SELECT * FROM alert JOIN comments on alert.commentid = comments.commentid JOIN blogposts on comments.postid = blogposts.postid where blogposts.author = '$username' LIMIT 5;";
    $result = $this->db->query($sql);
    return $result;


    /*  $result = $this->db->query($sql);
        if ($result) {
            // result is garanteed to be an object here since a select query was preformed
            if ($result->num_rows) {
              return true;
            }
          }
          return false;       
            //}  */
  }
  public function deleteAlert($id)
  {
    $id = intval($id);
    $sql = "DELETE FROM alert WHERE id=$id";
    return $this->db->query($sql);
  }
}
