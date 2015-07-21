(function() {
	var app = angular.module('testrest', []);

	app.controller('ObjectController', ['$http', function($http) {
		var ctrl = this;

		this.editClick = function(click_id) {
			ctrl.editObject = ctrl.objects.filter(function(object) {
				return object.id == click_id;
			})[0];
		};

		this.selectAllObjects = function() {
			$http({
				method: 'GET',
				url: 'data/object.php',
				data: '',
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.responseSelectAll = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.objects = data;
			})
			.error(function (data, status, headers, config) {
				ctrl.responseSelectAll = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.selectObjectById = function(id) {
			$http({
				method: 'GET',
				url: 'data/object.php?id=' + id,
				data: null,
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.responseSelect = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.viewObject = data;
			})
			.error(function (data, status, headers, config) {
				ctrl.responseSelect = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.insertObject = function(object) {
			$http({
				method: 'POST',
				url: 'data/object.php',
				data: object,
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.responseInsert = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.addObject = null;
				ctrl.selectAllObjects();
			})
			.error(function (data, status, headers, config) {
				ctrl.responseInsert = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.updateObject = function(object) {
			$http({
				method: 'PUT',
				url: 'data/object.php',
				data: object,
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.responseUpdate = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.editObject = null;
				ctrl.selectAllObjects();
			})
			.error(function (data, status, headers, config) {
				ctrl.responseUpdate = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.deleteObject = function(id) {
			$http({
				method: 'DELETE',
				url: 'data/object.php',
				data: {id: id},
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.responseDelete = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.removeObject = null;
				ctrl.selectAllObjects();
				ctrl.debugDelete = data;
			})
			.error(function (data, status, headers, config) {
				ctrl.responseDelete = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		//initialize list of objects
		this.selectAllObjects();
	}]);
})();