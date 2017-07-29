<?php
include_once 'simple_html_dom.php';
include_once 'dbconfig.php';
include_once 'stdlib.php';

// Create DOM from URL or file



    $html = file_get_html('http://kkomputer.com/59-processor-intel');


    foreach($html->find('#product_list li') as $element){
        if(trim($element->plaintext) != '&nbsp;'){
            $title = trim($element->find('h3')[0]->plaintext);
            $link  = trim($element->find('a')[0]->href);
            $price = trim($element->find('span.price')[0]->plaintext);
            $price = strip_price($price);
            $price = $price == '' ? 0 : $price;


            echo $title.'('.$price.') - '.$link.'<br>';
        }
    }
