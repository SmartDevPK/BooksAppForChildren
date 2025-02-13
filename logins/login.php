<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BOOK APP FOR CHILDREN</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
      <h1>BOOK APP FOR CHILDREN LOGIN PAGE</h1>
    </div>

   <form method="post" action="../login.server.php">
       <div class="input-group">  
           <label>EMAIL</label>
           <input type="text" name="email" placeholder="email">
        </div>

       <div class="input-group">
          <label>PASSWORD</label>
          <input type="password" name="password" placeholder="password">
       </div>

       <div class="input-group">
          <button type="submit" class="btn" name="login_user">Login</button>
        </div>

        <p>
              Don't have an account? <a href="register.php">Sign up</a>
        </p>

        <p>
              <a href="forget_password.php">Forgot Password?</a>
        </p>
    </form>
</body>
</html>