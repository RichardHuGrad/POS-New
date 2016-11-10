<?php

 ?><div class="clearfix marginB15 cashierbox" style="display:none">
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
                        <div class="row  <?php if ($value['all_extras']) { ?>dropdown-toggle<?php }?>" data-toggle="dropdown">
                            <div class="col-md-8 col-sm-8 col-xs-7">
                                <div class="pull-left titlebox">
                                    <!-- to show name of item -->
                                    <div class="less-title"><?php echo $value['name_en']."<br/>".$value['name_xh']; ?></div>

                                    <!-- to show the extras item name -->
                                    <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-5 price-txt paddinT5">$<?php echo ($value['price']+$value['extras_amount']); ?><?php echo $value['qty']>1?"x".$value['qty']:"" ?></div>
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
                                        <a class="clearfix add_extras"  item_id="<?php echo $value['id']; ?>" price="<?php echo $ex['price'] ?>"  name="<?php echo $ex['name_zh'] ?>" alt="<?php echo $ex['id'] ?>" href="javascript:void(0)">
                                            <?php 
                                                echo "<span class='pull-left'><!-- ".$ex['name']."<br/ -->".$ex['name_zh']."</span>";
                                                if($ex['price']){
                                                    echo "<span class='pull-right'>".$ex['price']."</span>";
                                                }
                                             ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                                <div class="show_extras" alt="<?php echo $value['id'] ?>" id="block<?php echo $value['id'] ?>">
                                    <?php
                                        if(!empty($selected_extras)) {
                                            foreach($selected_extras as $selected) {
                                                ?>
                                                <div class="extras_inner"  alt="<?php echo $selected['id'] ?>">
                                                    <?php 
                                                        echo "<span>".$selected['name']."</span>";
                                                        if($selected['price']){
                                                            echo "<span>".$selected['price']."</span>";
                                                        }
                                                        echo "<a href='javascript:void(0)' class='fa fa-times remove_extra'> </a>";
                                                     ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                     ?>
                                </div>
                                <div class="show_extras"><label>Special Instructions:&nbsp;&nbsp;</label><input type="text" name="ext_memo" id="ext_memo" placeholder="e.g. no onions, no mayo" size="30" value="" />&nbsp;&nbsp;&nbsp;<button type="button" id="clearbtn" name="clearbtn" class="clearbtn"  alt="<?php echo $value['id'] ?>">Clear 清除</button></div>
                                <button type="button" id="extnobtn" class="extbtn"  alt="<?php echo $value['id'] ?>">No</button>
                                <button type="button" id="extmorebtn" class="extbtn"  alt="<?php echo $value['id'] ?>">More</button>
                                <button type="button" id="extlessbtn" class="extbtn"  alt="<?php echo $value['id'] ?>">Less</button>
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
<?php// }?>

<div class="bgwhite clearfix">
    <?php if(empty($Order_detail) OR !$Order_detail['Order']['discount_value']) { ?>
        <div class="padding10 adddoscount">
            Add Discount 加入折扣  <i class="fa fa-plus-circle pull-right add-discount <?php
if(empty($Order_detail) or empty(@$Order_detail['OrderItem'])) echo 'disabled'
    ?>" aria-hidden="true"></i>
        </div>
    <?php }?>

    <?php if(!empty($Order_detail) and !$Order_detail['Order']['discount_value']) { ?>
    <div class="subtotalwrap discount_view" style="display:none;">
        <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fix_discount" style="font-size:11px;">Fix Discount</label>
                        <input type="text" id="fix_discount" required="required" class="form-control discount_section" maxlength="5"  name="fix_discount">                                                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="discount_percent" style="font-size:11px;">Discount in %</label>
                        <input type="text" id="discount_percent" required="required" class="form-control discount_section" maxlength="5"   name="discount_percent">                                                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="promocode" style="font-size:11px;">Promo Code</label>
                        <input type="text" id="promocode" required="required" class="form-control discount_section" maxlength="200" name="promocode">                                                    
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="AdminTableSize">&nbsp;</label>
                            <a class="btn btn-primary btn-wide pull-right" id="apply-discount" href="javascript:void(0)">Apply <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
        </div>
    </div>
    <?php } ?>
    <div class="subtotalwrap">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">Subtotal 小计: <strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['subtotal'], 2); else echo '0.00';  ?></strong></div>


            <div class="col-xs-6 col-sm-6 col-md-6">Taxes 税 (<?php if(!empty($Order_detail) and !empty(@$Order_detail['Order'] )) echo $Order_detail['Order']['tax'] ?>%): <strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['tax_amount'], 2); else echo '0.00'; ?></strong> </div>

        </div>
    </div>
    <?php if(!empty($Order_detail) and $Order_detail['Order']['discount_value'])  {
            ?>
            <div class="subtotalwrap">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8">Discount 折扣</div>
                    <div class="col-xs-4 col-sm-4 col-md-4 text-right">
                    <strong>
                        $<?php echo number_format($Order_detail['Order']['discount_value'], 2) ;
                                if($Order_detail['Order']['percent_discount']) {
                                    echo "<br/><span class='txt12'> ".$Order_detail['Order']['promocode']." (".$Order_detail['Order']['percent_discount']."%)</span>";
                                }
                        ?> 
                        <a aria-hidden="true" class="fa fa-times pull-right remove_discount" order_id="<?php echo $Order_detail['Order']['id']; ?>" href="javascript:void(0)"></a>                       
                    </strong></div>
                            
                </div>
            </div>
        <?php
        }
        ?>

    <div class="subtotalwrap">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">Total 总额: <strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['total'], 2); else echo '0.00'; ?></strong></div>

            <div class="col-xs-6 col-sm-6 col-md-6">
                <textarea name="" cols="" rows="" class="form-control" placeholder="Message" id="Message"><?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo $Order_detail['Order']['message'] ?></textarea>
            </div>
        </div>
    </div>

    <div class="clearfix subtotal-btn-wrap">
        <button type="submit" class="submitbtn <?php
if(empty($Order_detail) or empty(@$Order_detail['OrderItem'])) echo 'disabled'
    ?>" id="submit" alt="<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo $Order_detail['Order']['id'] ?>">Send to kitchen 发送到厨房</button>
        <button type="submit" class="paybtn <?php
if(empty($Order_detail) or empty(@$Order_detail['OrderItem'])) echo 'disabled'
    ?>" id="pay" alt="<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo $Order_detail['Order']['id'] ?>">Pay 结账</button>
    </div>
</div>
<input type="hidden" name="Order_Item" id="Order_Item" value="" />

<!-- Modified by Yishou Liao @ Oct 25 2016. -->
<script type="text/javascript">
var orderStr = "";
<?php for ($i=0;$i<count(@$Order_detail_print);$i++) {
	if ($i == (count(@$Order_detail_print)-1)) {?>;
	orderStr += '<?php echo implode("*",@$Order_detail_print[$i]['order_items']); ?>';
	orderStr += '*' + '<?php echo @$Order_detail_print[$i]['categories']['printer']; ?>'
<?php } else { ?>
	orderStr += '<?php echo implode("*",@$Order_detail_print[$i]['order_items']); ?>';
	orderStr += '*' + '<?php echo @$Order_detail_print[$i]['categories']['printer']; ?>'+'#';
<?php }; ?>

	$('#Order_Item').val(orderStr);
<?php } ?>
</script>
<!-- End. -->