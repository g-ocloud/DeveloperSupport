<?php
session_name ('API-PHPSESSID');
session_start(); 
ob_start();

$username = $_COOKIE['loggedin_user'];
$user_id = $_COOKIE['id'];
$orderid = $_GET['orderid'];

include("../include/dbconfig.php");
include("../include/EnomInterface_inc.php");
include("include/version.php");

	$query2 = "SELECT * FROM transfers WHERE order_id='$orderid'";
	$result2 = @mysql_query ($query2);
	$row2 = mysql_fetch_array ($result2, MYSQL_ASSOC);

		if($row2[init_date] == NULL) {
		$init_date = "Transfer not yet completed";
		} else {
		$init_date = $row2[init_date];
		}
		
		if($row2[statusid] == 5){
		  $statusid = "Completed" ;
		  }
		if(($row2[statusid] == 0) || ($row2[statusid] == 4) || ($row2[statusid] == 9) || ($row2[statusid] == 10) ||
		 ($row2[statusid] == 11) || ($row2[statusid] == 12) || ($row2[statusid] == 13) ||
		  ($row2[statusid] == 14)) {
		  $statusid = "Processing" ;
		  }
		 if(($row2[statusid] == 15) || ($row2[statusid] == 16) ||
		   ($row2[statusid] == 17) || ($row2[statusid] == 18) || ($row2[statusid] == 19) ||
		    ($row2[statusid] == 20) || ($row2[statusid] == 21) || ($row2[statusid] == 22) ||
			 ($row2[statusid] == 23) || ($row2[statusid] == 24) || ($row2[statusid] == 25) ||
			  ($row2[statusid] == 27) || ($row2[statusid] == 30) || ($row2[statusid] == 35) ||
			   ($row2[statusid] == 31) || ($row2[statusid] == 32) || ($row2[statusid] == 37)){
		$statusid = "Order Cancelled";
		} 
		
	$query = "SELECT * FROM transfer_status WHERE status_id = '$row2[statusid]'";
	$result = @mysql_query ($query);
	$row = mysql_fetch_array ($result, MYSQL_ASSOC);

		if($row[action] == 0) {
		$action = "<img src=\"../images/transfer_ok2.gif\">";
		} else if ($row[action] == 1) {
		$action = "<img src=\"../images/transfer_progress.gif\">";
		} else if ($row[action] == 2) {
		$action = "<img src=\"../images/transfer_look.gif\">";
		} else if ($row[action] == 3) {
		$action = "<img src=\"../images/transfer_canceled.gif\">";
		}
		
			if($resend_link == 1){
				$resend_link = "Resent";
				} else {
				$resend_link = "<a href=\"transferman.php?command=resend&orderid=$row2[order_id]\"><u><b>Resend Auth Email</u></b></a>";
				}
				
			if($resubmit_link == 1){
				$resubmit_link = "Resubmitted";
				} else {
				$resubmit_link = "<a href=\"transferman.php?command=resubmit&orderid=$row2[order_id]\"><u><b>Resubmit Failed Order</u></b></a>";
				}
if(($row2[statusid] == 0) ||($row2[statusid] == 9) || ($row2[statusid] == 10) ||
					 ($row2[statusid] == 11) || ($row2[statusid] == 12) || ($row2[statusid] == 13) ||
					  ($row2[statusid] == 28) || ($row2[statusid] == 29)) {
				$cancel_link = "<a href=\"transferman.php?command=cancel&orderid=$row2[order_id]\"><u><b>Cancel Order</u></b></a>";
				} else {
				$cancel_link = "<b>This order can not be cancelled</b></a>";
				}

$ret_sld = strtolower($row2[sld]);
$ret_tld = strtolower($row2[tld]);

include("include/header.php");?>
<tr>
  <td width="100%" valign="top" height="110"><table width="100%" border="0" valign="top" align="center" cellpadding="0" cellspacing="1" class="tableO1" id="table9">
      <tr valign="top">
        <td width="18%" valign="top" rowspan="2"><div align="left">
        <?php include("include/menu.php");?></div></td>
        <td width="34%" valign="top" align="center">&nbsp;</td>
        <td width="23%" valign="top" align="left" class="BasicText">        
        <td width="18%" valign="top" align="center"> <div align="left"></div>
        </td>
      </tr>
      <tr valign="top"> <span class=\"BasicText\"></span>
        <td colspan="3" rowspan="6" valign="top" align="center"><br>
          <br>
<table align="center" width="583" border="0" class="tableOO">
                     <tr class="tableOO">
                       <td colspan="3" class="titlepic"><div align="center"><span class="whiteheader">Status for order #:
                       <?=$row2[order_id];?>
                       </span></div></td>
                     </tr>
                     <tr class="tableOO">
                       <td width="282" align="center" class="BasicText"><div align="left"><strong>Date Created:</strong>
                         <?=$row2[create_date];?>
                       </div></td>
                       <td width="291" align="center" class="BasicText"><div align="left"><?=$resend_link;?> </div></td>
                     </tr>
  <tr>
    <td align=\"center\" class="BasicText"><div align="left"><b><u></u></b> <strong>Overall Order Status: 
        <?=$statusid;?>
    </strong> </div></td>
    <td align=\"center\" class="BasicText"><div align="left"><?=$resubmit_link;?></div></td>
  </tr>
  <tr>
    <td align=\"center\" class="BasicText">&nbsp;</td>
    <td align=\"center\" class="BasicText"><?=$cancel_link;?></td>
  </tr>
                   </table>
<br>
<table align="center" width="750" border="0" class="tableOO">	
                     <tr class="tableOO">
                       <td width="166" align="center" class="titlepic"><span class="whiteheader">Domain Name </span></td>
                       <td width="24" align="center" class="titlepic"><span class="whiteheader"></span></td>
                       <td width="415" align="center" class="titlepic"><span class="whiteheader">Current Status</span><span class="whiteheader"></span></td>
                     </tr>
  <tr>
    <td align="center"><b><span class="BasicText"><?php echo $ret_sld.".".$ret_tld;?></b></a></td>
    <td align="center"><b><span class="BasicText"><?php echo $action;?></span></b></td>
    <td align="center"><b><span class="BasicText"><?php echo $row[desc];?></span></b></td>
  </tr>
          </table>
<br>		      <p><center><a href="transferman.php"><img src="../images/btn_back.gif" border="0"></a></center></p>          </tr>
<?php include("include/footer.php");?>
    </table>    
