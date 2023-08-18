<?php
    require_once "tool.php"; 
    if(isset($_POST["username"]) && isset($_POST["password"])){
        session_start();

        require_once "../conf.php";
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        class InvalidUser extends Exception {};
        
        //genera eccezioni agli errori
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $conn = new mysqli($dbAddress,$userLoger,$passLoger,$dbName);
            $id = null;
            $name = null;
            $lvl=null;
            $query = $conn->query("SELECT id,user,password,level,nome FROM utenti");
            
            while ($row = $query->fetch_assoc()) {
                if(password_verify($username,$row["user"]) && password_verify($password,$row["password"])){
                    $name = $row["nome"];
                    $id = $row["id"];

                    $lvl=$row["level"];
                    if($row["level"] == false){
                        $user = $userOperator;
                        $pass = $passOperator;
                    } else {
                        $user = $userSuperior;
                        $pass = $passSuperior;
                    }

                    //$query->free();
                    break;
                }
            }
            $query->close();
            $conn->close();
            unset($conn);

            if(isset($user) && isset($pass)){
                $query = null;
                $conn = new mysqli($dbAddress,$user,$pass,$dbName);

                $conn->begin_transaction();

                try{
                    $descrizione="login";
                    $conn=logActivity($id,$descrizione,$conn);
                    
                    $query = $conn->prepare("UPDATE utenti SET last_login=NOW(3) WHERE id=?");
                    $query->bind_param("i",$id);
                    $query->execute();
                    $conn->commit();

                    $query->close();
                    $conn->close();

                    //sessione
                    $_SESSION["login"]["logged"] = true;
                    $_SESSION["login"]["id"] = $id;
                    $_SESSION["login"]["name"] = $name;
                    $_SESSION["login"]["level"]=$lvl;
                    
                    header("Location: ../html/dashboard.php");
                } catch(Exception $e){
                    //echo $e->getMessage();
                    $conn->rollback();

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


            goLogin("../html/login.php");
        }finally{
            if(isset($query))
            $query->close();

            if(isset($conn))
                $conn->close();
        }
    }else{
        goLogin("../html/login.php");
    }
?>
