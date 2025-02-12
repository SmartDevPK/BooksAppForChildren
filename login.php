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
      <h1>BOOK APP FOR CHILDREN LOGIN PAGE</h1>
    </div>

   <form method="post" action="login.php">
       
       <div class="input-group">
           <label "">EMAIL</label>
           <input type="text" name="email" placeholder="email">
        </div>

       <div class="input-group">
          <label >PASSWORD</label>
          <input type="number" name="password" value="password">
       </div>

       <div class="input-group">
          <button type="submit" class="btn" name="reg_user">Login</button>
        </div>

        <p>
              Don't have an account? <a href="register.php">Sign up</a>
       </p> 

    </form>
</body>
</html>