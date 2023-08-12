<?php
    class imageCreatorExc extends Exception{};
    require_once("tool.php");
    require_once("../conf.php");

    session_start();
    // isLogged("../",$_SESSION["login"]["level"],0);

    if(!isset($_GET["id"])){
        session_destroy();
        goLogin("../");
    }

    $id=trim($_GET["id"]);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn=null;
    $close=false;
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

        $i=0;
        foreach($files as $k=>$v){
            $ext=explode("/",$tipo[$k]);
            $img=imagecreatefromstring($files[$k]);
            if(!createImg($ext[1],$img,$id,$i)){
                throw new imageCreatorExc("Error during image creation");
            }

            $filesName[]="../tmp/img".$id."_".$i.".".$ext[1];

            $i++;
        }

        $filestring="[";
        $k=0;
        foreach($filesName as $v){
            $filestring.=(basename($v)).", ";
            $k++;
        }

        if($k>0)
            $filestring=substr($filestring,0,strlen($filestring)-2);

        $filestring.="]";

        $conn->change_user($userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],"Created images $filestring",$conn);

        $conn->close();
        unset($filestring);
        $close=true;

        $resultset=[];
        $resultset=[
            "filesName"=>$filesName,
            "tipo"=>$tipo,
            "data"=>$data,
            "descrizione"=>$descrizione,
            "id"=>$ids
        ];

        echo json_encode(["error"=>"no","num"=>$i,"resultset"=>$resultset]);

        // controllo file presenti da piu di un ora
        date_default_timezone_set("Europe/Rome");
        $filesInDir=scandir("../tmp",SCANDIR_SORT_DESCENDING);
        unset($filesInDir[count($filesInDir)-1]);
        unset($filesInDir[count($filesInDir)-1]);
        foreach($filesInDir as $v){
            $h=((time()-filemtime("../tmp/$v"))/3600);
            if($h>1)
                unlink("../tmp/$v");
        }

        //log della visualizzazione img
    }catch(Exception $e){
        echo json_encode(["error"=>"yes"]);
        $msg="Error while retriving images";
        if($e instanceof imageCreatorExc)
            $msg=$e->getMessage();

        $conn->change_user($userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],$msg,$conn);
    }finally{
        if(!$close)
            $conn->close();
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