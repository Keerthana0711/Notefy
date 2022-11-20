<?php
session_start();

require_once "pdo.php";


if (!isset($_SESSION['email_user'])) {
    die('Not logged in');
}
$email_temp=$_SESSION["email_user"];
$stmt = $pdo->prepare("SELECT * FROM userinfo where email = :email" );
$stmt->execute(array(":email" => $_SESSION['email_user']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user_id';
    header('Location: signup.html');
    return;
}

if (isset($_POST['phno']) && isset($_POST['university']) && isset($_POST['degree']) && isset($_POST['stream'])) {

        $sql = "UPDATE userinfo SET phno = :phno, university = :university, degree=:degree, stream=:stream WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
                ':phno' => $_POST['phno'],
                ':university' => $_POST['university'],
                ':degree' => $_POST['degree'],
                ':stream' => $_POST['stream'],
                ':email' => $_SESSION['email_user'])
        );

        
        $_SESSION['success'] = 'Record updated';
        header('Location: profile.php');
        return;
}

// Guardian: Make sure that user_id is present
if (!isset($_SESSION['email_user'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM userinfo where email = :email");
$stmt->execute(array(":email" => $_SESSION['email_user']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user_id';
    header('Location: index.php');
    return;
}

?>
<!DOCTYPE html>
<html>
    <head> 
        <title> Notefy</title>
        <style>
            body{
                margin: 0px;
                text-align: center;
            }
            table{
        background-color: white;
    }
    .editprofile{
        padding:100px 0 100px 0;
    }
        </style>
        <link rel="stylesheet" href="styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital@1&display=swap" rel="stylesheet">
    </head>
    <body>
        <script>
            function submit1(){
                var ph=document.forms["signupform"]["phno"].value;
                var uni=document.forms["signupform"]["university"].value;
                var deg=document.forms["signupform"]["degree"].value;
                var str=document.forms["signupform"]["stream"].value;
                var pass=document.forms["signupform"]["password"].value;
                var repass=document.forms["signupform"]["repassword"].value;
                
                if (ph==""){
                    alert("Please enter the phone number.");
                    return false;
                }
                if(!validatePhone(ph)){
                    alert("Phone number is not valid.");
                    return false;
                }
                if (uni==""){
                    alert("Please enter the univeristy.");
                    return false;
                }
                if (deg==""){
                    alert("Please enter the degree.");
                    return false;
                }
                if (str==""){
                    alert("Please enter the stream.");
                    return false;
                }
                if (pass==""){
                    alert("Please enter the password.");
                    return false;
                }
                if(!validatePassword(pass)){
                    alert("*Password should be of length between 6 and 8. Password should contain atleast one uppercase, one lowercase and one digit with one special character.");
                    return false;
                }
                if (repass==""){
                    alert("Please enter the password again.");
                    return false;
                }

            }
            function validatePhone(ph){
            let res =/^\d{10}$/;
            return res.test(ph); 
        }
            function validatePassword(pass){
                let res=/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
                return res.test(pass);
            }   
        </script>
    <div>
        <!-- header section starts  -->
<!-- <div class="dashboard"> -->
<section>
<div class="nav">

   <!--<a href="homepage.html" class="logo">travel.</a>-->

   <nav class="navBar">
      <a href="dashboard.php" class="nav-item">Dashboard</a>
      <a href="profile.php" class="nav-item">Profile Page</a>
      <a href="search.php" class="nav-item">Search</a>
      <a href="upload.html" class="nav-item">Upload</a>
      <a href="feedbacks.php" class="nav-item">Feedback</a>
      <a href="logout.php" class="nav-item">Log-out</a>
   </nav>

</section>

        <div class="signup"  align="center">
            <h2> Edit Profile Info</h2>
            <form align="center"  name="signupform" onsubmit="return submit1()" method="post"  border="2">
                <table align="center">
                    <tr>
                        <td> Phone number </td>
                        <td> <input type="text" name="phno"  value="<?php echo $row['phno']?> "> </td>
                    </tr>
                    <tr>
                        <td> University</td>
                        <td> <input type="text" name="university" value="<?php echo $row['university']?>"> </td>
                    </tr>
                    <tr>
                        <td> Degree </td>
                        <td> <input type="text" name="degree"  value="<?php echo $row['degree']?>"> </td>
                    </tr>
                    <tr>
                        <td> Stream/ Branch </td>
                        <td> <input type="text" name="stream"  value="<?php echo $row['stream']?>"> </td>
                    </tr>
                    <tr>
                        <td> Year </td>
                        <td> 
                            <select id="year" name="year" value="<?php echo $row['year']?>">
                                <option value="1"> 1 </option>
                                <option value="2"> 2 </option>
                                <option value="3"> 3 </option>
                                <option value="4"> 4 </option>
                                <option value="5"> 5 </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Password <br>
                        <small>*Password should be of length between 6 and 8. Password should contain atleast one uppercase, one lowercase and one digit with one special character.</small> </td>
                        <td> <input type="password" name="password" value="<?php echo $row['password']?>"> </td>
                    </tr>
                    <tr>
                        <td> Confirm password</td>
                        <td> <input type="password" name="repassword" value="<?php echo $row['confirmpassword']?>"> </td>
                    </tr>
                </table>
                <div class="btn">
                    <button type="submit" id="signup_btn" name="signup_btn"> Update</button>
                </div>
            </form>
        </div>

<!-- footer section starts  -->

        <footer class="contact" id="contact">
            <div class="brand-details">
                <h3 class="brand-name">Notefy.</h3>
                <p class="detail">We aim at giving notes for all from everywhere.</p>
            </div>
            <div class="contact-details">
                <h3 class="contact-title">Contact</h3>
                <p><i class="fa fa-address-card"></i>: Chennai, Tamil Nadu, India</p>
                <p><i class="fa fa-phone"></i>: +91-9955220015</p>
                <p><i class="fa fa-phone"></i>: +91-8945740254</p>
                <p><i class="fa fa-envelope"></i>: notefygmail.com</p>
            </div>
            <div class="link-details">
                <h3 class="link-title">Links</h3>
                <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile Page</a></li>
            <li><a href="search.php">Search</a></li>
            <li><a href="upload.html">Upload</a></li>
            <li><a href="feedbacks.php">Feedback</a></li>
        </ul>
            </div>
        </footer>

<!-- footer section ends -->

        </div>
    </div>
</body>
</html>

        