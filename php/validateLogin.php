<?php
    if(isset($_POST["username"]) && isset($_POST["password"])){
        session_start();

        require_once "tool.php"; 
        require_once "../conf.php";
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        class InvalidUser extends Exception {};
        
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $conn = new mysqli($dbAddress,$userLoger,$passLoger,$dbName);
            $id = null;
            $name = null;
            $query = $conn->query("SELECT id,user,password,level,nome FROM utenti");
            
            while ($row = $query->fetch_assoc()) {
                if(password_verify($username,$row["user"]) && password_verify($password,$row["password"])){
                    $name = $row["nome"];
                    $id = $row["id"];

                    if($row["level"] == false){
                        $user = $userOperator;
                        $pass = $passOperator;
                    } else {
                        $user = $userSuperior;
                        $pass = $passSuperior;
                    }

                    $query->free();
                    $query->close();
                    $conn->close();
                    unset($result);
                    unset($conn);
                    break;
                }
            }

            if(isset($user) && isset($pass)){
                $query = null;
                $conn = new mysqli($dbAddress,$user,$pass,$dbName);

                $conn->begin_transaction();

                try{
                    $descrizione="login";
                    $conn=logActivity($id,$descrizione,$conn);
                    
                    $data = date("Y-m-d H:i:s");
                    $query = $conn->prepare("UPDATE utenti SET last_login=? WHERE id=?");
                    $query->bind_param("si",$data,$id);
                    $query->execute();
                    $conn->commit();

                    $query->close();
                    $conn->close();

                    //sessione
                    $_SESSION["login"]["logged"] = true;
                    $_SESSION["login"]["id"] = $id;
                    $_SESSION["login"]["name"] = $name;

                    //cookie -due giorni
                    setcookie("logged",$id."-".$name,time()+172800,"/");
                } catch(Exception $e){
                    //echo $e->getMessage();
                    $conn->rollback();

                    if(isset($query)){
                        $query->close();
                        unset($query);
                    }

                    if(isset($conn)){
                        $conn->close();
                        unset($conn);
                    }

                    throw new mysqli_sql_exception();
                }

            } else {
                throw new InvalidUser("Utente non trovato");
            }  
        } catch(Exception $e){
            /* LASCIARE PER IL DEBUG
            echo $e->getMessage()."<br>";
            */
            if($e instanceof mysqli_sql_exception)
                $_SESSION["error"]["message"] = "Comunicazione con il server interrotta inaspettatamente";

            if($e instanceof InvalidUser)
                $_SESSION["error"]["message"] = "Utente non trovato. Riprova con username e/o password corretti";

            if(isset($query))
                $query->close();

            if(isset($conn))
                $conn->close();

            goLogin("../html/login.php");
        }
    }
?>