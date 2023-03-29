<?php
//Tilda Källström 2022 
class Blogposts
{
    private $db;
    private $title;
    private $content;

    function __construct()
    {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if ($this->db->connect_errno > 0) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    }

    public function getBlogposts(): array
    {
        $sql = "SELECT * FROM blogposts JOIN user where user.username = blogposts.author ORDER BY created DESC;";
        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    //get all the blogposts from a specific user
    public function getBlogpostsFromAuthor(): array
    {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM blogposts JOIN user WHERE user.username ='$username' ORDER BY created DESC;";
        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getBlogpostsFromThisAuthor(): array
    {
        $id = $_GET['id'];
        $sql = "SELECT * FROM blogposts JOIN user on blogposts.authorid=user.id WHERE blogposts.authorid = $id ORDER BY created DESC;";
        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getBlogpostFromId($postid)
    {
        $postid = intval($postid);
        $sql = "SELECT * FROM blogposts JOIN user WHERE postid=$postid AND user.username = blogposts.author;";
        $result = $this->db->query($sql);
        $row = mysqli_fetch_array($result);
        return $row;
    }

    public function addBlogpost($author, $authorid, $title, $content, $img)
    {
        //Control if correct values
        if (!$this->setTitle($title)) {
            return false;
        }
        if (!$this->setContent($content)) {
            return false;
        }

        //is the file of the right type? 
        if ((($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] ==
            "image/png") && ($_FILES["file"]["size"] < 200000))) {
            $img = $_FILES["image"]["name"];
            if ($img) {
                // change size
                $maxDimW = 500;
                $maxDimH = 500;
                list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
                if ($width > $maxDimW || $height > $maxDimH) {
                    $target_filename = $_FILES['image']['tmp_name'];
                    $fn = $_FILES['image']['tmp_name'];
                    $size = getimagesize($fn);
                    $ratio = $size[0] / $size[1]; // width/height
                    if ($ratio > 1) {
                        $width = $maxDimW;
                        $height = $maxDimH / $ratio;
                    } else {
                        $width = $maxDimW * $ratio;
                        $height = $maxDimH;
                    }
                    $src = imagecreatefromstring(file_get_contents($fn));
                    $dst = imagecreatetruecolor($width, $height);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                    imagejpeg($dst, $target_filename);
                }        //move file     
                move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $_FILES["image"]["name"]);
            }
        }
        //if the file is the right type, put in db, else create post without img
        if ((($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] ==
            "image/png"))) {
            $sql = "INSERT INTO blogposts(author, authorid, title, content, img)VALUES('$author', $authorid, '" . $this->title . "', '" . $this->content . "', '$img');";
            $result = $this->db->query($sql);
            return $result;
        } else {
            $sql2 = "INSERT INTO blogposts(author, authorid, title, content)VALUES('$author', $authorid, '" . $this->title . "', '" . $this->content . "');";
            $result2 = $this->db->query($sql2);
            return $result2;
        }
    }

    public function deleteBlogpost($postid)
    {
        $postid = intval($postid);
        $sql = "SELECT img FROM blogposts WHERE postid = $postid;";
        $result = $this->db->query($sql);
        $row = mysqli_fetch_array($result);
        $img = $row['img'];
        $target = "uploads/" . $img;
        //delete img from folder
        if ($img != "") {
            unlink($target);
            $sql2 = "DELETE FROM blogposts WHERE postid=$postid;";
            return $this->db->query($sql2);
        } else {
            $sql1 = "DELETE FROM blogposts WHERE postid=$postid;";
            return $this->db->query($sql1);
        }
    }

    public function updateBlogpost($title, $content, $postid)
    {
        $postid = intval($postid);
        if (!$this->setTitle($title)) {
            return false;
        }
        if (!$this->setContent($content)) {
            return false;
        }
        $sql = "UPDATE blogposts SET title='" . $this->title . "', content='" . $this->content . "' WHERE postid=$postid;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function setTitle($title)
    {
        if (filter_var($title)) {
            $title = strip_tags(html_entity_decode($title), '<p><a><br><i><b><strong><em>');
            $this->title = $this->db->real_escape_string($title);
            return true;
        } else {
            return false;
        }
    }

    public function setContent($content)
    {
        if (filter_var($content)) {
            $content = strip_tags(html_entity_decode($content), '<p><li><ol><a><br><i><b><strong><em>');

            $this->content = $this->db->real_escape_string($content);
            return true;
        } else {
            return false;
        }
    }

    public function getFiveBlogposts(): array
    {
        $sql = "SELECT * FROM blogposts JOIN user where user.username = blogposts.author ORDER BY created DESC LIMIT 5;";
        $result = $this->db->query($sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getFollowedPosts(): array
    {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM user JOIN (SELECT * FROM blogposts JOIN following ON following.followerid = blogposts.authorid) followerposts ON followerposts.followerid = user.id WHERE followerposts.username = '$username' ORDER BY CREATED DESC;";

        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function countComments($postid)
    {
        $postid = intval($postid);
        $sql = "SELECT COUNT(commentid) AS NumberOfComments FROM comments where postid = $postid;";
        $result = $this->db->query($sql);
        return $result;
    }
}
