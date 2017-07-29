<?php
include_once 'simple_html_dom.php';
include_once 'dbconfig.php';
include_once 'stdlib.php';

$links = [
    /*'http://klikgalaxy.com/products/1/0/Processor/',
    'http://klikgalaxy.com/products/87/0/Intel/',
    'http://klikgalaxy.com/products/86/0/AMD/',
    'http://kkomputer.com/88-harga-jual-motherboard-intel-socket-2011',
    'http://kkomputer.com/87-harga-jual-motherboard-intel-socket-1150',
    'http://kkomputer.com/124-socket-1151',
    'http://kkomputer.com/89-harga-jual-motherboard-amd-socket-am3',
    'http://kkomputer.com/90-harga-jual-motherboard-amd-socket-fm2',
    'http://kkomputer.com/91-harga-jual-motherboard-amd-socket-fm2',
    'http://kkomputer.com/92-harga-jual-motherboard-amd-socket-am1',
    'http://kkomputer.com/59-processor-intel',
    'http://kkomputer.com/60-processor-amd',
    'http://blossomzones.com/product-category/motherboard/motherboard-intel/1151',
    'http://blossomzones.com/product-category/motherboard/motherboard-amd/am3-motherboard-amd',
    'http://blossomzones.com/product-category/motherboard/motherboard-amd/fm2-motherboard-amd',
    'http://blossomzones.com/product-category/processor/intel/1151-intel',
    'http://blossomzones.com/product-category/processor/amd/am3',
    'http://blossomzones.com/product-category/processor/amd/fm2',*/
    //'https://www.tokopedia.com/enterid/etalase/prosesor',
    'https://www.tokopedia.com/enterid/etalase/motherboard'
];

$sitecount = 0;
$time_start = microtime(true);
foreach ($links as $url) {
    $sitecount++;
    // load page
    $crawl = "";
    $vendor = "";
    if(startsWith($url, 'http://klikgalaxy.com')){
        $crawl = crawl_klikgalaxy($url);
    } else if(startsWith($url, 'http://kkomputer.com')){
        $crawl = crawl_kkomputer($url);
    } else if(startsWith($url, 'http://blossomzones.com')){
        $crawl = crawl_blossomzones($url);
    } else if(startsWith($url, 'https://www.tokopedia.com')){
        $crawl = crawl_tokopedia($url);
    }

    // Insert or Update to db
    if(is_array($crawl)){
        foreach ($crawl as $product) {
            // get current product
            $stmt = $conn->prepare('SELECT * FROM products WHERE link = :link');
            $stmt->execute(array(
                ':link' => $product['link']
            ));
            $dbproduct = $stmt->fetch(PDO::FETCH_ASSOC);

            // update current value
            if(!empty($dbproduct)){
                echo 'existing product<br/>';
                if($dbproduct['price'] != $product['price']){
                    $stmt = $conn->prepare('UPDATE products SET price = :price, date_updated = :u WHERE id = :id');
                    $stmt->execute(array(
                        ':id' => $dbproduct['id'],
                        ':price' => $product['price'],
                        ':u' => date('Y-m-d H:i:s')
                    ));
                }
            }
            // insert new product
            else {
                echo 'insert new product<br/>';
                $stmt = $conn->prepare('INSERT INTO products(title,price,vendor,link,date_inserted,date_updated) VALUES(:title,:price,:vendor,:link,:i,:u)');
                $stmt->execute(array(
                    ':title' => $product['title'],
                    ':price' => $product['price'],
                    ':vendor' => $product['vendor'],
                    ':link' => $product['link'],
                    ':i' => date('Y-m-d H:i:s'),
                    ':u' => date('Y-m-d H:i:s'),
                ));
            }
        }
    }
}
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo 'Crawling: '.$sitecount.' sites. <b>Total Execution Time:</b> '.$execution_time.' Mins';
