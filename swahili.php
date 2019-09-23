<?php
//including the connection
include_once 'connection.php';
//prompt for user registration
/******messageFunction incase user input invalid menu*/
function userMessage(){
  echo "Samahani uchaguzi uloweka hautambuliki.";
  exit();
}
/*******userMessage ENDS HERE*/
//******************************userRegistration function**********************************
/*incase of changing the modes from jisajili to others consider changing level 1*/
function userRegistration()
{
    global $level,$conn,$phoneNumber;
    if (isset($level[0])&& $level[0]==2 && !isset($level[1])) {
        echo "Karibu bonyeza 1 Kujisajili\n1. Jisajili";
    }
    //user input firstname
    elseif (isset($level[1]) && $level[1] != "" && $level[0]==2 && !isset($level[2])) {
        switch ($level[1]) {
      case 1:
      echo "Jaza jina la kwanza:";
      break;

      default:
      echo "Uchaguzi si sahihi";
      break;
    }
    }
    //user input lastname (user must ensure the first case level 1 is 1) otherwise blank screen
    elseif (isset($level[2]) && $level[2] != "" && $level[0]==2 && $level[1]==1 && !isset($level[3])) {
        echo "Jaza jina la mwisho:";
    }

    //user input first passowrd
    elseif (isset($level[3]) && $level[3] != "" && $level[0]==2 && !isset($level[4])) {
        echo "Jaza tarakimu nne (PIN):";
    }

    //user input confirm passowrd
    elseif (isset($level[4]) && $level[4] != "" && $level[1]==1 && $level[0]==2 && !isset($level[5])) {
        echo "Jaza tena tarakimu nne (PIN):";
    } elseif (isset($level[5]) && $level[5] != "" && $level[1]==1 && $level[0]==2 && !isset($level[6])) {
        //comparing the passwords
        if ($level[4] != $level[5]) {
            echo "Namba za siri (PIN) hazifanani:";
        }
        //checking the length
        else if(preg_match("/[a-zA-Z%'~]/",$level[4])){
          echo "Tafadhali jaza namba pekee si herufi.";
          exit();
        }
        //if the inputed PIN is less than 4 digits or greater the four digit
        else if(strlen($level[4])>4){
          echo "Namba ya siri (PIN) ni ndefu mno, tafadhali jaza tarakimu nne tu.";
          exit();
        }

        //if the inputed PIN is less than 4 digits
        else if(strlen($level[4])<4){
          echo "Namba ya siri (PIN) ni fupi mno, tafadhali jaza tarakimu nne tu.";
          exit();
        }

        //passwords match
        else {
            global $phoneNumber;
            echo "Uhakiki wa taarifa:\n\n";
            echo "Jina la kwanza: $level[2]\nJina ka mwisho: $level[3]\nNamba ya simu: $phoneNumber\n\n";
            echo "1. Kubali\n2. Sitisha";
        }
    }
    //the case where user select to confirm the insertion of data
    elseif (isset($level[6]) && $level[6] != "" && $level[0]==2 && $level[1]==1 && !isset($level[7])) {
        switch ($level[6]) {
      //user agreed
      case 1:
      {
        //User Account Data Submission to the Database
                $userRegistration=$conn->prepare("INSERT INTO customer(firstName,lastName,phoneNumber,password) VALUES(?,?,?,?)");
                $hashedPassword = password_hash($level[4], PASSWORD_DEFAULT);
                $userRegistration->bind_param("ssss", $level[2], $level[3], $phoneNumber, $hashedPassword);
                $userRegistration->execute();
                if ($userRegistration) {
                    echo "Hongera umefanikiwa kujisajili kwenye mfumo, fika kwa wakala kwa ajili ya kuweka salio.";
                } else {
                    echo "Tatizo limetokea jaribu tena baadae";
                }

      }
      break;
      //user rejectbreak;

            case 2:
                echo "Umesitisha zoezi la usajili";
            break;

            default:
            echo "Umefanya uchaguzi batili tafadhali chagua 1 au 2";
    }
    }
    else{
      // echo "Menyu uloweka haipo";
      userMessage();
      exit();
    }
}

//******************************userRegistration function ENDS HERE**********************************

//******************************main function**********************************
function mainFunction()
{
    global $conn,$level,$phoneNumber;
    //rechecking if number is registered
    //selecting mobile number from database
    $sql_number = "SELECT phoneNumber FROM customer WHERE phoneNumber='$phoneNumber'";
    $query_number = mysqli_query($conn, $sql_number);
    $row_number = mysqli_fetch_array($query_number);

    if ($phoneNumber != $row_number[0]) {
        userRegistration();
    } else {
        mainServices();
    }
}

//******************************main function ENDS HERE**********************************

if (isset($level[0]) && $level[0]==2) {
    //selecting mobile number from database
    $sql_number = "SELECT phoneNumber FROM customer WHERE phoneNumber='$phoneNumber'";
    $query_number = mysqli_query($conn, $sql_number);
    $row_number = mysqli_fetch_array($query_number);

    if ($phoneNumber !=$row_number[0]) {
        userRegistration();
    } else {
        mainFunction();
    }
}

//******************************mainMenu function**********************************
function mainMenu()
{
    //prompting for main menu
    echo "KARIBU\n1.Tuma Pesa\n2.Toa Pesa\n3.Lipa Bili\n4.Akaunti yangu";
}

//******************************mainMenu function ENDS HERE************************
    /*******************************sendMoney FUNCTION********************************/
    function sendMoney()
    {
        //accessing other variables
        global $conn,$level,$phoneNumber;
        //displaying options
        if (isset($level[1]) && $level[0]==2 && isset($level[1])&& $level[1]==1 && !isset($level[2])) {
            echo "Tuma pesa:\n1.Kwa namba ya simu";
        } elseif (isset($level[2]) && $level[2] == 1 && $level[0]==2 && $level[1]==1 && !isset($level[3])) {
            switch ($level[2]) {
                case 1:
                echo "Weka namba ya simu:";
                break;

                default:
                echo "Umefanya uchaguzi batili";
                break;
            }
        }

        //prompting user to enter amount
        elseif (isset($level[3]) && $level[3] != "" && $level[2]==1 && $level[0]==2 && $level[1] ==1 && !isset($level[4])) {
            echo "Weka kiasi:";
        }

        //prompting user to put password
        elseif (isset($level[4]) && $level[4] != "" && $level[0] ==2 && $level[1] == 1 && !isset($level[5])) {
            echo "Weka namba ya siri (PIN):";
        }
        //prompting user to confirm
        elseif (isset($level[5]) && $level[5] != "" && $level[0] == 2 && $level[1] == 1 && !isset($level[6])) {
            //selecting passowrd from database
            $sql_send = "SELECT password,balance FROM customer WHERE phoneNumber='$phoneNumber'";
            $query_send = mysqli_query($conn, $sql_send);
            $row_send = mysqli_fetch_array($query_send);
            $hashedPassword = password_verify($level[5], $row_send[0]);

            if ($hashedPassword==false) {
                echo "Namba ya siri PIN si sahihi";
            }
            //checking the input amount it must be a number
            else if(preg_match("/[a-zA-Z$#'~%^]/",$level[4])){
              echo "Tafadhali jaza tarakimu pekee na si herufi.";
              exit();
            }

            //checking the input mobile number it must be a number
            else if(preg_match("/[a-zA-Z$#'~%^]/",$level[3]) || strlen($level[3])>10 ||strlen($level[3])<10 ){
              echo "Tafadhali namba ya simu uliyoweka si sahihi.";
              exit();
            }

             elseif ($level[4]>$row_send[1]) {
                echo "Salio lako halitoshi";
            } else {
                echo "Unakaribia kutuma Tsh. $level[4] kwenda namba: $level[3]\n1. Endelea\n2. Sitisha";
            }
        } elseif (isset($level[6]) && $level[6] != "" && $level[0] == 2 && $level[1] == 1 && !isset($level[7])) {
            switch ($level[6]) {
                case 1:
                    // user has agree to send money;
                    {
                        $balance = $level[4];
                        updateMoney();
                        break;
                    }
                //user decide to cancel the transaction
                case 2:
                    echo "Umesitisha mhamala";
                    break;

                    default:
                    echo "Umefanya uchaguzi batili";
                    break;
            }
        }
        else{
          userMessage();
          exit();
        }
    }
/*******************************sendMoney ENDS HERE********************************/
/*********************updateMoney FUNCTION**********************/
function updateMoney()
{
    global $conn,$phoneNumber,$level,$balance;
    //checking the balance first
    $sql_balance = "SELECT balance FROM customer WHERE phoneNumber='$phoneNumber'";
    $query_balance = mysqli_query($conn, $sql_balance);
    $row_balance = mysqli_fetch_array($query_balance);

    $newBalance = $row_balance[0]-$level[4];

    //updating new balance
    $sql_update_balance = "UPDATE customer SET balance='$newBalance' WHERE phoneNumber='$phoneNumber'";
    $query_balance_update = mysqli_query($conn, $sql_update_balance);

    if ($query_balance_update) {
        //selecting recipient balance
        $sql_recipient = "SELECT balance FROM customer WHERE phoneNumber='$level[3]'";
        $query_recipient = mysqli_query($conn, $sql_recipient);
        $row_recipient = mysqli_fetch_array($query_recipient);

        //new balance to recipient
        $newRecipientBalance = $row_recipient[0]+$level[4];

        //updating balance
        $sql_recipient_update = "UPDATE customer SET balance='$newRecipientBalance' WHERE phoneNumber='$level[3]'";
        $query_recipient_update = mysqli_query($conn, $sql_recipient_update);
        if ($query_recipient_update) {
            echo "$level[4] zimetumwa kikamilifu kwenda namba: $level[3]";
        } else {
            echo "Tatizo limetokea tafadhali jaribu tena baadae";
        }
    }
}
/*********************updateMoney FUNCTION ENDS HERE**********************/
/*********************withDraw() FUNCTION STARTS HERE**********************/
function withDraw()
{
    //calling other variables from other functions
    global $level,$conn,$phoneNumber;
    if (isset($level[1]) && $level[0]==2 && $level[1]==2 && !isset($level[2])) {
        echo "Weka namba ya wakala:";
    } elseif (isset($level[2]) && $level[1]==2 && $level[0] == 2 && !isset($level[3])) {
        echo "Weka kiasi:";
    } elseif (isset($level[3]) && $level[3] != "" && $level[1] == 2 && $level[0]==2 && !isset($level[4])) {
        echo "Weka namba ya siri (PIN):";
    } elseif (isset($level[4]) && $level[4] != "" && $level[0] == 2 && $level[1] == 2 && !isset($level[5])) {
        //checking the PIN if is correct
        $sql_withdraw = "SELECT password,balance FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_withdraw = mysqli_query($conn, $sql_withdraw);
        $row_withdraw = mysqli_fetch_array($query_withdraw);
        $hashedPassword = password_verify($level[4], $row_withdraw[0]);

        if ($hashedPassword==false) {
            echo "Namba ya siri (PIN) si sahihi";
        } elseif ($row_withdraw[1]<$level[3]) {
            echo "Salio lako halitoshi";
        } else {
            echo "Unakaribia kutoa Ths.$level[3] kwa $level[2] \n1. Kuthibitisha\n2. Kubatilisha";
        }
    } elseif (isset($level[5]) && $level[5] != "" && $level[0]==2  && $level[1] == 2 && !isset($level[6])) {
        switch ($level[5]) {
                case 1:
                {
                    //echo $level[2];
                    $sql_newBalance = "SELECT balance FROM customer WHERE phoneNumber='$phoneNumber'";
                    $query_newBalance = mysqli_query($conn, $sql_newBalance);
                    $row_newBalance = mysqli_fetch_array($query_newBalance);
                    //keeping new balance after user transaction
                    $newBalance = $row_newBalance[0]-$level[3];
                    //updating data
                    $sql_update_withDrawbalance = "UPDATE customer SET balance='$newBalance' WHERE phoneNumber='$phoneNumber'";
                    $query_update_withDrawbalance = mysqli_query($conn, $sql_update_withDrawbalance);

                    if ($query_update_withDrawbalance) {
                        echo "$level[3] imetolewa kikamilifu toka kwa $level[2].";
                    } else {
                        echo "Hitilafu imetokea tafadhali jaribu tena baadae";
                    }
                }
                break;

                case 2:
                echo "Mpendwa mteja Umesitisha mhamala";
                break;

                default:
                echo "Umefanya uchaguzi batili";
                break;
            }
    }
}
/*********************withDraw() FUNCTION ENDS HERE**********************/
/*********************payment() FUNCTION STARTS HERE**********************/
function payment()
{
    //accessing the global variables
    global $conn,$level,$phoneNumber;
    //checking if the level has set
    if (isset($level[1]) && $level[0]==2 && $level[1]==3 && !isset($level[2])) {
        echo " LIPA BILI:\n 1. Luku\n 2. DAWASCO";
    } elseif (isset($level[2]) && $level[2] !="" && $level[0]==2 && $level[1]==3 && !isset($level[3])) {
        switch ($level[2]) {
            case 1:
            echo "Weka lipa namba:";
            break;

            case 2:
            echo "Weka lipa namba::";
            break;

            default:
            echo "Uchaguzi si sahihi";
            break;
        }
    } elseif (isset($level[3]) && $level[3] != "" && $level[0]==2 && $level[1]==3 && !isset($level[4])) {
        echo "Weka kiasi:";
    } elseif (isset($level[4]) && $level[4] != "" && $level[0]==2 && $level[1]==3 && !isset($level[5])) {
        echo "Weka namba ya siri(PIN):";
    } elseif (isset($level[5]) && $level[5] != "" && $level[0]==2 && $level[1]==3 && !isset($level[6])) {
        //selecting password and deducting the balance then update
        $sql_payment = "SELECT password,balance FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_payment = mysqli_query($conn, $sql_payment);
        $row_payment = mysqli_fetch_array($query_payment);
        $hashedPassword = password_verify($level[5], $row_payment[0]);
        //confirming the user balance
        if ($hashedPassword==false) {
            echo "Namba ya siri si sahihi";
        } elseif ($row_payment[1]<$level[4]) {
            echo "Salio lako halitoshi";
        } else {
            echo "Unakaribia kulipa $level[4] kwenda $level[3]\n1. Kuthibitisha\n2. Kubatilisha";
        }
    }
    //last stage user confirmation
    elseif (isset($level[6]) && $level[6] != "" && $level[0] == 2 && $level[1]==3 && !isset($level[7])) {
        switch ($level[6]) {
            case 1:
                {
                    //updating user balance;
                    $sql_payment_balance = "SELECT balance FROM customer WHERE phoneNumber='$phoneNumber'";
                    $query_payment_balance = mysqli_query($conn, $sql_payment_balance);
                    $row_payment_balance = mysqli_fetch_array($query_payment_balance);
                    $newPaymentBalance = $row_payment_balance[0]-$level[4];
                    //updating customer data
                    $sql_update_payment_balance = "UPDATE customer SET balance ='$newPaymentBalance' WHERE phoneNumber='$phoneNumber'";
                    $query_payment_balance_update = mysqli_query($conn, $sql_update_payment_balance);

                    if ($query_payment_balance_update) {
                        echo "Imethibitishwa $level[4] imelipwa kikamilifu kwenda $level[3]";
                        exit();
                    } else {
                        echo "Tatizo limetokea tafadhali jaribu tena baadae";
                        exit();
                    }
                }
                break;
            case 2:
            {
                echo "Mhamala umesitishwa";
                exit();
            }
            break;
            default:
                echo "Umefanya uchaguzi batili";
                break;
        }
    }
}
/*********************payment() FUNCTION ENDS HERE**********************/
/*********************myAccount() FUNCTION STARTS HERE**********************/
function myAccount()
{
    //accessing the global variables
    global $conn,$level,$phoneNumber;

    if (isset($level[1]) && $level[0] == 2 && $level[1] == 4 && !isset($level[2])) {
        echo "AKAUNTI YANGU:\n 1. Angalia Salio\n 2. Badili PIN";
    }
    //if user select one of the choices
    elseif (isset($level[2]) && $level[2] == 1 && $level[1] == 4 && $level[0] == 2 && !isset($level[3])) {
        echo "Weka namba ya siri (PIN):";
    } elseif (isset($level[3]) && $level[3] != "" && $level[2] == 1 && $level[1] == 4 && $level[0] == 2 && !isset($level[4])) {
        //query to select the password and balance for users
        $sql_accountBalance = "SELECT password, balance FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_accountBalance = mysqli_query($conn, $sql_accountBalance);
        $row_accountBalance = mysqli_fetch_array($query_accountBalance);
        $hashedPassword = password_verify($level[3], $row_accountBalance[0]);

        if ($hashedPassword==false) {
            echo "Namba ya siri (PIN) si sahihi";
        } else {
            echo "Salio lako ni Tsh: $row_accountBalance[1]";
        }
    } elseif (isset($level[2]) && $level[1] == 4 && $level[0] == 2 && $level[2]==2 && !isset($level[3])) {
        echo "Weka namba ya siri (PIN) ya zamani:";
    } elseif (isset($level[3]) && $level[1] == 4 && $level[0] == 2 && $level[2] == 2 && !isset($level[4])) {
        $sql_password = "SELECT password FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_password = mysqli_query($conn, $sql_password);
        $row_password = mysqli_fetch_array($query_password);
        $hashedPassword = password_verify($level[3], $row_password[0]);
        //checking if old password is correct
        if ($hashedPassword==true) {
            echo "Weka namba ya siri (PIN) mpya:";
        } else {
            echo "Namba ya siri (PIN) si sahihi";
            exit();
        }
    } elseif (isset($level[4]) && $level[1] == 4 && $level[0] == 2 && $level[2]==2 && !isset($level[5])) {
        echo "Weka tena namba ya siri (PIN)";
    } elseif (isset($level[5]) && $level[1] == 4 && $level[0] == 2 && $level[2]==2 && !isset($level[6])) {
        if ($level[4] !=$level[5]) {
            echo "Namba ya siri si sahihi rudia tena";
            exit();
        } else {
            //updating password
            $passwordUpdate = password_hash($level[4], PASSWORD_DEFAULT);
            $sql_password_update = "UPDATE customer SET password='$passwordUpdate' WHERE phoneNumber='$phoneNumber'";
            $query_password_update = mysqli_query($conn, $sql_password_update);

            if ($query_password_update) {
                echo "Namba ya siri (PIN) imebadilishwa kikamilifu";
                exit();
            } else {
                echo "Tatizo limetokea tafadhali jaribu tena baadae";
                exit();
            }
        }
    } else {
        echo "Unknown command:";
    }
}
/*********************myAccount() FUNCTION ENDS HERE**********************/
/******************************mainServices function******************************/
function mainServices()
{
    //selecting the phone number from the database
    global $conn,$phoneNumber,$level;
    $sql_check = "SELECT phoneNumber FROM customer WHERE phoneNumber='$phoneNumber'";
    $query_check = mysqli_query($conn, $sql_check);
    $row_check = mysqli_fetch_array($query_check);

    //checking if user has select english language since it is level[0] == 1 and valid phoneNumber from database
    if (isset($level[0]) && $level[0]==2 && $phoneNumber == $row_check[0] && !isset($level[1])) {
        //echo "KARIBU\n1.Tuma Pesa\n2.Toa Pesa\n3.Lipa Bili\n4.Akaunti Yangu";
        mainMenu();
    } else {
        switch ($level[1]) {
         case 1:
         sendMoney();
         break;

         case 2:
         withDraw();
         break;

         case 3:
         payment();
         break;

         case 4:
         myAccount();
         break;

         default:
         mainMenu();
         break;
       }
    }
}
/******************************mainServices function ENDS HERE**********************************/
