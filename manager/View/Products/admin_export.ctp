<?php
$headers = array('S.No.', 'Name', 'Category', 'Brand', 'Description', 'Images');
$this->CSV->addRow($headers);

if(!empty($products)){

    foreach ($products as $k => $data)
    {
        $images = array();
        foreach ($data['ProductImage'] as $img){
            $images[] = PRODUCT_IMAGE_URL . $img['image'];
        }
        $images = implode(' ' , $images);
        
        $csv_data = array(
            $k + 1,
            $data['Product']['name'],
            $data['Category']['name'],
            $data['Brand']['name'],
            $data['Product']['description'],
            $images
        );
        $this->CSV->addRow($csv_data);
    }

}else{
    $this->CSV->addRow(array('Products Not Available.'));
}

$filename = 'Products' . time();
echo  $this->CSV->render($filename);