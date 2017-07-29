<?php
function strip_price($price){
    $price = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $price);
    $price = str_replace('Rp', '', $price);
    $price = str_replace('.', '', $price);
    $price = str_replace('&nbsp;', '', $price);
    $price = trim($price);
    return $price;
}

function startsWith($haystack, $needle){
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function crawl_klikgalaxy($url){
    $crawl = array();
    $html = file_get_html($url);
    foreach($html->find('td.hotitems') as $element){
        if(trim($element->plaintext) != '&nbsp;'){
            $title = trim($element->find('h2')[0]->plaintext);
            $price = '';
            if(!empty($element->find('span.product-price'))){
                $price = trim($element->find('span.product-price')[0]->plaintext);
            }
            $price = strip_price($price);
            $price = $price == '' ? 0 : $price;
            $link  = 'http://klikgalaxy.com' . $element->find('a')[0]->href;

            $data = array(
                'title' => $title,
                'price' => $price,
                'vendor' => 'klikgalaxy',
                'link' => $link
            );
            $crawl[] = $data;
        }
    }
    return $crawl;
}

function crawl_kkomputer($url){
    $crawl = array();
    $html = file_get_html($url);
    foreach($html->find('#product_list li') as $element){
        if(trim($element->plaintext) != '&nbsp;'){
            $title = trim($element->find('h3')[0]->plaintext);
            $link  = trim($element->find('a')[0]->href);
            $price = trim($element->find('span.price')[0]->plaintext);
            $price = strip_price($price);
            $price = $price == '' ? 0 : $price;

            $data = array(
                'title' => $title,
                'price' => $price,
                'vendor' => 'kkomputer',
                'link' => $link
            );
            $crawl[] = $data;
        }
    }
    return $crawl;
}

function crawl_blossomzones($url){
    $crawl = array();
    $page = 1;
    $valid = true;
    while($valid){
        $html = file_get_html($url.'/page/'.$page);
        if($html === false) {
            $valid = false;
            break;
        }

        foreach($html->find('ul.products li') as $element){
            if(trim($element->plaintext) != '&nbsp;'){
                $title = trim($element->find('h3')[0]->plaintext);
                $link  = trim($element->find('a')[0]->href);
                $price = trim($element->find('span.amount')[0]->plaintext);
                $price = strip_price($price);
                $price = $price == '' ? 0 : $price;

                $data = array(
                    'title' => $title,
                    'price' => $price,
                    'vendor' => 'blossomzones',
                    'link' => $link
                );
                $crawl[] = $data;
            }
        }

        $page++;
    }

    return $crawl;
}

function crawl_tokopedia($url) {
    $crawl = array();
    $html = file_get_contents($url);

    // get vendor name
    preg_match('/^http[s]?:\/\/.*?\/([a-zA-Z-_]+).*$/', $url, $match);
    $vendor = $match[1];

    // get tokopedia ace url
    preg_match_all("/var ace_url    = \"(.*)\";/", $html, $match);
    $aceUrl = $match[1][0];
    // get tokopedia shop id
    preg_match_all('<input type="hidden" id="menu_list" value="(\d*)">', $html, $match);
    $shopId = $match[1][0];
    // get tokopedia etalase id
    preg_match_all('<input type="hidden" id="active_etalase_id" value="(\d*)" >', $html, $match);
    $etalaseId = $match[1][0];

    // build query
    $params = array(
        'shop_id' => $shopId,
        'etalase' => $etalaseId,
        'start' => 0,
        'rows' => 80,
        'device' => 'desktop',
        'scheme' => 'https',
        'full_domain' => 'www.tokopedia.com',
        'source' => 'shop_product'
    );
    $turl = $aceUrl . '?' . http_build_query($params);

    // get json from tokopedia url
    $json = file_get_contents($turl);
    $obj = json_decode($json);

    // parse as marketwatch friendly data
    foreach($obj->data->products as $product){
        $data = array(
            'title' => $product->name,
            'price' => strip_price($product->price),
            'vendor' => $vendor,
            'link' => $product->url
        );
        $crawl[] = $data;
    }

    return $crawl;
}
