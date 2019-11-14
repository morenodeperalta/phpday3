<?php
ob_start();
session_start(); // start a new session or continues the previous
if( isset($_SESSION['user'])!="" ){
 header("Location: home.php" ); // redirects to home.php
}
include_once 'dbconnect.php';
// in this MEGA IF STATEMENT you can put all the keys in order to make the page to comunicate the commands. 
 
if ( isset($_POST['btn-signup']) ) {
 $error = false;

 // sanitize user input to prevent sql injection
 $name = trim($_POST['name']);
  //trim - strips whitespace (or other characters) from the beginning and end of a string
  $name = strip_tags($name);
  // strip_tags — strips HTML and PHP tags from a string
  $name = htmlspecialchars($name);
 // htmlspecialchars converts special characters to HTML entities
 $email = trim($_POST[ 'email']);
 $email = strip_tags($email);
 $email = htmlspecialchars($email);

 $pass = trim($_POST['pass']);
 $pass = strip_tags($pass);
 $pass = htmlspecialchars($pass);


// Now im creating and extra input for the SIGN UP
$dateOfBirth = trim($_POST['dateOfBirth']);
 $dateOfBirth = strip_tags($dateOfBirth);
 $dateOfBirth = htmlspecialchars($dateOfBirth);

 $phonenumber = trim($_POST['phonenumber']);
 $phonenumber = strip_tags($phonenumber);
 $phonenumber = htmlspecialchars($phonenumber);

  // basic name validation
 if (empty($name)) {
  $error = true ;
  $nameError = "Please enter your full name.";
 } else if (strlen($name) < 3) {
  $error = true;
  $nameError = "Name must have at least 3 characters.";
 } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
  $error = true ;
  $nameError = "Name must contain alphabets and space.";
 }

 //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
  $error = true;
  $emailError = "Please enter valid email address." ;
 } else {
  // checks whether the email exists or not
  $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if($count!=0){
   $error = true;
   $emailError = "Provided Email is already in use.";
  }
 }
 // password validation
  if (empty($pass)){
  $error = true;
  $passError = "Please enter password.";
 } elseif(strlen($pass) < 6) {
  $error = true;
  $passError = "Password must have atleast 6 characters." ;
 }


// Date of birth Validator


// phone numer validator 
  if (empty($phonenumber)){
  $error = true;
  $phonenumberError = "Please enter phonenumber.";
 } else if(strlen($phonenumber) < 3) {
  $error = true;
  $phonenumberError = "Phone number must have atleast 3 characters." ;
 }


 // password hashing for security
$password = hash('sha256' , $pass);

 // if there's no error, continue to signup
 if( !$error ) {
  
  echo $query = "INSERT INTO users(userName,userEmail,userPass,dateOfBirth, phonenumber) VALUES('$name','$email','$password', '$dateOfBirth', $phonenumber)";
  $res = mysqli_query($conn, $query);
  
  if ($res) {
   $errTyp = "success";
   $errMSG = "Successfully registered, you may login now";
   unset($name);
  unset($email);
   unset($pass);
   unset($dateOfBirth);
   unset($phonenumber);
  } else  {
   $errTyp = "danger";
   $errMSG = "Something went wrong, try again later..." ;
  }
  
 }


}
?>
<!DOCTYPE html> 
<html>
<head>
<title>Login & Registration System</title>
<link rel="stylesheet"  href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"  crossorigin="anonymous">
</head>
<body>
   <form method="post"  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  autocomplete="off" >
  
      
            <h2>Sign Up.</h2>
            <hr />
          
            <?php
   if ( isset($errMSG) ) {
  
   ?> 
           <div  class="alert alert-<?php echo $errTyp ?>" >
                         <?php echo  $errMSG; ?>
       </div>

<?php 
  }
  ?> 
          
      
          

            <input type ="text"  name="name"  class ="form-control"  placeholder ="Enter Name"  maxlength ="50"   value = "<?php echo $name ?>"  />
      
               <span   class = "text-danger" > <?php   echo  $nameError; ?> </span >
          
    

            <input   type = "email"   name = "email"   class = "form-control"   placeholder = "Enter Your Email"   maxlength = "40"   value = "<?php echo $email ?>"  />
    
               <span   class = "text-danger" > <?php   echo  $emailError; ?> </span >
      
          
      
            
        
            <input   type = "password"   name = "pass"   class = "form-control"   placeholder = "Enter Password"   maxlength = "15" value="<?= $pass ?>" />
            
               <span   class = "text-danger" > <?php   echo  $passError; ?> </span >


            <input type ="date"  name="dateOfBirth"  class ="form-control"  placeholder ="Enter your Birth date"  maxlength ="50"   value = "<?php echo $dateOfBirth ?>" />
      
               <span   class = "text-danger" > <?php   echo  $dateOfBirthError; ?> </span >

            <input type ="number"  name="phonenumber"  class ="form-control"  placeholder ="Enter your phone number"  maxlength ="50"   value = "<?php echo $phonenumber ?>"  />
      
               <span   class = "text-danger" > <?php   echo  $phonenumberError; ?> </span >
      
            <hr />

          
            <button   type = "submit"   class = "btn btn-block btn-primary"   name = "btn-signup" >Sign Up</button >
            <hr  />
          
            <a   href = "index.php" >Sign in Here...</a>
    
  
   </form >
</body >
</html >
<?php  ob_end_flush(); ?>