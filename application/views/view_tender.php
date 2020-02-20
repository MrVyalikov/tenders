
<?php
	include_once 'application/views/template/header.php';
?>
<body>
  <div ng-app="TenderApp">
 	 <div ng-controller="TenderController as tc">
	  <nav class='navbar navbar-default'>
		<div class='container-fluid'>
			<div class='navbar-header'>
				<a class='navbar-brand' href='/'>
					Тендеры
				</a>
			</div>
			<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
				<ul class="nav navbar-nav">
					<li><a href="#" ng-click="tc.openCreateModal()">Создать тендер</a></li>
				</ul>
			</div>
		</div>
	  </nav>
		<h1>Текущие тендеры</h1>
		<div ng-show="!tenderList.length" >Нет тендеров: создайте хотя-бы один тендер</div> 
		<table class="table" ng-show="tenderList.length > 0">
		  <thead>
		    <th>ID</th>
		    <th>Дата создания</th>
		    <th>Название</th>
		    <th>Код</th>
		    <th>Год</th>
		    <th>Действия</th>
		  </thead>
		  <tbody ng-repeat="tender in tenderList" >
		    <tr>
		      <td>{{tender.id}}</td>
		      <td>{{tender.creation_date}}</td>
		      <td>{{tender.name}}</td>
		      <td>{{tender.code}}</td>
		      <td>{{tender.year}}</td>
		      <td>
				 <button ng-click="tc.openEditModal(tender)">Редактировать</button>
				 <button ng-click="tc.openRemoveModal(tender)">Удалить</button>
			  </td>
		    </tr>
		  </tbody>
		</table>
	</div> 
	<div ng-controller="ModalCreateTenderController" ng-show="show">
		<div class="modal-container">
			<div class="modal-window">
			    <div class="modal-header">
			      Создание тендера
			    </div>
			    <div class="modal-body">
			    	<form name="EditTenderForm" novalidate ng-submit="submit(modalData)" >
						<div class='form-group'>
							<label for='name'>Название тендера</label>
							<input class='form-control' type="text" name='name' ng-model="modalData.name"></input>
							<label for='code'>Код тендера</label>
							<input class='form-control' type="text" name='code' ng-model="modalData.code"></input>
							<label for='year'>Год тендера</label>
							<input class='form-control' type="number" name='year' ng-model="modalData.year"></input>
						</div>
						{{error}}
						<br><br>
						<input type='submit' value='Добавить тендер' class='btn btn-primary'>
					</form>
					<br>
					<input type='submit' value='Отмена' class='btn btn-primary' ng-click="cancel()">
			    </div>
			</div>
		</div>
	</div>
	<div ng-controller="ModalEditTenderController" ng-show="show">
		<div class="modal-container">
			<div class="modal-window">
			    <div class="modal-header">
			      Редактирование тендера
			    </div>
			    <div class="modal-body">
			    	<form name="EditTenderForm" novalidate ng-submit="submit(modalData)" >
						<div class='form-group'>
							<label for='description'>Id тендера</label>
							<input class='form-control' name='id' ng-model="modalData.id" disabled></input>
							<label for='creation_date'>Дата создания тендера</label>
							<input class='form-control' type="text" name='creation_date' ng-model="modalData.creation_date" disabled></input>
							<label for='name'>Название тендера</label>
							<input class='form-control' type="text" name='name' ng-model="modalData.name"></input>
							<label for='code'>Код тендера</label>
							<input class='form-control' type="text" name='code' ng-model="modalData.code"></input>
							<label for='year'>Год тендера</label>
							<input class='form-control' type="number" name='year' ng-model="modalData.year"></input>
						</div>
						{{error}}
						<br><br>
						<input type='submit' value='Сохранить изменения' class='btn btn-primary'>
					</form>
					<br>
					<input type='submit' value='Отмена' class='btn btn-primary' ng-click="cancel()">
			    </div>
			</div>
		</div>
	</div>
	<div ng-controller="ModalRemoveTenderController" ng-show="show">
		<div class="modal-container">
			<div class="modal-window">
			    <div class="modal-header">
			      Удаление тендера
			    </div>
			    <p>Вы действительно ходите удалить тендер?</p>
			    <p>ID: <strong>{{modalData.id}}</strong></p>
			    <p>Название: <strong>{{modalData.name}}</strong></p>
			    <div class="modal-body horizontal">
					<input type='submit' value='Удалить' class='btn btn-warning' ng-click="removeTender(modalData)"> 
					<input type='submit' value='Отмена' class='btn btn-primary' ng-click="cancel()">
			    </div>
			    {{error}}
			</div>
		</div>
	</div>
</div>


<script>
	var app = angular.module('TenderApp', []);

	app.controller('TenderController', ['modalService', '$scope','$rootScope', '$http', function(modalService, $scope, $rootScope, $http)
	{
	  $scope.result="";		// Default value for the result
	  $scope.tenderList = [];
	  $scope.modalOn = modalService.modalOn;

	  this.updateList = function()
	  {
	  	$http.post("/tender/get_list").then( function (response) 
		{
		  $scope.tenderList = response.data.result;
		});
	  }

	  this.openCreateModal = function()
	  {
		modalService.openModal('CREATE');
	  };
	  
	  this.openEditModal = function(tender)
	  {
	  	tender.year = parseInt(tender.year); // convert to integer
		modalService.openModal('EDIT', tender);
	  };

	  this.openRemoveModal = function(tender)
	  {
		modalService.openModal('REMOVE', tender);
	  };


	  $scope.$on('UPDATE_LIST', this.updateList);
	  this.updateList();

	}]);

	app.controller('ModalCreateTenderController', ['modalService','$scope','$rootScope', '$http', function(modalService, $scope, $rootScope, $http)
	{
	    $scope.show = modalService.modalOn; // Flag to show or hide the modal
	    $scope.error = '';

	 	$scope.submit = function(modalData)
	 	{
	 		if(!modalData)
	 		{
	 			modalData = {name:"",code:"", year:-1};
	 		}
	 		if(!modalData.name) modalData.name = "";
	 		if(!modalData.code) modalData.code = "";
			if(!modalData.year) modalData.year = -1;

			var objData = JSON.stringify(modalData);
		  	$http.post("/tender/create", objData)
			.then( function (response) 
			{
				if(response.data.success == '1')
				{
					$rootScope.$broadcast('UPDATE_LIST'); // Broadcast the event
					modalService.close();	
				} 
				else 
				{

					$scope.error = response.data.error_text;
				}
			});
		}

		$scope.cancel = function()
		{
			modalService.close();
		}

	    $scope.$on('MODAL_OPEN_CREATE', function()
	    {
	    	$scope.show = modalService.modalOn;
	    	$scope.modalData = modalService.modalData;
	    });
	    
	    $scope.$on('MODAL_CLOSE_CREATE', function()
	    {
	    	
	    	$scope.show = modalService.modalOn;

	    });

 	 }]);

	app.controller('ModalEditTenderController', ['modalService','$scope','$rootScope', '$http', function(modalService, $scope, $rootScope, $http)
	{
	    $scope.show = modalService.modalOn; // Flag to show or hide the modal
	    $scope.error = '';

	 	$scope.submit = function(modalData)
	 	{
			var objData = JSON.stringify(modalData);
		  	$http.post("/tender/edit", objData)
			.then( function (response) 
			{
				if(response.data.success == '1')
				{
					$rootScope.$broadcast('UPDATE_LIST'); // Broadcast the event
					modalService.close();	
				} 
				else 
				{
					$scope.error = response.data.error_text;
				}
			});
		}

		$scope.cancel = function()
		{
			modalService.close();
		}

	    $scope.$on('MODAL_OPEN_EDIT', function()
	    {
	    	$scope.show = modalService.modalOn;
	    	$scope.modalData = modalService.modalData;
	    });
	    
	    $scope.$on('MODAL_CLOSE_EDIT', function()
	    {
	    	
	    	$scope.show = modalService.modalOn;

	    });

 	 }]);

	app.controller('ModalRemoveTenderController', ['modalService','$scope','$rootScope', '$http', function(modalService, $scope, $rootScope, $http)
	{
	    $scope.show = modalService.modalOn; // Flag to show or hide the modal
	    $scope.error = '';

	 	$scope.removeTender = function(modalData)
	 	{

			var objData = JSON.stringify(modalData);
		  	$http.post("/tender/remove", objData)
			.then( function (response) 
			{
				if(response.data.success == '1')
				{
					$rootScope.$broadcast('UPDATE_LIST'); // Broadcast the event
					modalService.close();	
				} 
				else 
				{
					$scope.error = response.data.error_text;
				}
			});
		}

		$scope.cancel = function()
		{
			modalService.close();
		}

	    $scope.$on('MODAL_OPEN_REMOVE', function()
	    {
	    	$scope.show = modalService.modalOn;
	    	$scope.modalData = modalService.modalData;
	    });
	    
	    $scope.$on('MODAL_CLOSE_REMOVE',function()
	    {
	    	
	    	$scope.show = modalService.modalOn;

	    });

 	 }]);

	app.service('modalService', ['$q','$rootScope', function($q, $rootScope)
	{   
		var ms = this;
	    ms.modalOn = false; // Flag to indicate if the modal is on or off. Close by default
	    ms.modalName = '';
	    ms.modalData = {};
	    ms.openModal = function(modal_name, data = null)
	    {
	      ms.modalName = modal_name;
	      ms.defer = $q.defer(); // We create a deferrer
	      ms.modalOn = true; // Flag the showing of the modal
	      ms.modalData = data;
	      $rootScope.$broadcast('MODAL_OPEN_' + modal_name); // Broadcast the message that the popup is open
	      return ms.defer.promise; // Return a promise to the calling function
	    };

	    ms.close = function(value)
	    {
	      ms.modalOn = false; // We flag the closing of the modal
	      $rootScope.$broadcast('MODAL_CLOSE_' + ms.modalName); // Broadcast the event
	    };

	    ms.returnValue = function(value)
	    {
	      ms.modalOn = false; // We flag the closing of the modal
	      $rootScope.$broadcast('MODAL_CLOSE_' + ms.modalName); // Broadcast the event
	      ms.defer.resolve(value); // Return the resolved value of the modal
	    };

	}]);
</script>