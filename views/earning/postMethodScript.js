	var dbPost = angular.module('myApp',[]);	

	dbPost.controller('myController', function($scope,$http){
		$scope.btnName = 'insert';
		$scope.insertData = function(btnName){
			if(btnName=='insert')
			{
				$http.post("insert.php",{"name":$scope.name,"email":$scope.email,"password":$scope.password,"btn":'add'})
				.success(function(data,status,headers,config){
				if(data=='success'){
					alert('Record has been successfully saved');
					$scope.getRecords();
					//$scope.message = 'Record has been successfully saved';
					//window.location.reload(true);
					//console.log('data insert');
				}
				else{
					$scope.message = data;}
				});
			}

			if(btnName=='Update')
			{
				$http.post("insert.php",{"id":$scope.id,"name":$scope.name,"email":$scope.email,"password":$scope.password,"btn":'update'})
				.success(function(data,status,headers,config){
				if(data=='success'){
					alert('Record has been successfully update');
					$this.getRecords();
					//$scope.message = 'Record has been successfully saved';
					//window.location.reload(true);
					//console.log('data insert');
				}
				else{
					$scope.message = data;
				} });
			}
		}

		$scope.getRecords = function(){
			$http.get('insert.php?btn=getData')
			.success(function(data){
				$scope.DataRecord = data;
			});
		}	
	
		$scope.refresh =function(){
			window.location.reload(true);
		}

		var onSuccess=  function(data, status, headers, config){
			console.log(data);
			$scope.DataRecord = data;
		};

		var onError = function(data, status, headers, config)
		{
			console.log('record not found');
		};
		var pro = $http.get('insert.php?btn=getData').success(onSuccess).error(onError);

		$scope.deleteData=function(data){
			$http.post('insert.php',{'id':data,'btn':'dltData'})
			.success(function(data){
				alert(data);
				window.location.reload(true);
			});
		}

		$scope.editData=function(id,name,email){
			$scope.btnName = 'Update';
			$scope.id=id;
			$scope.name=name;
			$scope.email=email;
			$scope.password=email;
			$scope.dsBtn = true;
			// alert(email);
		}


});
	
	
