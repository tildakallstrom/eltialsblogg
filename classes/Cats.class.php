<?php
//Tilda Källström 2022
class Cats
{
    private $db;
    private $catid;
    private $userid;
    private $name;
    private $catimg;
    private $birth;
    private $mother;
    private $father;
    private $merits;


    function __construct()
    {
        //connect to db
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if ($this->db->connect_errno > 0) {
            die("Fel vid anslutning: " . $this->db->connect_error);
        }
    }

    public function getCatsFromOwner()
    {
        $userid = $_SESSION['username'];
        $sql = "SELECT * FROM cat JOIN user WHERE user.username ='$userid';";
        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getCatsFromUser(): array
    {
        $id = $_GET['id'];
        $sql = "SELECT * FROM cats JOIN user on cats.ownerid=user.id WHERE cats.ownerid = $id;";
        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function addCat($ownerid, $userid, $name, $catimg, $birth, $mother, $father, $merits)
    {
        if (!$this->setName($name)) {
            return false;
        }

        if ((($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] ==
            "image/png") && ($_FILES["file"]["size"] < 200000))) {
            $img = $_FILES["image"]["name"];
            if ($img) {
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
                }
                move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/cats/" . $_FILES["image"]["name"]);
            }
        }
        if (!$this->setBirth($birth)) {
            return false;
        }
        if (!$this->setMother($mother)) {
            return false;
        }
        if (!$this->setFather($father)) {
            return false;
        }
        if (!$this->setMerits($merits)) {
            return false;
        }

        if ((($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] ==
            "image/png"))) {
            $sql = "INSERT INTO cat(ownerid, userid, name, catimg, birth, mother, father, merits)VALUES('$ownerid', '$userid', '" . $this->name . "', '$catimg', '" . $this->birth . "', '" . $this->mother . "', '" . $this->father . "',  '" . $this->merits . "');";
            $result = $this->db->query($sql);
            return $result;
        } else {
            $sql2 = "INSERT INTO cat(ownerid, userid, name, catimg, birth, mother, father, merits)VALUES('$ownerid', '$userid', '" . $this->name . "', 'none.jpg', '" . $this->birth . "', '" . $this->mother . "', '" . $this->father . "',  '" . $this->merits . "');";
            $result2 = $this->db->query($sql2);
            return $result2;
        }
    }

    public function deleteCat($catid)
    {
        $catid = intval($catid);
        $sql = "DELETE FROM cat WHERE catid=$catid;";
        return $this->db->query($sql);
    }

    public function getCatFromId($catid)
    {
        $catid = intval($catid);
        $sql = "SELECT * FROM cat JOIN user WHERE catid=$catid AND user.username = cat.userid;";
        $result = $this->db->query($sql);
        $row = mysqli_fetch_array($result);
        return $row;
    }

    public function updateCat($name, $birth, $mother, $father, $merits, $catid)
    {
        $catid = intval($catid);
        if (!$this->setName($name)) {
            return false;
        }
        if (!$this->setBirth($birth)) {
            return false;
        }
        if (!$this->setMother($mother)) {
            return false;
        }
        if (!$this->setFather($father)) {
            return false;
        }
        if (!$this->setMerits($merits)) {
            return false;
        }
        $sql = "UPDATE cat SET name='" . $this->name . "', birth='" . $this->birth . "', mother='" . $this->mother . "', father='" . $this->father . "', merits='" . $this->merits . "' WHERE catid=$catid;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function setName($name)
    {
        if (filter_var($name)) {
            $name = strip_tags(html_entity_decode($name), '<p>');
            $this->name = $this->db->real_escape_string($name);
            return true;
        } else {
            return false;
        }
    }

    public function setMother($mother)
    {
        if (filter_var($mother)) {
            $mother = strip_tags(html_entity_decode($mother), '<p>');
            $this->mother = $this->db->real_escape_string($mother);
            return true;
        } else {
            return false;
        }
    }

    public function setFather($father)
    {
        if (filter_var($father)) {
            $father = strip_tags(html_entity_decode($father), '<p>');
            $this->father = $this->db->real_escape_string($father);
            return true;
        } else {
            return false;
        }
    }

    public function setMerits($merits)
    {
        if (filter_var($merits)) {
            $merits = strip_tags(html_entity_decode($merits), '<p><li><ol><a><br><i><b><strong><em>');

            $this->merits = $this->db->real_escape_string($merits);
            return true;
        } else {
            return false;
        }
    }
    
    public function setBirth($birth)
    {
        if (filter_var($birth)) {
            $birth = strip_tags(html_entity_decode($birth), '<p>');

            $this->birth = $this->db->real_escape_string($birth);
            return true;
        } else {
            return false;
        }
    }

    public function getCats()
    {
        $sql = "Select * from cat join user on user.id = cat.ownerid ORDER BY cat.name;";
        $result = $this->db->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
