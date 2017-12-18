<div class="clearfix marginB15 cashierbox">
    <div class="pull-left marginR5">
        <?php if ($cashier_detail['Cashier']['image']) { ?>
            <?php echo $this->Html->image(TIMB_PATH."timthumb.php?src=".CASHIER_IMAGE_PATH . $cashier_detail['Cashier']['image']."&h=60&w=60&&zc=4&Q=100", array('class'=>'img-circle img-responsive')); ?>
        <?php } else { ?>
            <?php echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=60&w=60&&zc=4&Q=100", array('class'=>'img-circle img-responsive'));  ?>
        <?php } ?>
    </div>
    <div class="pull-left marginL5 clearfix">
        <div class="txt16 marginB5 marginT5"><?php echo ucfirst($cashier_detail['Cashier']['firstname'])." ".$cashier_detail['Cashier']['lastname']; ?></div>
        <div class="txt15"><?php echo str_pad($cashier_detail['Cashier']['id'], 4, 0, STR_PAD_LEFT); ?></div>
    </div>
</div>

<?php
 if (!empty(@$Order_detail['OrderItem'])) { ?>
<div class="clearfix marginB15 cashierbox">
    <div class="order-summary-indent clearfix">

        <ul>
            <?php
            if(!empty($Order_detail['OrderItem'])) {
                foreach ($Order_detail['OrderItem'] as $key => $value) {
                    # code...
                    $selected_extras_name = [];
                    if ($value['all_extras']) {
                        $extras = json_decode($value['all_extras'], true);
                        $selected_extras = json_decode($value['selected_extras'], true);

                        // prepare extras string
                        $selected_extras_id = [];
                        if(!empty($selected_extras)) {
                            foreach($selected_extras as $k=>$v){
                                $selected_extras_name[] = $v['name'];
                                $selected_extras_id[] = $v['id'];
                            }
                        }
                    }
                  ?>
                    <li class="clearfix dropdown" style="border-top:0px; padding-top:5px;">
                        <div class="row  dropdown-toggle" data-toggle="dropdown">
                            <div class="col-md-8 col-sm-8 col-xs-7">
                                <div class="pull-left">
                                    <?php 
                                      if ($value['image']) { 
                                        echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $value['image']."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                      } else {
                                        echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                      } 
                                      ?>
                                </div>
                                <div class="pull-left titlebox">
                                    <!-- to show name of item -->
                                    <div class="less-title"><?php echo $value['name_en']."<br/>".$value['name_xh']; ?></div>

                                    <!-- to show the extras item name -->
                                    <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-5 price-txt paddinT10">$<?php echo ($value['price']+$value['extras_amount']); ?><?php echo $value['qty']>1?"x".$value['qty']:"" ?></div>
                        </div>
                        <?php

                        if ($value['all_extras']) {
                            ?>
                            <ul class="dropdown-menu sub-items" id="sub_<?php echo $value['id'] ?>">
                                <div class="menu-arrow"></div>
                                <?php
                                foreach($extras as $ex) {
                                    ?>
                                    <li>
                                        <a class="clearfix add_extras <?php if(in_array($ex['id'], $selected_extras_id)) echo "active"; ?>"  item_id="<?php echo $value['id']; ?>" alt="<?php echo $ex['id'] ?>" href="javascript:void(0)">
                                            <?php 
                                                echo "<span class='pull-left'>".$ex['name']."<br/>".$ex['name_zh']."</span>";
                                                if($ex['price']){
                                                    echo "<span class='pull-right'>".$ex['price']."</span>";
                                                }
                                             ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                                <button type="button" class="savebtn"  alt="<?php echo $value['id'] ?>">Save 保存</button>
                            </ul>
                        <?php }?>
                        <a href="javascript:void(0)" alt="<?php echo $value['id'] ?>" order_id="<?php echo $Order_detail['Order']['id'] ?>" class="fa fa-times pull-right close-link" aria-hidden="true"></a>
                    </li>
            <?php }
        }?>
        </ul>
    </div>
</div>
<?php }?>

<?php
if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) {
    ?>
<div class="bgwhite clearfix">
    <div class="padding10 adddoscount">
        Add Discount 加入折扣 <i class="fa fa-plus-circle pull-right" aria-hidden="true"></i>
    </div>

    <div class="subtotalwrap">
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8">Subtotal 小计</div>
            <div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php echo number_format($Order_detail['Order']['subtotal'], 2) ?></strong></div>
        </div>
    </div>

    <div class="subtotalwrap">
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8">Taxes 税 (<?php echo $Order_detail['Order']['tax'] ?>%) </div>
            <div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php echo number_format($Order_detail['Order']['tax_amount'], 2) ?></strong></div>
        </div>
    </div>

    <div class="subtotalwrap">
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8">Total 总</div>
            <div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php echo number_format($Order_detail['Order']['total'], 2) ?></strong></div>
        </div>
    </div>

    <div class="commentwrap">
        <textarea name="" cols="" rows="" class="form-control" placeholder="Message" id="Message"><?php echo $Order_detail['Order']['message'] ?></textarea>
    </div>

    <div class="clearfix padding15">
        <button type="submit" class="submitbtn" id="submit" alt="<?php echo $Order_detail['Order']['id'] ?>">Submit 提交</button>
        <button type="submit" class="paybtn" id="pay" alt="<?php echo $Order_detail['Order']['id'] ?>">Pay 工资</button>
    </div>
</div>
<?php }?>