(function() {
	var app = angular.module('testrest', []);

	app.controller('ObjectController', ['$http', function($http) {
		var ctrl = this;

		this.selectAllObjects = function() {
			$http({
				method: 'GET',
				url: 'data/object.php',
				data: '',
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.selectResponse = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.objects = data;
			})
			.error(function (data, status, headers, config) {
				ctrl.selectResponse = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.editClick = function(click_id) {
			ctrl.editObject = ctrl.objects.filter(function(object) {
				return object.id == click_id;
			})[0];
		};

		this.removeClick = function(click_id) {
			ctrl.removeObject = ctrl.objects.filter(function(object) {
				return object.id == click_id;
			})[0];
			ctrl.deleteObject(ctrl.removeObject)
		};

		this.insertObject = function(insertObject) {
			$http({
				method: 'POST',
				url: 'data/object.php',
				data: insertObject,
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.insertResponse = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.addObject = null;
				ctrl.selectAllObjects();
			})
			.error(function (data, status, headers, config) {
				ctrl.insertResponse = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.updateObject = function(updateObject) {
			$http({
				method: 'PUT',
				url: 'data/object.php',
				data: updateObject,
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.updateResponse = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.editObject = null;
				ctrl.selectAllObjects();
			})
			.error(function (data, status, headers, config) {
				ctrl.updateResponse = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		this.deleteObject = function(deleteObject) {
			$http({
				method: 'DELETE',
				url: 'data/object.php',
				data: deleteObject,
				header: {'Content-Type': 'application/c-www-form-urlencoded'}
			})
			.success(function (data, status, headers, config) {
				ctrl.deleteResponse = 'SUCCESS - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
				ctrl.removeObject = null;
				ctrl.selectAllObjects();
			})
			.error(function (data, status, headers, config) {
				ctrl.deleteResponse = 'ERROR - DATA: ' + data + '|STATUS: ' + status + '|HEADERS: ' + headers + '|CONFIG: ' + config;
			});
		};

		//initialize list of objects
		this.selectAllObjects();
	}]);
})();