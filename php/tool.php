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
        @$query = $conn->prepare("INSERT INTO logoffice (autore,descrizione,data) VALUES (?,?,NOW(3))");

        $query->bind_param("is",$id,$descrizione);
        $query->execute();
        $query->close();

        return $conn;
    }

    function isLogged($home,$actualLvl,$minReqLvl){
        if(!isset($_SESSION["login"]) || $actualLvl<$minReqLvl){
            session_destroy();
            // header("Location:$home");
            goLogin($home);
            die();
        }
    }

    function mapSQLError($code){
        $message="";
        switch($code){
            case 1044:
                $message="Impossibile trovare il Database";
            break;
            case 1045:
                $message="Errore di login al Database";
            break;
            case 1062:
                $message="Codice identificativo dell'incarico e/o già esistente nel Database, generarne uno nuovo";
            break;
            case 1146:
                $message="Impossibile salvare/trovare l'incarico a causa di una tabella inesistente";
            break;
            case 1216:
                $message="Impossibile salvare/trovare l'incarico a causa di una chiave esterna inesistente";
            break;
            case 1451:
                $message="Impossibile salvare/trovare l'incarico, violazione vincolo di chiave esterna";
            break;
            case 1064:
                $message="Impossibile salvare/trovare l'incarico, chiamare un tecnico per risolvere i problemi di sintassi";
            break;
            case 2006:
                $message="Connessione al Database persa, riprovare";
            break;
            case 2013:
                $message="Impossibile salvare/trovare i dati a causa di una configurazione che va in conflitto con quella esistente, riprovare. Se il problema persiste contattare un tecnico";
            break;
            case "bigFileExc":
                $message="E' stato inserito un file troppo grande";
                break;
            default:
                $message="Impossibile salvare/trovare i dati. Errore generico. Se il problema persiste contattare il tecnico";
            break;
        }

        return $message;
    }

    function mapStates($stato){
        $ret="";

        switch($stato){
            case 1:
                $ret="IN CONSEGNA";
                break;
            case 2:
                $ret="CONSEGNATO";
                break;
            case 3:
                $ret="RITIRO EFFETTUATO";
                break;
            case 4:
                $ret="IN GIACENZA";
                break;
            case 5:
                $ret="RIFIUTATO";
                break;
            case 6:
                $ret="ASSEGNATO";
                break;
            case 7:
                $ret="SMARRITO";
                break;
            case 8:
                $ret="AGGIUNTA ALLEGATI";
                break;
            case 9:
                $ret="PRONTO PER PARTENZA";
                break;
            case 10:
                $ret="RISPEDITO AL MITT";
                break;
            case 11:
                $ret="ARRIVATO IN MAGAZZINO";
                break;
            default:
                $ret="new unknow state";
                break;
        }


        return $ret;
    }
?>