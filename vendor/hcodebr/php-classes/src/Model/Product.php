<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Product extends Model {

    public static function listAll(){

        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_products ORDER BY desproduct");

        foreach ($results as $key => $value) {

            $dest = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
            "ecommerce" . DIRECTORY_SEPARATOR .
            "res" . DIRECTORY_SEPARATOR .
            "site" . DIRECTORY_SEPARATOR .
            "img" . DIRECTORY_SEPARATOR .
            "products" . DIRECTORY_SEPARATOR .
            $value["idproduct"] . ".jpg";

            if(!file_exists($dest)){
                $results[$key]["desphoto"]="/ecommerce/res/site/img/product.jpg";
            } else {
                $results[$key]["desphoto"]="/ecommerce/res/site/img/products/".$value["idproduct"].".jpg";
            }

        }

        return $results;

    }

    public function save(){



        $sql = new Sql();
        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));


        $this->setData($results[0]);

    }

    public function get($idproduct){
        $sql=new Sql();
        $results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct",[
            ":idproduct"=>$idproduct
        ]);


        $dest = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
        "ecommerce" . DIRECTORY_SEPARATOR .
        "res" . DIRECTORY_SEPARATOR .
        "site" . DIRECTORY_SEPARATOR .
        "img" . DIRECTORY_SEPARATOR .
        "products" . DIRECTORY_SEPARATOR .
        $idproduct . ".jpg";

        if(!file_exists($dest)){
            $results[0]["desphoto"]="/ecommerce/res/site/img/product.jpg";
        } else {
            $results[0]["desphoto"]="/ecommerce/res/site/img/products/".$idproduct.".jpg";
        }


        $this->setData($results[0]);


    }

    public function delete() {

        $sql = new Sql();

        $sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct",[
            ":idproduct"=>$this->getidproduct()
        ]);
    }

    public function setPhoto($file) {

        $extension = explode('.',$file["name"]);
        $extension = end($extension);
        switch ($extension) {
            case "jpg":
            case "jpeg":
            $image = imagecreatefromjpeg($file["tmp_name"]);
            break;

            case "gif":
            $image = imagecreatefromgif($file["tmp_name"]);
            break;

            case "png":
            $image = imagecreatefrompng($file["tmp_name"]);
            break;
        }

        $dest = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
        "ecommerce" . DIRECTORY_SEPARATOR .
        "res" . DIRECTORY_SEPARATOR .
        "site" . DIRECTORY_SEPARATOR .
        "img" . DIRECTORY_SEPARATOR .
        "products" . DIRECTORY_SEPARATOR .
        $this->getidproduct() . ".jpg";

        imagejpeg($image,$dest);

        imagedestroy($image);
    }



}



 ?>
