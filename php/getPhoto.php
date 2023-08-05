<?php
    require_once("tool.php");
    require_once("../conf.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],0);

    $id=$_GET["id"];
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try{
        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
        $stmt=$conn->prepare("SELECT id,data,foto,tipo,descrizione FROM allegati WHERE incarico=? ORDER BY data ASC");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result=$stmt->get_result();
        $files=[];
        $tipo=[];
        $data=[];
        $ids=[];
        $descrizione=[];
        $filesName=[];

        while($row=$result->fetch_object()){
            $files[]=$row->foto;
            $tipo[]=$row->tipo;
            $data[]=$row->data;
            $ids[]=$row->id;
            $descrizione[]=$row->descrizione!=null?$row->descrizione:"";
        }

        $result->free();
        $stmt->close();
        $conn->close();

        $i=0;
        foreach($files as $k=>$v){
            $ext=explode("/",$tipo[$k]);
            $img=imagecreatefromstring($files[$k]);
            if(!createImg($ext[1],$img,$id,$i)){
                echo json_encode(["error"=>"yes"]);
                return;
            }

            $filesName[]="../tmp/img".$id."_".$i.".".$ext[1];

            $i++;
        }

        $resultset=[];
        $resultset=[
            "filesName"=>$filesName,
            "tipo"=>$tipo,
            "data"=>$data,
            "descrizione"=>$descrizione,
            "id"=>$ids
        ];

        echo json_encode(["error"=>"no","num"=>$i,"resultset"=>$resultset]);

        //controllo file presenti da piu di un ora
        date_default_timezone_set("Europe/Rome");
        $filesInDir=scandir("../tmp",SCANDIR_SORT_DESCENDING);
        unset($filesInDir[count($filesInDir)-1]);
        unset($filesInDir[count($filesInDir)-1]);
        foreach($filesInDir as $v){
            $h=((time()-filemtime("../tmp/$v"))/3600);
            if($h>0)
                unlink("../tmp/$v");
        }

        //log della visualizzazione img
    }catch(Exception $e){
        echo json_encode(["error"=>"yes"]);
    }

    function createImg($ext,$img,$id,$imgCount){
        switch($ext){
            case "png":
                return imagepng($img,"../tmp/img".$id."_".$imgCount.".".$ext);
            case "jpeg":
                return imagejpeg($img,"../tmp/img".$id."_".$imgCount.".".$ext);
            default:
                return false;
        }
    }
    
?>