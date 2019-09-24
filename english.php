<?php
//including the connection
include_once 'connection.php';
//prompt for user registration

/******messageFunction incase user input invalid menu*/
function userMessage(){
  echo "Please dial the proper menu.";
  exit();
}
/*******userMessage ENDS HERE*/
//******************************userRegistration function**********************************
function userRegistration()
{
    global $level,$conn,$phoneNumber;
    if (isset($level[0])&& $level[0]==1 && !isset($level[1])) {
        echo "Hello Welcome Press 1 to Register\n1.Register";
    }
    //user input firstname
    elseif (isset($level[1]) && $level[1] != "" && $level[0]==1 && !isset($level[2])) {
        switch ($level[1]) {
      case 1:
      echo "Enter your firstname:";
      break;

      default:
      echo "Invalid input.";
      break;
    }
    }
    //user input lastname (user must ensure the first case level 1 is 1) otherwise the selection is invalid
    elseif (isset($level[2]) && $level[2] != "" && $level[0]==1 && $level[1]==1 && !isset($level[3])) {
        echo "Enter lastname:";
    }

    //user input first passowrd
    elseif (isset($level[3]) && $level[3] != "" && $level[0]==1 && $level[1]==1 && !isset($level[4])) {
        echo "Enter four digit PIN:";
    }

    //user input confirm passowrd
    elseif (isset($level[4]) && $level[4] != "" && $level[0]==1 && $level[1]==1 && !isset($level[5])) {
        echo "Re-enter four digit PIN:";
    } elseif (isset($level[5]) && $level[5] != "" && $level[0]==1 && $level[1]==1 && !isset($level[6])) {
        //comparing the passwords and ensuring user input digits only which are > 3 & <=4
        if ($level[4] != $level[5]) {
            echo "Password Do not Match.";
            exit();
        }
        //checking the length
        else if(preg_match("/[a-zA-Z%'~]/",$level[4])){
          echo "Invalid input only Numbers are required.";
          exit();
        }

        //if the inputed PIN is less than 4 digits or greater the four digit
        else if(strlen($level[4])>4){
          echo "The PIN is too long, required is four digit PIN.";
          exit();
        }

        //if the inputed PIN is less than 4 digits
        else if(strlen($level[4])<4){
          echo "The PIN is too short, required is four digit PIN.";
          exit();
        }

        //passwords match
        else {
            global $phoneNumber;
            echo "Details Confirmation:\n\n";
            echo "Firstname: $level[2]\nLastname: $level[3]\nPhone Number: $phoneNumber\n\n";
            echo "1. Confirm\n2. Cancel";
        }
    }
    //the case where user select to confirm the insertion of data
    elseif (isset($level[6]) && $level[6] != "" && $level[0]==1 && $level[1]==1 && !isset($level[7])) {
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
                    echo "The account has been created successfully, Visit to any agent to deposit your balance";
                } else {
                    echo "An error has occured during account registration";
                    exit();
                }
      }
      break;
      //user rejectbreak;

            case 2:
                echo "You have cancelled the registration";
            break;

            default:
            echo "Invalid Input Select either 1 or 2";
            break;
    }
    }
    //invalid inputs
    else{
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

    if ($phoneNumber !=$row_number[0]) {
        userRegistration();
    } else {
        mainServices();
    }
}

//******************************main function ENDS HERE**********************************

if (isset($level[0]) && $level[0]==1) {
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
    echo "WELCOME\n1.Send money\n2.Withdraw\n3.Payment\n4.My account";
}
//******************************mainMenu function ENDS HERE************************
/*******************************sendMoney FUNCTION********************************/
    function sendMoney()
    {
        //accessing other variables
        global $conn,$level,$phoneNumber;
        //displaying options
        if (isset($level[1]) && $level[0]==1 && $level[1]==1 && !isset($level[2])) {
            echo "Send Money to:\n1.Mobile number";
        } elseif (isset($level[2]) && $level[2] == 1 && $level[0]==1 && $level[1]==1 && !isset($level[3])) {
            switch ($level[2]) {
                case 1:
                echo "Enter mobile number:";
                break;

                default:
                echo "Invalid Input";
                break;
            }
        }

        //prompting user to enter amount
        elseif (isset($level[3]) && $level[3] != "" && $level[2] == 1 && $level[0]==1 && $level[1] ==1 && !isset($level[4])) {
            echo "Enter amount:";
        }

        //prompting user to put password
        elseif (isset($level[4]) && $level[4] != "" && $level[0] ==1 && $level[1] == 1 && !isset($level[5])) {
            echo "Enter your PIN:";
        }
        //prompting user to confirm
        elseif (isset($level[5]) && $level[5] != "" && $level[0] == 1 && $level[2] == 1 && $level[1] == 1 && !isset($level[6])) {
            //selecting passowrd from database
            $sql_send = "SELECT password,balance FROM customer WHERE phoneNumber='$phoneNumber'";
            $query_send = mysqli_query($conn, $sql_send);
            $row_send = mysqli_fetch_array($query_send);
            $hashedPassword = password_verify($level[5], $row_send[0]);

            if ($hashedPassword==false) {
                echo "Invalid PIN";
            }
            //checking the input amount it must be a number
            else if(preg_match("/[a-zA-Z$#'~%^]/",$level[4])){
              echo "Invalid amount format, only numbers are accepted.";
              exit();
            }

            //checking the input mobile number it must be a number
            else if(preg_match("/[a-zA-Z$#'~%^]/",$level[3]) || strlen($level[3])>10 ||strlen($level[3])<10 ){
              echo "The provided mobile number is invalid.";
              exit();
            }

            elseif ($level[4]>$row_send[1]) {
                echo "Insufficient Balance";
            } else {
                echo "You are about to send $level[4] to mobile number: $level[3]\n1. Proceed\n2. Cancel";
            }
        } elseif (isset($level[6]) && $level[6] != "" && $level[0] == 1 && $level[2]==1 && $level[1] == 1 && !isset($level[7])) {
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
                    echo "The transaction has been cancelled";
                    break;

                    default:
                    echo "Invalid Input";
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
            echo "$level[4] has been successfully sent to mobile number: $level[3]";
        } else {
            echo "System out of service";
        }
    }
}
/*********************updateMoney FUNCTION ENDS HERE**********************/
/*********************withDraw() FUNCTION STARTS HERE**********************/
function withDraw()
{
    //calling other variables from other functions
    global $level,$conn,$phoneNumber;
    if (isset($level[1]) && $level[0]==1 && $level[1]==2 && !isset($level[2])) {
        echo "Enter Agent Number:";
    } elseif (isset($level[2]) && $level[1]==2 && $level[0] == 1 && !isset($level[3])) {
        echo "Enter Amount:";
    } elseif (isset($level[3]) && $level[3] != "" && $level[1] == 2 && $level[0]==1 && !isset($level[4])) {
        echo "Enter PIN:";
    } elseif (isset($level[4]) && $level[4] != "" && $level[0] == 1 && $level[1] == 2 && !isset($level[5])) {
        //checking the PIN if is correct
        $sql_withdraw = "SELECT password,balance FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_withdraw = mysqli_query($conn, $sql_withdraw);
        $row_withdraw = mysqli_fetch_array($query_withdraw);
        $hashedPassword = password_verify($level[4], $row_withdraw[0]);

        if ($hashedPassword==false) {
            echo "Invalid PIN";
        }
        //checking the input amount it must be a number
        else if(preg_match("/[a-zA-Z$#'~%^]/",$level[3])){
          echo "Invalid amount format, only numbers are accepted.";
          exit();
        }

        elseif ($row_withdraw[1]<$level[3]) {
            echo "Insufficient Balance";
        }

         else {
            echo "You are about to withdraw $level[3] from $level[2] \n1. Proceed\n2. Cancel";
        }
    } elseif (isset($level[5]) && ($level[5] == 1 || $level[5]==2) && $level[0]==1  && $level[1] == 2 && !isset($level[6]) && !isset($level[7])) {
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
                        echo "$level[3] has been withdrawn from $level[2] successfully.";
                    } else {
                        echo "System out of service";
                    }
                }
                break;

                case 2:
                echo "You have cancelled the transaction";
                break;

                default:
                echo "Invalid Input";
                break;
            }
    }
    //invalid input from user
    else{
      userMessage();
      exit();
    }
}
/*********************withDraw() FUNCTION ENDS HERE**********************/
/*********************payment() FUNCTION STARTS HERE**********************/
function payment()
{
    //accessing the global variables
    global $conn,$level,$phoneNumber;
    //checking if the level has set
    if (isset($level[1]) && $level[0]==1 && $level[1]==3 && !isset($level[2])) {
        echo " PAYMENT FOR:\n 1. Luku\n 2. DAWASCO";
    }
      //user input is either 1 or 2 (incase if added other functionality modify here)
     elseif (isset($level[2]) && ($level[2] == 1 || $level[2]==2) && $level[0]==1 && $level[1]==3 && !isset($level[3])) {
        switch ($level[2]) {
            case 1:
            echo "Enter Reference Number:";
            break;

            case 2:
            echo "Enter Reference Number:";
            break;

            default:
            echo "Invalid Selection";
            break;
        }
    } elseif (isset($level[3]) && $level[3] != "" && $level[0]==1 && $level[1]==3 && !isset($level[4])) {
        echo "Enter amount:";
    } elseif (isset($level[4]) && $level[4] != "" && $level[0]==1 && $level[1]==3 && !isset($level[5])) {
        echo "Enter PIN:";
    } elseif (isset($level[5]) && $level[5] != "" && $level[0]==1 && $level[1]==3 && !isset($level[6])) {
        //selecting password and deducting the balance then update
        $sql_payment = "SELECT password,balance FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_payment = mysqli_query($conn, $sql_payment);
        $row_payment = mysqli_fetch_array($query_payment);
        $hashedPassword = password_verify($level[5], $row_payment[0]);
        //confirming the user balance
        if ($hashedPassword==false) {
            echo "Invalid PIN";
        }
        //checking the input amount it must be a number
        else if(preg_match("/[a-zA-Z$#'~%^]/",$level[4])){
          echo "Invalid amount format, only numbers are accepted.";
          exit();
        }
        elseif ($row_payment[1]<$level[4]) {
            echo "Insufficient Balance";
        } else {
            echo "You are about to Pay $level[4] to $level[3]\n1. Proceed\n2. Cancel";
        }
    }
    //last stage user confirmation
    elseif (isset($level[6]) && ($level[6] == 1 || $level[6] == 2) && $level[0] == 1 && $level[1]==3 && !isset($level[7])) {
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
                        echo "$level[4] has been paid successfully to $level[3]";
                        exit();
                    } else {
                        echo "An error occured";
                        exit();
                    }
                }
                break;
            case 2:
            {
                echo "You have choose to Cancel the transaction";
                exit();
            }
            break;
            default:
                echo "Invalid input";
                break;
        }
    }

    else{
      userMessage();
      exit();
    }
}
/*********************payment() FUNCTION ENDS HERE**********************/
/*********************myAccount() FUNCTION STARTS HERE**********************/
function myAccount()
{
    //accessing the global variables
    global $conn,$level,$phoneNumber;

    if (isset($level[1]) && $level[0] == 1 && $level[1] == 4 && !isset($level[2])) {
        echo "MY ACCOUNT\n 1. Check Balance\n 2. Change PIN";
    }
    //if user select one of the choices
    elseif (isset($level[2]) && $level[2] == 1 && $level[1] == 4 && $level[0] == 1 && !isset($level[3])) {
        echo "Enter PIN:";
    } elseif (isset($level[3]) && $level[3] != "" && $level[2] == 1 && $level[1] == 4 && $level[0] == 1 && !isset($level[4])) {
        //query to select the password and balance for users
        $sql_accountBalance = "SELECT password, balance FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_accountBalance = mysqli_query($conn, $sql_accountBalance);
        $row_accountBalance = mysqli_fetch_array($query_accountBalance);
        $hashedPassword = password_verify($level[3], $row_accountBalance[0]);

        if ($hashedPassword==false) {
            echo "Invalid PIN";
        } else {
            echo "Your balance is: $row_accountBalance[1]";
        }
    } elseif (isset($level[2]) && $level[1] == 4 && $level[0] == 1 && $level[2]==2 && !isset($level[3])) {
        echo "Enter Old PIN:";
    } elseif (isset($level[3]) && $level[1] == 4 && $level[0] == 1 && $level[2] == 2 && !isset($level[4])) {
        $sql_password = "SELECT password FROM customer WHERE phoneNumber='$phoneNumber'";
        $query_password = mysqli_query($conn, $sql_password);
        $row_password = mysqli_fetch_array($query_password);
        $hashedPassword = password_verify($level[3], $row_password[0]);
        //checking if old password is correct
        if ($hashedPassword==true) {
            echo "Enter new PIN:";
        } else {
            echo "Invalid PIN";
            exit();
        }
    } elseif (isset($level[4]) && $level[1] == 4 && $level[0] == 1 && $level[2]==2 && !isset($level[5])) {
        echo "Re-enter New PIN";
    } elseif (isset($level[5]) && $level[1] == 4 && $level[0] == 1 && $level[2]==2 && !isset($level[6])) {
        if ($level[4] !=$level[5]) {
            echo "Error PIN do not match";
            exit();
        } else {
            //updating password
            $passwordUpdate = password_hash($level[4], PASSWORD_DEFAULT);
            $sql_password_update = "UPDATE customer SET password='$passwordUpdate' WHERE phoneNumber='$phoneNumber'";
            $query_password_update = mysqli_query($conn, $sql_password_update);

            if ($query_password_update) {
                echo "PIN changed successfully";
                exit();
            } else {
                echo "Unable to change PIN";
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
    if (isset($level[0]) && $level[0]==1 && $phoneNumber == $row_check[0] && !isset($level[1])) {
        //echo "WELCOME\n1.Send money\n2.Withdraw\n3.Payment\n4.My account";
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
