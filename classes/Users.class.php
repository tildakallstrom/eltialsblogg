<?php
//Tilda Källström 2022
class Users
{
  private $db;
  private $firstname;
  private $lastname;

  function __construct()
  {
    $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
    if ($this->db->connect_errno > 0) {
      die("Fel vid anslutning: " . $this->db->connect_error);
    }
  }

  public function registerUser($username, $firstname, $lastname, $email, $password)
  {
    $password = $this->db->real_escape_string($password);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user(username, firstname, lastname, email, password)VALUES('$username', '$firstname', '$lastname', '$email', '$password')";
    $result = $this->db->query($sql);
    return $result;
  }

  public function loginUser($username, $password)
  {
    $username = $this->db->real_escape_string($username);
    $password = $this->db->real_escape_string($password);
    $sql = "SELECT * FROM user WHERE username='$username'";
    $result = $this->db->query($sql);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $storedpassword = $row['password'];

      if ($storedpassword == crypt($password, $storedpassword)) {
        return $row['id'];
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function isUsernameTaken($username)
  {
    $username = $this->db->real_escape_string($username);
    $sql = "SELECT username FROM user WHERE username='$username'";
    $result = $this->db->query($sql);
    if ($result->num_rows > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function isEmailTaken($email)
  {
    $email = $this->db->real_escape_string($email);
    $sql = "SELECT email FROM user WHERE email='$email'";
    $result = $this->db->query($sql);
    if ($result->num_rows > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function getUsers()
  {
    $sql = "SELECT * FROM user ORDER BY ucreated DESC;";
    $result = $this->db->query($sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  public function getUserFromId($id)
  {
    $id = intval($id);
    $sql = "SELECT * FROM user WHERE id=$id;";
    $result = $this->db->query($sql);
    $row = mysqli_fetch_array($result);
    return $row;
  }

  public function isThisFollowed($username, $userid)
  {
    $username = $_SESSION['username'];
    $userid = $_GET['id'];
    $sql = "SELECT * FROM `following` WHERE username='$username' AND followerid=$userid";
    $result = $this->db->query($sql);
    if ($result) {
      if ($result->num_rows) {
        return true;
      }
    }
    return false;
  }

  public function followUser($username, $userid)
  {
    $username = $_SESSION['username'];
    $userid = $_GET['id'];
    $sql = "INSERT INTO `following` (username, followerid) VALUES('$username', $userid)";
    $result = $this->db->query($sql);
    return $result;
  }

  public function unfollowUser($username, $userid)
  {
    $username = $_SESSION['username'];
    $userid = $_GET['id'];
    $sql = "DELETE FROM `following` WHERE username='$username' AND followerid=$userid;";
    $result = $this->db->query($sql);
    return $result;
  }

  public function countFollower($id)
  {
    $id = intval($id);
    $sql = "SELECT COUNT(fid) AS NumberOfFollowers FROM following WHERE followerid = $id;";
    $result = $this->db->query($sql);
    return $result;
  }

  public function updateUser($firstname, $lastname, $email, $profile)
  {

    if (!$this->setFirstname($firstname)) {
      return false;
    }
    if (!$this->setLastname($lastname)) {
      return false;
    }
    if (!$this->setEmail($email)) {
      return false;
    }

    $profile = $_POST['profile'];
    $username = $_SESSION['username'];
    $sql = "UPDATE user SET firstname = '" . $this->firstname . "', lastname = '" . $this->lastname . "', email = '$email', profile = '$profile' WHERE username = '$username'";
    $result = $this->db->query($sql);
    return $result;
  }

  public function setEmail($email)
  {
    $email = $_POST['email'];
    if (filter_var($email)) {
      $email = strip_tags(html_entity_decode($email), '');
      $this->email = $this->db->real_escape_string($email);

      return true;
    } else {
      return false;
    }
  }

  public function setFirstname($firstname)
  {
    $firstname = $_POST['firstname'];
    if (filter_var($firstname)) {
      $firstname = strip_tags(html_entity_decode($firstname), '');
      $this->firstname = $this->db->real_escape_string($firstname);
      return true;
    } else {
      return false;
    }
  }

  public function setLastname($lastname)
  {
    $lastname = $_POST['lastname'];
    if (filter_var($lastname)) {
      $lastname = strip_tags(html_entity_decode($lastname), '');
      $this->lastname = $this->db->real_escape_string($lastname);
      return true;
    } else {
      return false;
    }
  }

  public function updateProfileimg()
  {
    $username = $_SESSION['username'];
    $profileimg = $_FILES['profileimg']['name'];

    if ((($_FILES["profileimg"]["type"] == "image/jpeg") || ($_FILES["profileimg"]["type"] ==
      "image/png") && ($_FILES["file"]["size"] < 200000))) {
      $profileimg = $_FILES["profileimg"]["name"];
      if ($profileimg) {
        $DimW = 250;
        $DimH = 250;
        list($width, $height) = getimagesize($_FILES['profileimg']['tmp_name']);
        if ($width > $DimW || $height > $DimH) {
          $target_filename = $_FILES['profileimg']['tmp_name'];
          $fn = $_FILES['profileimg']['tmp_name'];
          $size = getimagesize($fn);
          $ratio = $size[0] / $size[1]; // width/height
          if ($ratio > 1) {
            $width = $DimW;
            $height = $DimH / $ratio;
          } else {
            $width = $DimW * $ratio;
            $height = $DimH;
          }
          $src = imagecreatefromstring(file_get_contents($fn));
          $dst = imagecreatetruecolor($width, $height);
          imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
          imagejpeg($dst, $target_filename);
        }
        move_uploaded_file($_FILES["profileimg"]["tmp_name"], "profileimg/" . $_FILES["profileimg"]["name"]);
      }
    }

    if ((($_FILES["profileimg"]["type"] == "image/jpeg") || ($_FILES["profileimg"]["type"] ==
      "image/png"))) {
      $sql = "UPDATE user SET profileimg = '$profileimg' WHERE username = '$username'";
      $result = $this->db->query($sql);
      return $result;
    } else {
      return false;
    }
  }

  public function getFollowers(): array
  {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM user JOIN following on following.followerid = user.id WHERE following.username = '$username';";
    $result = $this->db->query($sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  public function deleteUser($username)
  {
    $username = $_SESSION['username'];
    $sql = "DELETE FROM user, blogposts
 USING user
 INNER JOIN blogposts on user.username = blogposts.author
 INNER JOIN comments on user.username = comments.user
 INNER JOIN following on user.username = following.username
 WHERE user.username='$username';";

    $result = $this->db->query($sql);
    return $result;
  }

  public function getUserFromUsername($username)
  {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM user  WHERE username = '$username';";
    $result = $this->db->query($sql);
    $row = mysqli_fetch_array($result);
    return $row;
  }
}
