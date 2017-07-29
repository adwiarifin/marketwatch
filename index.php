<?php

$dbhost   = "localhost";
$dbuser   = "kesatria_adwi";
$dbpass   = "3of77g9qN03g";
$dbname   = "kesatria_marketwatch";

$conn = '';
$result = array();
try {
  $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare('select title,price,vendor,link from products where price > 0 order by title,price desc');
  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  echo 'ERROR: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Marketwatch - Kesatria Keyboard</title>

    <link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <div id="header" class="text-center">
            <h1>MARKETWATCH</h1>
        </div>

        <div id="body">
            <table id="marketwatch" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Visit Link</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Visit Link</th>
                    </tr>
                </tfoot>
                <tbody>
    <?php foreach($result as $data): ?>
                    <tr>
                        <td><?php echo $data['title']; ?></td>
                        <td class="text-right"><?php echo $data['price']; ?></td>
                        <td class="text-center"><img width="20px" height="20px" src="images/vendor/<?php echo $data['vendor']; ?>.png" /> <a href="<?php echo $data['link']; ?>">Visit</a></td>
                    </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="footer" class="text-center">
            <a href="http://kesatriakeyboard.com/marketwatch/cron/job.php" target="_blank">Reload New Data</a>
            <div style="background: #4B515D; color: white; margin-top: 20px; padding: 10px;">
                Data Taken From:<br/>
                <a href="http://kkomputer.com"><img width="50px" height="50px" style="margin: 10px;" src="https://kesatriakeyboard.com/marketwatch/images/vendor/kkomputer.png" /></a>
                <a href="http://klikgalaxy.com"><img width="50px" height="50px" style="margin: 10px;" src="https://kesatriakeyboard.com/marketwatch/images/vendor/klikgalaxy.png" /></a>
                <a href="http://blossomzones.com"><img width="50px" height="50px" style="margin: 10px;" src="https://kesatriakeyboard.com/marketwatch/images/vendor/blossomzones.png" /></a>
                <a href="http://tokopedia.com/enterid"><img width="50px" height="50px" style="margin: 10px;" src="https://kesatriakeyboard.com/marketwatch/images/vendor/enterid.png" /></a>
                <br/>
                &copy; 2017 - Kesatria Keyboard
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#marketwatch').DataTable({
                "columnDefs": [{
                    "render": $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ),
                    "targets": 1
                }]
            });
        } );
    </script>
</body>

</html>
