<?php include 'files/header.php'; ?>
<?php include 'files/menu.php';
 
   $salehistory = $db->joinQuery('SELECT DISTINCT `customerid`,`memono`,`return_date` FROM `sell_return` WHERE `memono`="'.$_GET['invo'].'"')->fetch(PDO::FETCH_ASSOC);
   
   ?>
<div class="container">
   <div class="row">
      <div class="col-sm-12">
         <div class="page-title-box">
            <div class="btn-group pull-right">
               <ol class="breadcrumb hide-phone p-0 m-0">
                  <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                  <li class="breadcrumb-item"><a href="#">Products</a></li>
                  <li class="breadcrumb-item active">Products sale Return update</li>
               </ol>
            </div>
            <h4 class="page-title">Product Sale Return Update</h4>
         </div>
      </div>
   </div>
   <!-- end page title end breadcrumb -->
   <div class="row">
      <div class="col-12">
         <div class="card m-b-30">
            <div class="card-body">
               <div class="row">
                  <div class="col-12">
                     <div class="row">
                        <div class="col-6">
                           <address>
                              <p><strong>Customer Info:</strong><br>
                                 <?=$dm->getUserFullDetails($salehistory['customerid'])?>
                              </p>
                           </address>
                        </div>
                        <div class="col-6 text-right">
                           <address>
                              <strong>Shipment Details:</strong>
                              <br>
                              Invoice No: <?=$salehistory['memono']?><br>
                              <input type="hidden" name="memes" id="memes" value="<?=$salehistory['memono']?>">
                              Sale Date :  <?=date("Y-M-d",strtotime($salehistory['return_date']))?>
                              <input type="hidden" name="returndate" id="returndate" value="<?=$salehistory['return_date']?>">
                           </address>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="p-2">
                        <h3 class="panel-title font-20"><strong>Order summary</strong></h3>
                     </div>
                     <div class="table-responsive">
                        <table class="table">
                           <thead>
                              <tr>
                                 <td><strong>Item</strong></td>
                                 <td class="text-center"><strong>Price</strong></td>
                                 <td class="text-center"><strong>Quantity</strong></td>
                                 <td class="text-right"><strong>Totals</strong></td>
                              </tr>
                           </thead>
                           <tbody id="mycartlists">
                              <?php 
                                 $invoiceinfo = $db->selectAll('sell_return','`memono`= "'.$_GET['invo'].'"')->fetchAll();
                                 /*echo "<pre>";
                                 print_r($invoiceinfo);
                                 echo "</pre>";*/
                                 $sum =0;
                                 $weight = 0;
                                 $transport = 0;
                                 $cus= "";
                                 
                                 
                                 $i=0;
                                   foreach ($invoiceinfo as $inv) { $i++;
                                     $sum += ((int)$inv['price']*(int)$inv['quntity']);
                                     $weight = $inv['weight'];
                                     $transport = $inv['transport'];
                                     $cus =  $inv['customerid'];
                                     ?>
                              <tr class="domclass">
                                 <td contenteditable="true">

                                    <?=$fn->getProductName(trim($inv['productid']))?>
                                    <span id="proname" style="opacity: 0" ><?=$inv['productid']?></span>
                                 </td>
                                 <td class="text-center" contenteditable="true" id="price">
                                    <?=$inv['price']?>
                                 </td>
                                 <td class="text-center" contenteditable="true" id="quntity">
                                    <?=$inv['quntity']?>
                                 </td>
                                 <td class="text-right" >
                                    <?=((int)$inv['price']*(int)$inv['quntity'])?>
                                 </td>
                              </tr>
                              <?php
                                 }
                                 
                                 ?>  
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <!-- Use accounts sections  -->
                  <div class="col">
                  </div>
                  <div class="col">
                     <form method="post" id="allotherinfo">
                        <input type="hidden" name="custohiddenname" id="custohiddenname" value="<?=$cus?>">
                        <div class="form-group">
                           <label for="">Total</label>
                           <input type="text" class="form-control" id="subtotalbeforecommsion" name="subtotalbeforecommsion" value="<?=$sum?>">
                        </div>
                        <div class="form-group">
                           <label for="">Weight</label>
                           <input type="text" class="form-control" id="weightamount" name="weightamount" onblur="getValueF()" value="<?=$weight?>">
                        </div>
                        <div class="form-group">
                           <label for="">Transport</label>
                           <input type="text" class="form-control" id="transportamount" name="transportamount" onblur="getValueF()" value="<?=$transport?>">
                        </div>
                        <div class="form-group">
                           <label for="">Grand Total</label>
                           <input type="text" class="form-control" id="grandtotalaftercommision">
                        </div>
                        <button type="button" onclick="savereturninfo()"   class="btn btn-outline-primary">Update and Save </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end row -->
   </div>
</div>
<?php include 'files/footer.php'; ?>
<script type="text/javascript">
   function savereturninfo()
   {
       
   
            var obj = function(memo,prod,quntity,price,date){
                this.memo =  memo;
                this.prod  = prod;
                this.quntity = quntity;
                this.price = price;
                this.date = date;
            }
   
   
                var confirmlist = [];
              var tr = document.getElementsByClassName("domclass");
              if (tr.length === 0) 
              {
                msg("You have to add something to the list",'al');
              }else {


                alertify.confirm("Are you sure ?",function(ev)
                   {
                    ev.preventDefault();

                      $("tr.domclass").each(function(index, el) {
                          var memono  = $('#memes').val();
                          var product  = $(this).find('#proname').text();
                          var quantity  = $(this).find('#quntity').text();
                          var price  = $(this).find('#price').text();
                          var returndate = $("#returndate").val();
                         confirmlist.push(new obj(memono,product,quantity,price,returndate));
                   });
   
   
                   console.log(confirmlist);
   
                   $.ajax({
                     url: 'ajax/insert-sell-return.php?dclas='+JSON.stringify(confirmlist)+'&weight='+$("#weightamount").val()+'&transport='+$("#transportamount").val()+'&custo='+$("#custohiddenname").val()+'&update=true'
                   })
                   .done(function(res) {
                     console.log(res);
                     var res = JSON.parse(res);
                    //console.log(res);
                    res.forEach(function(item)
                   { 
              
                     if (Object.keys(item)[0]  === "success") 
                     {
                        msg(Object.values(item)[0],'su',1);
                        //window.location.reload();
                     }
                     else if (Object.keys(item)[0] === "error") 
                     {
                        msg(Object.values(item)[0],'err',1);

                     }
               
                   });
                    
                   })
                   .fail(function() {
                     console.log("error");
                   })
                   .always(function() {
                     console.log("complete");
                   });


                  },function(ev){
                    ev.preventDefault();

                    msg("You'v canceled",'err');

                  });
   
   
                                      
                   
              }
   
   
    }
</script>