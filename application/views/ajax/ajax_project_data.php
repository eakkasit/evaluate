<div class="tabbable">
  <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
    <li class="active">
      <a data-toggle="tab" href="#project">โครงการ </a>
    </li>

    <li>
      <a data-toggle="tab" href="#bsc">BSC </a>
    </li>

    <li>
      <a data-toggle="tab" href="#activity">กิจกรรม </a>
    </li>

    <li>
      <a data-toggle="tab" href="#other">อื่นๆ </a>
    </li>
  </ul>

  <div class="tab-content">
    <div id="project" class="tab-pane in active">
        <div class="table-responsive" style="max-height:230px">
         <table role="grid" id="table-example"
              class="table table-bordered table-hover dataTable no-footer">
           <thead>
           <tr role="row">
             <th class="text-center" width="7%">
               <input type="checkbox" id="checkAll"/>
             </th>
             <th class="text-center" width="43%">โครงการ</th>
           </tr>
           </thead>
           <tbody>
           <?php
           // $array_tmp_checked = array();
           // if (isset($users_list_selected) && !empty($users_list_selected)) {
           //   foreach ($users_list_selected as $user) {
           //     $array_tmp_checked[$user->user_id] = $user->user_id;
           //   }
           // }
           $array_tmp = array();
           if (isset($project) && !empty($project)) {
             foreach ($project as $key => $project_name) {
               ?>
               <tr class="odd" role="row">
                 <td class="text-center">
                   <input type="checkbox" name="project[]" class="checkChild" value="<?php echo $key; ?>" <?php echo in_array($key,$project_checked)?'checked':'';?> />
                 </td>
                 <td class="text-left">
                   <?php echo $project_name; ?>
                 </td>
               </tr>
               <?php
             }
           } else {
             ?>
             <tr class="odd" role="row">
               <td class="text-center" colspan="3">ไม่มีข้อมูลโครงการ</td>
             </tr>
           <?php } ?>
           </tbody>
         </table>
         <?php
         // if (isset($users_list_selected) && !empty($users_list_selected)) {
         //   foreach ($users_list_selected as $user) {
         //     if (isset($array_tmp[$user->user_id])) continue;
             ?>
             <!-- <input type="hidden" name="project[]" class="hiddenChild" value="<?php echo $user->user_id; ?>"/> -->
             <?php
         //   }
         // }
         ?>
     </div>
    </div>

    <div id="bsc" class="tab-pane">
      <div class="table-responsive">
       <table role="grid" id="table-example"
            class="table table-bordered table-hover dataTable no-footer">
         <thead>
         <tr role="row">
           <th class="text-center" width="7%">
             <input type="checkbox" id="checkAll"/>
           </th>
           <th class="text-center" width="43%">โครงการ</th>
         </tr>
         </thead>
         <tbody>
         <?php
         // $array_tmp_checked = array();
         // if (isset($users_list_selected) && !empty($users_list_selected)) {
         //   foreach ($users_list_selected as $user) {
         //     $array_tmp_checked[$user->user_id] = $user->user_id;
         //   }
         // }
         $array_tmp = array();
         if (isset($bsc) && !empty($bsc)) {
           foreach ($bsc as $key => $bsc_name) {
             ?>
             <tr class="odd" role="row">
               <td class="text-center">
                 <input type="checkbox" name="bsc[]" class="checkChild" value="<?php echo $key; ?>" <?php echo in_array($key,$bsc_checked)?'checked':'';?> />
               </td>
               <td class="text-left">
                 <?php echo $bsc_name; ?>
               </td>
             </tr>
             <?php
           }
         } else {
           ?>
           <tr class="odd" role="row">
             <td class="text-center" colspan="3">ไม่มีข้อมูล</td>
           </tr>
         <?php } ?>
         </tbody>
       </table>
       <?php
       // if (isset($users_list_selected) && !empty($users_list_selected)) {
       //   foreach ($users_list_selected as $user) {
       //     if (isset($array_tmp[$user->user_id])) continue;
       //     ?>
            <!-- <input type="hidden" name="bsc[]" class="hiddenChild" value="<?php //echo $user->user_id; ?>"/> -->
            <?php
       //   }
       // }
       ?>
   </div>
    </div>

    <div id="activity" class="tab-pane">
      <div class="table-responsive">
       <table role="grid" id="table-example"
            class="table table-bordered table-hover dataTable no-footer">
         <thead>
         <tr role="row">
           <th class="text-center" width="7%">
             <input type="checkbox" id="checkAll"/>
           </th>
           <th class="text-center" width="43%">โครงการ</th>
         </tr>
         </thead>
         <tbody>
         <?php
         // $array_tmp_checked = array();
         // if (isset($users_list_selected) && !empty($users_list_selected)) {
         //   foreach ($users_list_selected as $user) {
         //     $array_tmp_checked[$user->user_id] = $user->user_id;
         //   }
         // }
         $array_tmp = array();
         if (isset($task) && !empty($task)) {
           foreach ($task as $key => $task_name) {
             ?>
             <tr class="odd" role="row">
               <td class="text-center">
                 <input type="checkbox" name="activity[]" class="checkChild" value="<?php echo $key; ?>" <?php echo in_array($key,$task_checked)?'checked':'' ?> />
               </td>
               <td class="text-left">
                 <?php echo $task_name; ?>
               </td>
             </tr>
             <?php
           }
         } else {
           ?>
           <tr class="odd" role="row">
             <td class="text-center" colspan="3">ไม่มีข้อมูล</td>
           </tr>
         <?php } ?>
         </tbody>
       </table>
       <?php
       // if (isset($users_list_selected) && !empty($users_list_selected)) {
       //   foreach ($users_list_selected as $user) {
       //     if (isset($array_tmp[$user->user_id])) continue;
           ?>
           <!-- <input type="hidden" name="activity[]" class="hiddenChild" value="<?php //echo $user->user_id; ?>"/> -->
           <?php
       //   }
       // }
       ?>
   </div>
    </div>
    <div id="other" class="tab-pane">
      <textarea class="form-control" name="other" cols="4" rows="5"><?php echo $other_data; ?></textarea>
    </div>
  </div>
</div>
