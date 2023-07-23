<?php
    function encrypt($plaintext,$conf){
        require_once $conf;
        $iv = openssl_random_pseudo_bytes(16);
        return base64_encode($iv).openssl_encrypt($plaintext,$ciph_algo,$key,0,$iv);
    }

    function decrypt($ciphertext,$conf){
        require $conf;
        $iv = substr($ciphertext, 0, 24);
        $ciphertext = substr($ciphertext, 24);
        $iv = base64_decode($iv);
        return openssl_decrypt($ciphertext, $ciph_algo, $key, 0, $iv);
    }
    
    function goLogin($url){
        header("Location: $url");
    }

    function logActivity($id,$descrizione,$conn){
        @$query = $conn->prepare("INSERT INTO logoffice (autore,descrizione,data) VALUES (?,?,?)");
        date_default_timezone_set('Europe/Rome');
        $data = date("Y-m-d H:i:s");
        $query->bind_param("iss",$id,$descrizione,$data);
        $query->execute();
        $query->close();

        return $conn;
    }

    function isLogged($home,$actualLvl,$minReqLvl){
        if(!isset($_SESSION["login"]) || $actualLvl<$minReqLvl){
            session_destroy();
            header("Location:$home");
            die();
        }
    }
?>