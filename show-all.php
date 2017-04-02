<?php 
include 'inc/header.php'; 
include 'inc/connection.php'; 

try {
        $stmt = $db->prepare('SELECT * FROM users');
        $stmt->execute();
        //print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo $e->getMessage();
    }

$people = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<table class="table">
  <thead>
    <tr>
      <th>Nuotrauka</th>
      <th>Vardas</th>
      <th>Pavardė</th>
      <th>Telefonas</th>
      <th>El. Paštas</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($people as $p): ?>
    <tr>
      <th><img src="<?php echo $p['file_path']; ?>" height="125" width="125"></th>
      <td><?php echo $p['name']; ?></td>
      <td><?php echo $p['last_name']; ?></td>
      <td><?php echo $p['phone']; ?></td>
      <td><?php echo $p['email']; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'inc/footer.php'; ?>