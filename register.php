<?php

include 'inc/header.php';
include 'inc/connection.php';
include 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Form POST variables
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  
    // Check Email and Phone If Exists in DB
    try {
        $stmt = $db->prepare('SELECT * FROM users WHERE phone = :phone OR email = :email');
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
    $checkPhoneEmail = $stmt->fetch(PDO::FETCH_ASSOC);
    // Get Format of the file
    $imageFileType = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
    
    if ($phone == $checkPhoneEmail['phone'] && $email == $checkPhoneEmail['email']) {
        phoneError($phone);
        emailError($email);
    } else if ($phone == $checkPhoneEmail['phone']) {
        phoneError($phone);
    } else if ($email == $checkPhoneEmail['email']) {
        emailError($email);
    } else if (empty($_FILES["fileToUpload"]["name"])) {
        fileMissingError();
    } else if ($imageFileType != "jpg" && $imageFileType != "png") {
        fileExtensionError();
    } else {

        try {
            // id field is auto increment don't need to include it
            $stmt = $db->prepare('INSERT INTO users (name, last_name, phone, email) 
                                             VALUES (:name, :lastname, :phone, :email)');
            //$stmt->bindParam(':id', NULL);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':lastname', $lastName);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        // I need I id for creating seperate folder for user and naming that forlder as id
        try {
          
            $stmt2 = $db->prepare('SELECT id FROM users WHERE email = :email');
            $stmt2->bindParam(':email', $email);
            $stmt2->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
      
        $fetchUser = $stmt2->fetch(PDO::FETCH_ASSOC);
        // Creating new dir for every user in case of duplicate files name. And it's easier to track what images user has
        mkdir('user-pictures/' . $fetchUser["id"]);
        // Directory where user image will stay
        $uploadDir = 'user-pictures/' . $fetchUser["id"] . '/' . basename($_FILES["fileToUpload"]["name"]);
        // Move image to desired directory
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $uploadDir);
        
        try {
          
            $stmt3 = $db->prepare('UPDATE users SET file_path = :filepath WHERE id = :id');
            $stmt3->bindParam(':filepath', $uploadDir);
            $stmt3->bindParam(':id', $fetchUser["id"]);
            $stmt3->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        header('location: thanks.php');
    }
}

?>
<form method="post" action="register.php" id="val" enctype="multipart/form-data">
  
  <div class="form-group row">
    <label for="name" class="col-2 col-form-label">Vardas</label>
    <div class="col-10">
      <input class="form-control" type="text" placeholder="jūsų vardas" id="name" name="name" value="<?php if (isset($name)) { echo $name; } ?>">
    </div>
  </div>

  <div class="form-group row">
    <label for="lastname" class="col-2 col-form-label">Pavardė</label>
    <div class="col-10">
      <input class="form-control" type="text" placeholder="jūsų pavardė" id="lastname" name="lastname" value="<?php if (isset($lastName)) { echo $lastName; } ?>">
    </div>
  </div>

  <div class="form-group row">
    <label for="phone" class="col-2 col-form-label">Telefonas</label>
    <div class="col-10">
      <input class="form-control" type="text" placeholder="pvz: 86..." id="phone" name="phone" value="<?php if (isset($phone)) { echo $phone; } ?>">
    </div>
  </div>

  <div class="form-group row">
    <label for="email" class="col-2 col-form-label">El. Paštas</label>
    <div class="col-10">
      <input class="form-control" type="text" placeholder="pvz: pastas@gmail.com" id="email" name="email" value="<?php if (isset($email)) { echo $email; } ?>">
    </div>
  </div>

  <div class="form-group row">
    <label for="file" class="col-2 col-form-label">Prisekite Failą</label>
    <div class="col-10">
      <input type="file" class="form-control-file" id="file" name="fileToUpload" aria-describedby="fileHelp">
      <small id="fileHelp" class="form-text text-muted">Tinkami formatai: .png .jpg</small>
    </div>
  </div>
  
  <div class="form-group row">
    <div class="col-5"></div>
      <input type="submit" class="btn btn-primary col-2" value="Siųsti">
    <div class="col-5"></div>
  </div>
  
</form>
<?php include 'inc/footer.php'; ?>