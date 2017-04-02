<?php
function phoneError($phone) {
    echo '<div class="alert alert-danger">
            <strong>Klaida: Telefono Laukelis</strong> Toks Telefonas jau užregistruotas: ' . $phone . '
          </div>';
}
function emailError($email) {
    echo '<div class="alert alert-danger">
            <strong>Klaida: El. Pašto Laukelis</strong> Toks El. Paštas jau užregistruotas: ' . $email . '
          </div>';
}

function registrationSuccess() {
    echo '<div class="alert alert-success">
            <strong>Registracija Sėkminga!</strong> <a href="index.php">Grižti į pagrindinį puslapį.</a>
          </div>';
}

function fileMissingError() {
    echo '<div class="alert alert-danger">
            <strong>Klaida: Failo Laukelis</strong> Pamiršote prisegti failą.
          </div>';
}

function fileExtensionError() {
    echo '<div class="alert alert-danger">
            <strong>Klaida: Failo Laukelis</strong> Galite prisegti tik .PNG ar .JPG formato failą.
          </div>';
}
?>