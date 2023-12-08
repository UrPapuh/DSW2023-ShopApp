<?php
  session_start();
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
<header>
    <nav>
      <a href="index.php"><- Volver</a>
    </nav>
    <h1>Carrito</h1>
  </header>
<?php
    //Clear Out
    if (isset($_SESSION['basket']) && isset($_GET['action']) && $_GET['action'] == 'clear') unset($_SESSION['basket']);
    if (isset($_SESSION['basket']) && $_SESSION['basket'] != null) {
      if (isset($_GET['action']) && isset($_GET['product']) && isset($_SESSION['basket'][$_GET['product']])) {
        // Decrease
        if ($_GET['action'] == 'decrease') {
          if ($_SESSION['basket'][$_GET['product']] > 1) {
            $_SESSION['basket'][$_GET['product']]--;
          } else {
            unset($_SESSION['basket'][$_GET['product']]);
          }
        } else if ($_GET['action'] == 'increase') {
          $stmt = $link->stmt_init();
          $stmt->prepare('SELECT * FROM products WHERE id=?');
          $stmt->bind_param('i', $_GET['product']);
          $stmt->execute();

          $result = $stmt->get_result();
          if ($result->num_rows > 0) {
            $product = $result->fetch_array();
            if ($_SESSION['basket'][$_GET['product']] < $product['amount']) $_SESSION['basket'][$_GET['product']]++;
            $result->free();
          }
          $stmt->close();
        }
      }          
?>
        <table>
            <thead>
                <tr>
                    <th>name</th>
                    <th>price</th>
                    <th>amount</th>
                    <th>subtotal</th>
                    <th class="actions">                            
                      <a class="button" href="basket.php?action=clear">
                          <button>&#x1F5D1;</button>
                      </a>               
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                  $stmt = $link->stmt_init();

                  $stmt->prepare('SELECT * FROM products WHERE id=?');

                  $total = 0;

                  foreach ($_SESSION['basket'] as $id => $amount) {
                    $stmt->bind_param('i', $id);
                    $stmt->execute();

                    $result = $stmt->get_result();
                                   
                    if ($result->num_rows > 0) {   
                      $product = $result->fetch_array();
                      $subTotal = $product['price'] * $amount;
                      $total += $subTotal;
                ?>
                      <tr>
                          <td><?=$product['name']?></td>
                          <td><?=$product['price']?> €</td>
                          <td><?=$amount?></td>
                          <td><?=$subTotal?> €</td>
                          <td class="actions">                            
                              <a class="button" href="basket.php?action=decrease&&product=<?=$id?>">
                                  <button>-</button>
                              </a>               
                          </td>
                          <td class="actions">                            
                              <a class="button" href="basket.php?action=increase&&product=<?=$id?>">
                                  <button>+</button>
                              </a>               
                          </td>
                      </tr>
                <?php
                      $result->free();
                    }
                  }
                ?>
            </tbody>
            <tfoot>
              <tr>
                <td id="total" colspan="3">Total:</td>
                <td><?=$total?> €</td>
                <td></td>
                <td></td>
              </tr>
            </tfoot>
        </table>
<?php
        $stmt->close();
      } else {
        echo "Carrito vacio...";
      }
?>
</body>
</html>
<?php
  }
  $link->close();
?>