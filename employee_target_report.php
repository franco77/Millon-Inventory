


<?php include 'files/header.php'; ?>
<?php include 'files/menu.php';

      /**  get the quantity of the product selled by the employee  */
      function gettropsale($proid,$employeid,$sdate,$enddate)
      {

           $sql = "SELECT  SUM(`quantity`) as totalquantity FROM `sell` WHERE `productid`='{$proid}' AND `sellby`='{$employeid}' AND `selldate` BETWEEN '{$sdate}' AND '{$enddate}'";
           $targetquantity = $GLOBALS['db']->joinQuery($sql)->fetch(PDO::FETCH_ASSOC);
           $sql1 = "SELECT  SUM(`quntity`) as returntotal FROM `sell_return` WHERE `productid`='{$proid}' AND `takenby` = '{$employeid}' AND `return_date` BETWEEN '{$sdate}' AND '{$enddate}'";
           $returnquantity =  $GLOBALS['db']->joinQuery($sql1)->fetch(PDO::FETCH_ASSOC);
           return $targetquantity['totalquantity']-$returnquantity['returntotal'];
      }
      /** get the total day between two days */
      function gettheday($sdate,$edate)
      {
          $date1 = new DateTime($sdate);
          $date2 = new DateTime($edate);
          $days  = $date2->diff($date1)->format('%a');
          return $days+1;
      }

        if (isset($_GET['runningupdate'])) 
        {
          $update = array(
            'approve' => "1" 
          );
          if ($db->update("target",$update,"autoid='".$_GET['runningupdate']."'")) {
                ?>
                <script>
                  window.location.href='employee_target_report.php';
                </script>
                <?php
             }
        }

      function expiration($enddate)
      {
           $date1 =  new DateTime($enddate);
           $today = new DateTime(date("Y-m-d"));
           if ($date1<$today) 
           {
             echo "<a class='btn btn-outline-danger' href='#'>Ended</a>";
           }
           else
           {
            echo "
            
            <a  href='#'><img src='assets/images/giphy.gif' height='50px' width='50px'> </a>";
           }
      }

?>


     <div class="container">
      <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="btn-group pull-right">
                                <ol class="breadcrumb hide-phone p-0 m-0">
                                  <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item"><a href="#">Report</a></li>
                                  <li class="breadcrumb-item active">Target Report</li>
                                </ol>
                            </div>
                            <h4 class="page-title"> Employee Target Report</h4>
                            
                        </div>
                    </div>
                </div>
                <!-- end page title end breadcrumb -->

      
                <!-- table start -->
                <div class="row">
       <div class="col">
         <div class="card card-body">
           <?php
            if (isset($_GET['paycommission'])) 
            {
                ?>
                  <form action="" method="post">
           <div class="form-group">
            <label for="">Date</label>
            <input type="text" class="form-control mydate"  name="paymentdate">
          </div>
          <div class="form-group">
            <label for="">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?=$_GET['amount']?>" min="<?=$_GET['amount']?>">
          </div>
          <div class="form-group">
            <button type="submit" name="paybtn" class="btn btn-outline-primary">Pay now <i class="fa fa-floppy-o"></i> </button>
          </div>
         </form>
         <?php

            if (isset($_POST['paybtn'])) 
            {
               $data = array(
                'paydate' =>  $_POST['paymentdate'], 
                'pament'  =>  $_POST['amount'], 
                'token'   => "comisn_paid" 
              );
              if ($db->update("target",$data,"autoid='".$_GET['paycommission']."'")) 
              {
                   ?>
                   <script>
                  window.location.href='employee_target_report.php';
                  </script>
                   <?php
              }
            }
         ?>
                <?php 
            }
            else
            {
               ?>
                 <form action="" method="post">
            <div class="row">
              <div class="col">
                <select class="form-control" name="employeeid">
                   <option value="">Select a marketing</option>
                  <?php 
                    //fetching only users who are given target
                  $ids = [];
                     $cat  =  $db->joinQuery("SELECT  `employee_id` FROM `target`")->fetchAll();
                     foreach ($cat as $c) {
                         array_push($ids, $c['employee_id']);
                     }
                $dd =  array_unique($ids); //removing duplicate value from the table
                      foreach ($dd as $k => $v) { ?>
                       <option value="<?=$v?>"><?=$fn->getUserName($v)?></option>
                   <?php  }
                    
                     ?>
                </select>
              </div>
              <div class="col"><input type="date" class="form-control" name="start"></div>
            <div class="col"><input type="date" class="form-control" name="to"></div>
              <div class="col">
                <button type="submit"  name="search" class="btn btn-outline-primary">Search <i class="fa fa-search"></i> </button></div>
            </div>
          </form>
               <?php 
            }

           ?>
       
         </div>
       </div>
     </div>

                <div class="row" style="margin-top:30px">
                 <div class="col-xl-12">
                  <div class="card card-body">
                    <?php

                        $sql = "SELECT * FROM `target`";
                        $i = 0;
                        $sum = 0;
                        if (isset($_POST['search'])) 
                        {

                          if (!empty($_POST['employeeid'])) 
                          {
                            $sql = "SELECT * FROM `target` WHERE `employee_id`='".$_POST['employeeid']."' ORDER BY autoid DESC";
                          }
                        }
                        $dataid = $db->joinQuery($sql)->fetchAll();

                    ?>
                    <table border="1" style="width: 100%" id="datatable">
                       <thead>
                         <tr>
                           <th rowspan="2">#</th>
                           <th rowspan="2">Product Name</th>
                           <th colspan="2">Quantity</th>
                           <th colspan="2">Amount</th>
                           <th rowspan="2">(%)</th>
                           <th rowspan="2">Status</th>
                         </tr>
                         <tr>
                           <td>Given</td>
                           <td>Fullfill</td>
                           <td>Given</td>
                           <td>Worth</td>
                         </tr>
                         
                       </thead>
                       <tbody>
                         <?php

                          foreach ($dataid as $did) 
                          {
                              $i++; 
            $totalday = gettheday($did['startdate'],$did['enddate']);
            $qunt =  gettropsale($did['brandid'],$did['employee_id'],$did['startdate'],$did['enddate']);
            $parcantage  = ($qunt/$did['quantity'])*100;
            $sum += ($did['commsion']/$did['quantity'])*$qunt;
            $emgets =   ($did['commsion']/$did['quantity'])*$qunt;
                            ?>
                            <tr>
                              <td><?=$i?></td>
                              <td><a href="stock-report.php?pro_id=<?=$did['brandid']?>"><?=$fn->getProductName($did['brandid'])?></a></td>
                              <td><?=$did['quantity']?></td>
                              <td><?=$qunt?></td>
                              <td><?=$did['commsion']?></td>
                              <td><?=($did['commsion']/$did['quantity'])*$qunt?></td>
                              <td>
                                   <div class="c100 dark p<?=$parcantage?>">
                                      <span><?=$parcantage?>%</span>
                                      <div class="slice">
                                        <div class="bar"></div>
                                        <div class="fill"></div>
                                      </div>
                                    </div>
                              </td>
                              <td>
                                <?php 

                                expiration($did['enddate']); 

                                  if ($did['token'] == "comisn_paid") 
                                  {
                                    echo '||<button href="#" class="btn btn-secondary">Paid</button>';
                                  }
                                  else
                                  {
                                   echo '<a class="btn btn-outline-info"  href="employee_target_report.php?paycommission='.$did['autoid'].'&amount='.$emgets.'">Pay</a>';
                                  }
                                  

                                ?> <br>
                              
                              <!-- The Modal -->

                              </td>
                             
                            </tr>
                            <?php 
                          }

                         ?>
                       </tbody>
                       <tr>
                         <td colspan="5" class="text-right">Total gets</td>
                         <td><?=$sum?></td>
                       </tr>
                    </table>
                  </div>
                  
                 </div>
                </div>
                
                
                
              </div>
           
<?php include 'files/footer.php'; ?>


