<?php
    require_once("tool.php");
    require_once("../conf.php");

    // session_start();
    // isLogged("../",$_SESSION["login"]["level"],0);

    $id=$_GET["id"];
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try{
        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
        $stmt=$conn->prepare("SELECT data,foto,tipo,descrizione FROM allegati WHERE incarico=?");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result=$stmt->get_result();
        $files=[];
        $tipo=[];
        $i=0;

        while($row=$result->fetch_object()){
            $files[]=$row->foto;
            $tipo[]=$row->tipo;

            $i++;
        }

        $result->free();
        $stmt->close();
        $conn->close();

        foreach($files as $k=>$v){
            $ext=explode("/",$tipo[$k]);
            $img=imagecreatefromstring($files[$k]);
            imagepng($img,"../tmp/img".$id.".".$ext[1]);
            if(!createImg($ext[1],$img,$id)){
                echo json_encode(["error"=>"yes"]);
                return;
            }
        }

        echo json_encode(["error"=>"no","num"=>$i]);
    }catch(Exception $e){
        echo json_encode(["error"=>"yes"]);
    }

    function createImg($ext,$img,$id){
        switch($ext){
            case "png":
                return imagepng($img,"../tmp/img".$id.".".$ext);
            case "jpeg":
                return imagejpeg($img,"../tmp/img".$id.".".$ext);
            default:
                return false;
        }
    }
    
?>