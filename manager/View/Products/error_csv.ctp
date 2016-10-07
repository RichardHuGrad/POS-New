<?php
$this->CSV->addRow(array('Below data failed to import :'));
if(!empty($error)){
    foreach ($error as $k => $err)
    {
        if(0 == $k){
            $this->CSV->addRow(array_keys($err));
        }
        $this->CSV->addRow($err);
    }
}

$filename = 'ProductImportError' . time();
echo  $this->CSV->render($filename);