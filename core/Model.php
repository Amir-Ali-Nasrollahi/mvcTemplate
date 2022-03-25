<?php

class Model
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "music";
    private static $con;

    public function __construct()
    {
        try {
            self::$con = new PDO("mysql:dbname=$this->dbname;host=$this->host;", $this->username, $this->password);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function doQuery($query, $data = [])
    {
        $sql = self::$con->prepare($query);
        foreach ($data as $key => $item) {
            $sql->bindValue($key + 1, $item);
        }
        $sql->execute();
    }

    public static function doSelect($query, array $data = [], string $flag = '')
    {
        $sql = self::$con->prepare($query);
        foreach ($data as $key => $item) {
            $sql->bindValue($key + 1, $item);
        }
        $sql->execute();
        if ($flag == '') {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $sql->fetch(PDO::FETCH_ASSOC);
        }
    }

    public static function UploadImage($image, $folder)
    {
        $image_name = $image['name'];
        $image_name = time() . $image_name;
        $directory = "public/images/" . $folder;
        if ($image["size"] <= 5000000 && $image["size"] != null) {
            if (is_dir($directory)) {
                $directory = $directory . "/" . $image_name;
                move_uploaded_file($image["tmp_name"], $directory);
            } else {
                mkdir($directory);
                $directory = $directory . "/" . $image_name;
                move_uploaded_file($image["tmp_name"], $directory);
            }
            return $image_name;
        } else {
            echo "حجم عکس بزرگ تر از 5مگابایت بوده است";
            return "";
        }
    }

    public static function UploadMusic($music, $folder)
    {
        $music_name = $music["name"];
        $music_name = time() . $music_name;
        $directory = "public/music/" . $folder;
        if ($music["size"] <= 10000000 && $music["size"] != null) {
            if (is_dir($directory)) {
                $directory = $directory . "/" . $music_name;
                move_uploaded_file($music["tmp_name"], $directory);
            } else {
                mkdir($directory);
                $directory = $directory . "/" . $music_name;
                move_uploaded_file($music["tmp_name"], $directory);
            }
            return $music_name;
        } else {
            echo "حجم موزیک بزرگ تر از 10 مگابایت بوده یا اصلا موزیک اپلود نشده :(";
            return "";
        }
    }

    public static function conditionUploadMusic($music, $folder, $item, $name = 'music')
    {
        if ($music["name"] == null) {
            $music_name = $item[$name];
        } else {
            if (is_file("public/music/" . $folder . "/" . $item[$name])) {
                unlink("public/music/" . $folder . "/" . $item[$name]);
            }
            $music_name = Model::UploadMusic($music, $folder);
        }
        return $music_name;
    }

    public static function conditionUploadImage($image, $folder, $item, string $name = 'image')
    {
        if ($image["name"] == null) {
            $image_name = $item[$name];
        } else {
            if (is_file("public/images/" . $folder . "/" . $item[$name])) {
                unlink("public/images/" . $folder . "/" . $item[$name]);
                echo "success";
            }
            $image_name = Model::UploadImage($image, $folder);
        }
        return $image_name;
    }

    public static function webUrl($path)
    {
        header("location:" . URL . $path);
    }

    public static function setSession($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function getSession($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    public static function unsetSession($name)
    {
        $_SESSION[$name] = null;
    }

    public static function initSession()
    {
        session_start();
    }

    public static function deleteFile($file = [], $directory = [])
    {
        if (count($file) == count($directory)) {
            for ($i = 0; $i < count($file); $i++) {
                unlink($directory[$i] . $file[$i]);
            }
        }
    }


}