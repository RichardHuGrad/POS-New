<?php 
    echo $this->Html->css('summarypanel.css');
?>

<?php
$numofcomb = ""; //Modified by Yishou Liao @ Dec 15 2016
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
                    //Modified by Yishou Liao @ Dec 13 2016
					if ($value['all_extras']) {
					//if (count($extras)) {
					//End @ Dec 09 2016
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
                    	<!-- Modified by Yishou Liao @ Dec 13 2016 -->
                    	<a href="javascript:void(0)" alt="<?php echo $value['id'] ?>" order_id="<?php echo $Order_detail['Order']['id'] ?>" class="fa fa-car waimai-link" aria-hidden="true"></a>
                        <div class="row  <?php if ($value['all_extras']) { ?>dropdown-toggle<?php }?>" data-toggle="dropdown">
                        <!-- <div class="row  <?php //if (count($extras)) { ?>dropdown-toggle<?php //}?>" data-toggle="dropdown"> -->
                        <!-- End -->
                            <div class="col-md-8 col-sm-8 col-xs-7" >
                                <div class="pull-left titlebox">
                                    <!-- to show name of item -->
                                    <div class="less-title" style="padding-left:24px;"><?php echo (($value['is_waimai'] == 'Y') ? "(Takeaway) " : "" ) . $value['name_en']."<br/>".(($value['is_waimai'] == 'Y') ? "(外卖) " : "" ).$value['name_xh']; ?></div>

                                    <!-- to show the extras item name -->
                                    <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-5 price-txt paddinT5">$<?php echo ($value['price']+$value['extras_amount']); ?><?php echo $value['qty']>1?"x".$value['qty']:"" ?></div>
                        </div>

                        <?php
                        //Modified by Yishou Liao @ Dec 09 2016
						if ($value['all_extras']) {
						//if (count($extras)) {
						//End @ Dec 09 2016
                            ?>

                            <ul class="dropdown-menu sub-items" id="sub_<?php echo $value['id'] ?>">
                            <div>
                            <?php
							//Modified by Yishou Liao @ Dec 14 2016
							$cate_disp = array();
							foreach($extras as $ex) {
								if (in_array($ex['category_id'],$cate_disp)==false){
									$cate_disp[] = $ex['category_id'];
								};
							};
							
							$comb_flag = 0;
							foreach($extras_categories as $ex_category) {
								if (in_array($ex_category['extrascategories']['id'],$cate_disp)==true){
									if ($ex_category['extrascategories']['extras_num'] > 0){
										$comb_flag = 1;
										$numofcomb = $ex_category['extrascategories']['id'] . "," . $ex_category['extrascategories']['extras_num']; //Modified by Yishou Liao @ Dec 15 2016
									};
								};
							};
							$tab_disp = array();
							//End @ Dec 14 2016
							
                            foreach($extras_categories as $ex_category) {
								if (in_array($ex_category['extrascategories']['id'],$cate_disp)==true){ //Modified by Yishou Liao @ Dec 14 2016
									if ((!empty($selected_extras) && $comb_flag==1) || $comb_flag==0 || $ex_category['extrascategories']['extras_num'] > 0) {
										$tab_disp[] = $ex_category['extrascategories']['id'];//Modified by Yishou Liao @ Dec 14 2016
                                ?>
                                
                                <div class="tabSyle" atab="<?php echo $ex_category['extrascategories']['id'] ?>"> <?php echo $ex_category['extrascategories']['name'] . '(' . $ex_category['extrascategories']['name_zh'] . ')'; ?></div>
                                <?php
									};
								}; //End @ Dec 14 2016
                             };
                                ?>
                            </div>
                                <div class="menu-arrow"></div>
                                <!-- Modified by Yishou Liao @ Nov 30 2016 -->
                                <?php
                                foreach($extras as $ex) {
									if (in_array($ex['category_id'],$tab_disp)==true){ //Modified by Yishou Liao @ Dec 14 2016
								?>
                                    <li class="addadish" adish="<?php echo $ex['category_id'] ?>">
                                        <a class="clearfix add_extras" category_id="<?php echo $ex['category_id'] ?>" item_id="<?php echo $value['id']; ?>" price="<?php echo $ex['price']>0?$ex['price']:"" ?>"  name="<?php echo $ex['name_zh'] ?>" alt="<?php echo $ex['id'] ?>" href="javascript:void(0)">

                                            <div class="addadish-items">
                                                <?php 
                                                    echo "<span class='pull-left'><!-- ".$ex['name']."<br/ -->".$ex['name_zh']."</span>";
                                                    if($ex['price']>0){
                                                        echo "<span class='pull-right'>".$ex['price']."</span>";
                                                    }
                                                 ?>
                                            </div>
                                            
                                        </a>
                                    </li>
                                <?php
									};//End @ Dec 14 2016
                                }
                                ?>
                                <!-- End -->
                                
                                <div class="show_extras" alt="<?php echo $value['id'] ?>" id="block<?php echo $value['id'] ?>">
                                    <?php
                                        if(!empty($selected_extras)) {
                                            foreach($selected_extras as $selected) {
												if ($selected['name'] !=""){
                                                ?>
                                                <div class="extras_inner" category_id="<?php echo $selected['category_id'] ?>" alt="<?php echo $selected['id'] ?>">
                                                    <?php 
                                                        echo "<span>".$selected['name']."</span>";
                                                        if($selected['price']>0){
                                                            echo "<span>".$selected['price']."</span>";
                                                        }
                                                        echo "<a href='javascript:void(0)' class='fa fa-times remove_extra'> </a>";
                                                     ?>
                                                </div>
                                                <?php
												}
                                            }
                                        }
                                     ?>
                                </div>
                                <div class="show_extras"><label>Special Instructions:&nbsp;&nbsp;</label><input type="text" name="ext_memo" id="ext_memo<?php echo $value['id']; ?>" placeholder="e.g. no onions, no mayo" size="30" value="" />&nbsp;&nbsp;&nbsp;

                                <div>
                                    <button type="button" id="extnobtn" class="extbtn"  alt="<?php echo $value['id'] ?>" style="display:none">No</button> 
                                    <button type="button" id="extmorebtn" class="extbtn"  alt="<?php echo $value['id'] ?>" style="display:none">More</button> 
                                    <button type="button" id="extlessbtn" class="extbtn"  alt="<?php echo $value['id'] ?>" style="display:none">Less</button> 
                                </div>
                                <div>
                                    <button type="button" id="clearbtn" name="clearbtn" class="clearbtn btn-lg btn-warning"  alt="<?php echo $value['id'] ?>">Clear 清除</button></div>
                                    <button type="button" class="savebtn btn-lg btn-success"  alt="<?php echo $value['id'] ?>">Save 保存</button>
                                </div>

                                
                                
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
        	<!-- Modified by Yishou Liao @ Nov 25 2016 -->
            <div class="col-xs-8 col-sm-8 col-md-8">Subtotal <?php 
			if(!empty($Order_detail) and $Order_detail['Order']['discount_value']) { ?>小计(原价)<?php } else { ?> 小计 <?php } ?>: </div><div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php 
			if(!empty($Order_detail) and $Order_detail['Order']['discount_value']) {
				if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['subtotal']+$Order_detail['Order']['discount_value'], 2); else echo '0.00';  
			}else{
				if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['subtotal'], 2); else echo '0.00';  
			};
			?></strong>
            <!-- End -->
            </div>
		</div><!-- Modified by Yishou Liao @ Nov 25 2016 -->
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
            
            <div class="subtotalwrap"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
                <div class="row"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
                    <div class="col-xs-8 col-sm-8 col-md-8">After Discount 打折后: </div><div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['subtotal'], 2); else echo '0.00'; ?></strong> </div>
        
                </div>
            </div><!-- Modified by Yishou Liao @ Nov 25 2016 -->
    
        <?php
        }
        ?>

    <div class="subtotalwrap"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
    	<div class="row"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
            <div class="col-xs-8 col-sm-8 col-md-8">Taxes 税 (<?php if(!empty($Order_detail) and !empty(@$Order_detail['Order'] )) echo $Order_detail['Order']['tax'] ?>%): </div><div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['tax_amount'], 2); else echo '0.00'; ?></strong> </div>

        </div>
    </div><!-- Modified by Yishou Liao @ Nov 25 2016 -->
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
<!-- Modified by Yishou Liao -->
<input type="hidden" name="Order_Item" id="Order_Item" value="" />
<!-- End -->
<!-- Modified by Yishou Liao @ Dec 12 2016 -->
<input type="hidden" name="Order_no" id="Order_no" value="<?php echo @$Order_detail['Order']['order_no']; ?>" />
<!-- End @ Dec 12 2016 -->
<!-- Modified by Yishou Liao @ Dec 15 2016 -->
<input type="hidden" name="show_extras_flag" id="show_extras_flag" value="<?php echo @$show_extras_flag; ?>" />
<!-- End @ Dec 15 2016 -->
<!-- Modified by Yishou Liao @ Dec 15 2016 -->
<input type="hidden" name="numofcomb" id="numofcomb" value="<?php echo $numofcomb; ?>" />
<!-- End @ Dec 15 2016 -->


<!-- Modified by Yishou Liao @ Oct 25 2016. -->
<script type="text/javascript">
var orderStr = "";
<?php for ($i=0;$i<count(@$Order_detail_print);$i++) {
	if ($i == (count(@$Order_detail_print)-1)) {?>;
	orderStr += '<?php echo implode("*",@$Order_detail_print[$i]['order_items']); ?>';
	orderStr += '*' + '<?php echo @$Order_detail_print[$i]['categories']['printer']; ?>';
<?php } else { ?>
	orderStr += '<?php echo implode("*",@$Order_detail_print[$i]['order_items']); ?>';
	orderStr += '*' + '<?php echo @$Order_detail_print[$i]['categories']['printer']; ?>'+'#';
<?php }; ?>

	$('#Order_Item').val(orderStr);
<?php } ?>
</script>
<script type="text/javascript">
    //for dish additional option tab switch
    $(document).ready(function(){

        $(".dropdown-menu div.tabSyle:first-child").addClass("live");
        var defaultTab = $(".tabSyle").first().attr("atab");
        $(".addadish[adish!='" + defaultTab + "']").hide();


        $(".tabSyle").click(function(){
            $(".addadish").show();
            $("div.tabSyle").removeClass("live");
            $(this).addClass("live");

            var dichCat = $(this).attr("atab");

            //console.log(dichCat);
            //alert(dichCat);

            $(".addadish[adish!='" + dichCat + "']").hide();

        });
    });
</script>
<!-- End. -->