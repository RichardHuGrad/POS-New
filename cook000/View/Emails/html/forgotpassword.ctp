<div style="width:100% !important;overflow-y:none">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif;">
        <tr>
            <td style="font-size: 13px; padding: 10px 0px;border-bottom: solid 2px #333">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="45%" align="left" valign="bottom" >
                            <div class=""><h2 style="margin-bottom:0px">Your New Password</h2></div>
                        </td>
                        <td width="30%" align="right">
                            <img src="<?php echo WEBSITE_FRONT_IMAGE; ?>login-logo.jpg" width="246" height="68" />

                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
	    <td style="padding: 10px 0px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">                                        
                    <tr>

			<td align="left" style="font-size: 14px; ">
			    <p>
				Hello <?php echo ucfirst($name); ?>,
			    </p>											
			</td>
                    </tr>
		    <tr><td style='height:30px' colspan="2">&nbsp;</td></tr>					
		    <tr>
                        <td align="left" style="font-size:14px; ">
			    <p>
				Your Password has been changed successfully </br>									
			    </p>											
                        </td>
                    </tr>
		    <tr><td style='height:30px' colspan="2">&nbsp;</td></tr>
		    <tr>
			<td align="left">
			    <table border="0" cellpadding="0" cellspacing="0" width="100%">
				
				<tr>
				    <td align="left" width="10%"><strong>New Password : </strong></td>
				    <td align="left" width="85%"><?php echo $password; ?></td>
				</tr>
			    </table>
			</td>
                    </tr>
		    <tr><td style='height:0px' colspan="2">&nbsp;</td></tr>	


                </table>
	    </td>
	</tr>


	<tr>
	    <td style="padding: 10px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">                                        
                    <tr>

			<td align="center" style="font-size: 13px; padding: 10px 0px;border-bottom: solid 2px #333">
			    <p>
				This is an auto generated e-mail, please do not reply to the sender.<br />
				For any queries kindly mail to contact@pos.com
			    </p>											
			</td>
                    </tr>
                </table>
	    </td>
	</tr>

	


    </table>

</div>

<?php //exit; ?>