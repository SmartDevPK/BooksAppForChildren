<?php include('server.php'); ?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BOOK APP FOR CHILDREN</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
      <h1>BOOK APP FOR CHILDREN REGISTRATION PAGE</h1>
    </div>

    
    <form method="post" action="register.php">
        <div class="input-group">
            <label >NAME</label>
            <input type="text" name="name" placeholder="name">
        </div>

        <div class="input-group">
           <label "">EMAIL</label>
           <input type="text" name="email" placeholder="email">
        </div>

        <div class="input-group">
           <label>PHONE NUMBER</label>
           <input type="number" name="phone_number" placeholder="phone_number">
        </div>

        <div class="input-group">
          <label >SELECT COUNTRY:</label>
          <select id="country" name="country">
              <option value="">--Select a country--</option>
              <option value="AF">Afghanistan</option>
              <option value="AL">Albania</option>
              <option value="DZ">Algeria</option>
              <option value="AD">Andorra</option>
              <option value="AO">Angola</option>
              <option value="AR">Argentina</option>
              <option value="AM">Armenia</option>
              <option value="AU">Australia</option>
              <option value="AT">Austria</option>
              <option value="AZ">Azerbaijan</option>
              <option value="BS">Bahamas</option>
              <option value="BH">Bahrain</option>
              <option value="BD">Bangladesh</option>
              <option value="BB">Barbados</option>
              <option value="BY">Belarus</option>
              <option value="BE">Belgium</option>
              <option value="BZ">Belize</option>
              <option value="BJ">Benin</option>
              <option value="BT">Bhutan</option>
              <option value="BO">Bolivia</option>
              <option value="BA">Bosnia and Herzegovina</option>
              <option value="BW">Botswana</option>
              <option value="BR">Brazil</option>
              <option value="BN">Brunei</option>
              <option value="BG">Bulgaria</option>
              <option value="BF">Burkina Faso</option>
              <option value="BI">Burundi</option>
              <option value="KH">Cambodia</option>
              <option value="CM">Cameroon</option>
              <option value="CA">Canada</option>
              <option value="CF">Central African Republic</option>
              <option value="TD">Chad</option>
              <option value="CL">Chile</option>
              <option value="CN">China</option>
              <option value="CO">Colombia</option>
              <option value="CG">Congo</option>
              <option value="CR">Costa Rica</option>
              <option value="HR">Croatia</option>
              <option value="CU">Cuba</option>
              <option value="CY">Cyprus</option>
              <option value="CZ">Czech Republic</option>
              <option value="DK">Denmark</option>
              <option value="DJ">Djibouti</option>
              <option value="EG">Egypt</option>
              <option value="FR">France</option>
              <option value="DE">Germany</option>
              <option value="GH">Ghana</option>
              <option value="GR">Greece</option>
              <option value="IN">India</option>
              <option value="ID">Indonesia</option>
              <option value="IR">Iran</option>
              <option value="IQ">Iraq</option>
              <option value="IE">Ireland</option>
              <option value="IT">Italy</option>
              <option value="JP">Japan</option>
              <option value="KE">Kenya</option>
              <option value="KR">South Korea</option>
              <option value="NG   ">Nigeria</option>
              <option value="RU">Russia</option>
              <option value="SA">Saudi Arabia</option>
              <option value="ZA">South Africa</option>
              <option value="ES">Spain</option>
              <option value="SE">Sweden</option>
              <option value="CH">Switzerland</option>
              <option value="TR">Turkey</option>
              <option value="UA">Ukraine</option>
              <option value="AE">United Arab Emirates</option>
              <option value="GB">United Kingdom</option>
              <option value="US">United States</option>
              <option value="VN">Vietnam</option>
              <option value="ZW">Zimbabwe</option>
         </select>
       </div>

       <div class="input-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="password">
        </div>

       <div class="input-group">
          <button type="submit" class="btn" name="reg_user">Register</button>
        </div>

        <p>
                Already a member? <a href="login.php">Sign in</a>
        </p>

    </form>
</body>
</html>