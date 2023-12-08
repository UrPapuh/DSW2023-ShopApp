<?php
  session_start();
  require('../src/utils/connection.php');
  if ($error == null) {
    // Order
    if (isset($_GET['order'])) {
      $order = $_GET['order'];
    } else if (isset($_COOKIE['order'])) {
      $order = $_COOKIE['order'];
    } else {
      $order = '';
    }
    switch ($order) {
      case 'name':
        $sql = 'SELECT * FROM products ORDER BY name ASC';
        setcookie("order", 'name', time() + 3600);
        break;
      case 'price':
        $sql = 'SELECT * FROM products ORDER BY price ASC';
        setcookie("order", 'price', time() + 3600);
        break;
      case 'amount':
        $sql = 'SELECT * FROM products ORDER BY amount ASC';
        setcookie("order", 'amount', time() + 3600);
        break;
      default:
        $sql = 'SELECT * FROM products';
        break;
    }

    $action = isset($_GET['action'])? $_GET['action']:'';
    switch ($action) {
      case 'add':
        if (isset($_SESSION['basket'][$_GET['product']])) {
          $_SESSION['basket'][$_GET['product']]++;
        } else {
          $_SESSION['basket'][$_GET['product']] = 1;
        }
        break;
    }
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
      <a href="basket.php">
        <button>&#x1F6D2;</button>
      </a>
    </nav>
    <h1>Lista de Productos</h1>
  </header>
<?php
    $stmt = $link->stmt_init();

    $stmt->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
                                                
    if ($result->num_rows > 0) {                            
?>
      <table>
          <thead>
              <tr>
                  <th>
                    <a href="index.php?order=name">name</a>
                  </th>
                  <th>
                    <a href="index.php?order=price">price</a>
                  </th>
                  <th>
                    <a href="index.php?order=amount">amount</a>
                  </th>
                  <th></th>
              </tr>
          </thead>
          <tbody>
              <?php            
                while ($product = $result->fetch_array()) {
                  if ($product['amount'] > 0) {
              ?>
                    <tr>
                        <td><?=$product['name']?></td>
                        <td><?=$product['price']?>€</td>
                        <td><?=$product['amount']?></td>
                        <td class="actions">                            
                            <a class="button" href="index.php?action=add&&product=<?=$product['id']?>">
                                <button>+</button>
                            </a>               
                        </td>
                    </tr>
              <?php
                }
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