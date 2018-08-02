<?php $data['page']='vehicle'; $data['title']='Weekly Reward'; $this->load->view('layout/header',$data);?> 

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Driver Weekly Reward</strong></h3>
                    <div class="btn-group pull-right">
                         <button type="button" class="btn btn-submit" onclick="addReward()"> Add Reward </button>   
                     </div>
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated">
                        <div class="panel-body panel-body-table">

                                    <div class="table-responsive">
                                        <div id="list_table" style="overflow:scroll;">
                                            <table id="example" class="table display">
                                                <thead>
                                                    <tr>
                                                        <th width="50">#</th>
                                                        <th>Trip Type</th>                                                        
                                                        <th>Country</th>
                                                        <th>City</th>
                                                        <th>Target Trip</th>
                                                        <th>Reward Unit</th>
                                                        <th>Reward rate</th>
                                                        <th>Status</th>
                                                        <th width="100">Edit</th>
                                                        <th width="100">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php $i=1; foreach ($rewards as $t =>$v) { ?>
                                                    <tr">
                                                        <td class="text-center"><?php echo $i++; ?></td>
                                                        <td><?php echo $v->reward_type; ?></td>
                                                        <td><?php echo $v->country; ?></td>
                                                        <td><?php echo $v->city; ?></td>
                                                        <td><strong><?php echo $v->weeklyTargetTrip; ?></strong></td>
                                                        <td><?php echo $v->reward_unit; ?></td>
                                                        <td><?php echo $v->reward_rate; ?></td>
                                                        <td style="color:<?php if($v->reward_status=='active'){ echo 'green';} else{echo 'red';} ?>"><strong><?php echo strtoupper($v->reward_status); ?></strong></td>
                                                        <td>
                                                            <a  href="<?php echo site_url('Driver/updateDriver_reward/'.$v->reward_id); ?>">
                                                            <button class="btn btn-default btn-rounded btn-sm"><span class="fa fa-pencil"></span>
                                                            </button></a>
                                                        </td>
                                                        <td>
                                                            <?php if($v->reward_status=='off'){ $status ='on';} else{$status='off';}?>

                                                            <input type='button' class="btn btn-danger btn-rounded btn-sm" onClick="changeStatus(<?php echo $v->reward_id;?>,<?php echo "'".$status."'";?>);" value="<?php echo $status; ?>">
                                                        </td>
                                                    </tr>
                                                    <?php } ?>                                                                              
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>               
                    </div>
                </div>
            </div>                    
        </div>

<?php $this->load->view('layout/second_footer');?>



<script>

    function addReward()
    {
      window.location.href="<?php echo site_url('Driver/addDriver_reward');?>";
    }

    function changeStatus(id,status)
    {
        $.ajax({
            type:'post',
            data:{'rewardid':id,'status':status},
            url:"<?php echo site_url('Vehicle/changeRewardStatus');?>",
            success:function(res)
            {
               alert(res);
                location.reload();
            }
        });
    }
</script>
