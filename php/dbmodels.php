



<?php

		require_once("dboperation.php");
		require_once("functions.php");
		



		class DbModels extends Db
		{
			private $CategoryTableName = "cateogory";
			private $brandTableName = "p_brand";
			private $sizeTableName = "p_size";
			private $productTableName = "product_info";
			private $userTableName = "users";
			private $emplyeeTypeTable = "employeetype";


			private $brands = [];
			private $size = [];
			private $products = [];
			
			function __construct()
			{
				parent::__construct();
			}

			public function getRawData($table)
			{
				return $this->selectAll($table);
			}
			

			public function getCategories()
			{
			    $datas = $this->getRawData($this->CategoryTableName)->fetchAll();
			    foreach ($datas as $dt)
			     {
			    	 echo "<option value='".$dt['cat_id']."'>".$dt['cat_name']."</option>";
			    }
			}

			public function getEmployeeType()
			{
			    $datas = $this->getRawData($this->emplyeeTypeTable)->fetchAll();
			    foreach ($datas as $dt)
			     {
			    	 echo "<option value='".$dt['et_id']."'>".$dt['name']."</option>";
			    }
			}
			public function getUsersByRole($roleid)
			{
			    $datas = $this->selectAll($this->userTableName,"user_role='".$roleid."'")->fetchAll();
			    foreach ($datas as $dt)
			     {
			    	 echo "<option value='".$dt['u_id']."'>".$dt['name']."</option>";
			    }
			}

			public function getUsersByR($roleid)
			{
			    $datas = $this->selectAll($this->userTableName,"user_role='".$roleid."'")->fetchAll();
			    $myarray = [];
			    foreach ($datas as $dt)
			     {
			    	array_push($myarray, [
			    		"label"=>$dt['name'],
			    		"value"=>$dt['u_id']
			    	]);
			    }
			    return $myarray;
			}

			
			public function getBrands()
			{
				$datas = $this->getRawData($this->brandTableName)->fetchAll();
			    foreach ($datas as $dt)
			     {
			     	
			    	 echo "<option value='".$dt['brand_id']."'>".$dt['brand_name']."</option>";
			    }
			}

			public function brandsList()
			{
				$datas = $this->getRawData($this->brandTableName)->fetchAll();
				foreach ($datas as $dt)
			     {
			     	$this->brands[$dt['brand_id']] = $dt['brand_name'];
			    	 
			    }
			}


			public function getSizes()
			{
				$datas = $this->getRawData($this->sizeTableName)->fetchAll();
				foreach ($datas as $dt)
			     {
			     	$this->size[$dt['pro_size_id']] = $dt['pro_size_name'];
			    	 
			    }

			}

			public function products()
			{
				$datas = $this->getRawData($this->productTableName)->fetchAll();
				$fn = new Functions();
				foreach ($datas as $dt)
			     {
			     $this->products[$dt['pro_id']] = $fn->getProductName($dt['pro_id']);
			    	 
			    }
			}

			public function getBrandListByIds()
			{
				 $this->brandsList();
				return $this->brands;
			}

			public function getSizeListByIds()
			{
				$this->getSizes();
				return $this->size;
			}

			public function getProListByIds()
			{
				$this->products();
				return $this->products;
			}



			public function getUserFullDetails($uid)
			{
				$users =  $this->selectAll("users","u_id='".$uid."'")->fetch(PDO::FETCH_ASSOC);
				return "<b>Name </b>   : ".$users['name']."</br>
						<b>Phone  </b> : ".$users['contact_number']."</br>
						<b>Address</b> : ".$users['address'].".";
			}




			
           



		}



?>