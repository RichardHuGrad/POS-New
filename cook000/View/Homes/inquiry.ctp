<header class="product-header">
  <div class="container">
      <div class="home-logo"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>">
          <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
      </a></div>
      <div class="logout"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'logout')) ?>">Logout 登出</a></div>
  </div>
</header>
<div class="container">
    <div class="clearfix cartwrap-wrap">
     <div class="row">       
       <div class="col-md-9 col-sm-8 col-xs-12 home-link">
        <div class="clearfix">
          <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'index')) ?>" class="submitbtn" >Home 家</a>
          <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>" class="submitbtn" >Back 背部</a>
        </div>
       </div>
    </div>
  </div>
    <div class="calculator clearfix">
           <div class="calc-top">
            <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>"><span class="pull-left"><i class="fa fa-angle-left" aria-hidden="true"></i></span></a>
            Enter Code 输入代码
        </div>

        <div class="calc-total clearfix">
            <ul>
                <li><input type="text" placeholder="-" class="order_input order1" alt="1" maxlength="1"></li>
                <li><input type="text" placeholder="-" class="order_input order2" alt="2" maxlength="1"></li>
                <li><input type="text" placeholder="-" class="order_input order3" alt="3" maxlength="1"></li>
                <li><input type="text" placeholder="-" class="order_input order4" alt="4" maxlength="1"></li>
                <li><input type="text" placeholder="-" class="order_input order5" alt="5s" maxlength="1"></li>
            </ul>
        </div>
        <div class="calc-indent clearfix">
            <ul>
                <li>7</li>
                <li>8</li>
                <li>9</li>

                <li>4</li>
                <li>5</li>
                <li>6</li>

                <li>1</li>
                <li>2</li>
                <li>3</li>

                <li class="clear-txt" id="Clear">Clear 明确</li>
                <li>0</li>
                <li class="enter-txt" id="Enter">Enter 输入</li>
            </ul>
        </div>
      </div>
</div>
<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
echo $this->fetch('script');
?>
<script>
$(document).ready(function(){

  $(".select_card"). click(function() {
    $(this).addClass("active")
    var type = $(this).attr("id");
    if(type == 'card')
        $("#cash").removeClass("active")
    else
        $("#card").removeClass("active")

    $("#selected_card").val(type);
  })

  

  $(".calc-indent li").click(function() {
    if($(this).hasClass("clear-txt") || $(this).hasClass("enter-txt"))
      return false;

    var digit =  parseInt($(this).html());
    for(i = 1; i <= 5; i++) {
      if(!$(".order"+i).val()) {
        $(".order"+i).val(digit);
        $(".order"+(i+1)).focus();
        return true;
      }
    }
  })

  $("#Enter").click(function() {
    var order_id = "";
    for(i = 1; i <= 5; i++) {
        order_id += $(".order"+i).val();
    }
    if(order_id.length == 5) {
      // send request to server to verify order no
      window.location = "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay')) ?>?order_no="+order_id;
    } else {
      alert("Please enter valid order number 请输入有效的订单号码.");
      return false;
    }
  })
  $(".order_input").keyup(function() {
    var val = $(this).val();
    var id = parseInt($(this).attr("alt"));
    if(val) {      
        $(".order"+(id+1)).focus();
    } else {
      return false;
    }
  })

  $("#Clear").click(function() {
    $(".order_input").val("");
  })

  $(".order_input").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
           // Allow: Ctrl+A, Command+A
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
           // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               // let it happen, don't do anything
               return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
  });
})
</script>