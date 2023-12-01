<?php
  require('../src/utils/connection.php');
  if ($error == null) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda Virtual</title>
  <link rel="stylesheet" href="../src/utils/style.css">
</head>
<body>
<?php
  $stmt = $link->stmt_init();

  $stmt->prepare('SELECT * FROM products ORDER BY name ASC');
  $stmt->execute();

  $result = $stmt->get_result();
                                              
  if ($result->num_rows > 0) {                            
?>
        <table>
            <thead>
                <tr>
                    <th>name</th>
                    <th>price</th>
                    <th>amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php            
                  while ($product = $result->fetch_array()) {
                ?>
                  <tr>
                      <td><?=$product['name']?></td>
                      <td><?=$product['price']?>€</td>
                      <td><?=$product['amount']?></td>
                      <td class="actions">                            
                          <a class="button" href="index.php?product=<?=$product['id']?>">
                              <button>+</button>
                          </a>               
                      </td>
                  </tr>
                <?php
                }
                  $result->free();
                ?>
            </tbody>
        </table>
<?php
  }                                       
  $stmt->close();
?>
</body>
</html>
<?php
  }
  $link->close();
?>