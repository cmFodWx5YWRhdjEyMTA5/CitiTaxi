
<?php 
    //echo "<pre>";
    //print_r($result);die();
 ?>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Registration</title>
      <style type="text/css">
         * { margin: 0; padding: 0; }
         body { font: 14px/1.4 'Open sence', sans-serif; }
         #page-wrap { width: 800px; margin: 0 auto; margin-top:20px; }
         table { border-collapse: collapse; }
         table td, table th { border: 1px solid black; padding: 8px;  }
         #header{  height: 15px;
         width: 100%;
         margin: 0px 0;
         background: #222;
         text-align: center;
         color: #73e2fb;
         font: bold 15px 'Open sence', sans-serif;
         letter-spacing: 5px;
         padding: 10px 0px;}
         #customer-title { font-size: 12px; font-weight: bold; float: left; }
         #meta { margin-top: 1px; width: 300px; float: right; }
         #meta td { text-align: right;  }
         #meta td.meta-head { text-align: left; background: #eee; }
         #meta td textarea { width: 100%; height: 20px; text-align: right; }
         #items { clear: both; width: 100%; margin: 10px 0 0 0; border: 1px solid black; }
         #items th { background: #eee; font-size: 12px; text-align: left; width:25%; }
         #items td { font-size: 12px; }
         #items textarea { width: 80px; height: 50px; }
         #items tr.item-row td { border: 0; vertical-align: top; }
         #items td.description { width: 400px; color:red; font-weight: bold }
         #items td.item-name { width: 175px; }
         #items td.total-line { border-right: 0; text-align: right; }
         #items td.total-value { border-left: 0; padding: 10px; }
         #items td.total-value textarea { height: 20px; background: none; }
         #items td.balance { background: #eee; }
         #items td.blank { border: 0; }
         #terms { text-align: center; margin: 20px 0 0 0; }
         #terms h5 {font: 13px Helvetica, Sans-Serif; letter-spacing: 1px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0;color: #1956b3;
         font-weight: bolder; }
         #terms textarea { width: 100%; text-align: center;}
         textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }
         .delete-wpr { position: relative; }
         .delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family:raleway; font-size: 12px; }
      </style>
   </head>
   <div id="page-wrap">
      <div id="header">New Registration</div>
      <div id="identity" align="center">                    
            <img src="<?php echo base_url('assest/forget_Image.png');?>" style="display:block;height:70px;padding:0px !important;" class="" width="100%" border="0"> 
      </div>
      <div style="clear:both"></div>
      <table id="items">
         <tbody>
         <?php if($result->user_type==1){ $userType='Technician';}
               if($result->user_type==0){$userType='Customer';}
               ?>      
            <tr>
               <th>Registration Type</td> 
               <td class="description"><?php echo $userType; ?></td>
            </tr>
            <tr>
               <th>Full Name</th>
               <!-- provider name -->
               <td colspan="3"><?php echo $result->name;?></td>
            </tr>
            <tr>
               <th>Contact Number</th>
               <td colspan="3"><?php echo $result->contact;?></td>
            </tr>
            <tr>
               <th>Email</th>
               <td colspan="3"><?php echo $result->email;?></td>
            </tr>
            <tr>
               <th>Password</th>
               <td colspan="3" style="color:red;"><?php echo $result->password;?></td>
            </tr>
            <tr>
               <th>address</th>
               <td colspan="3"><?php echo $result->address;?></td>
            </tr>

         </tbody>
      </table>
      <div id="terms">
         <h5> !!Thanks ClickAsa Team!! </h5>
      </div>
   </div>
</html>