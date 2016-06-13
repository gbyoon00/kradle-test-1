angular.module('siTableExampleApp', [
  'siTable'
]);


angular.module('siTableExampleApp').directive('krInput', [ '$parse', function($parse) {

  return {
    priority : 2,
    restrict : 'A',
    compile : function(element) {
        element.on('compositionstart', function(e) {
            e.stopImmediatePropagation();
        });
    },
  };

}]);


angular.module('siTableExampleApp').controller('ExampleCtrl', function($scope, $http) {
  $scope.filter = {
    $: ''
  };
  $scope.params = {};

  var url = 'qna-list.json';
  $http.get(url).then(function(issues) {
    $scope.issues = issues.data;
  });

  $scope.view = function() {
    alert('view');
     window.location.href = '/qna-body.html' ;
  };

});

angular.module('siTableExampleApp').controller('RemoteCtrl', function($scope, $http) {
  var limit = 10;
  var url = 'https://api.github.com/repos/angular/angular.js/issues';

  $scope.params = {};

  function getData() {
    var offset = $scope.params.offset || 0;
    var sortBy = $scope.params.sortBy;
    var page = Math.floor(offset / limit) + 1;
    $http.get(url, {
      params: {
        'per_page': limit,
        'page': page,
        'order_by': sortBy
      }
    }).then(function(ret) {
      // Some extra logic to read the number of available pages from the
      // request response, and then calculate the total number of issues
      var maxPage = (/page=(\d+)&per_page=\d+>; rel="last"/).exec(ret.headers('Link'));
      if (maxPage !== null) {
        $scope.params.total = parseInt(maxPage[1], 10) * limit;
      }
      $scope.issues = ret.data;
    });
  }

  $scope.$watch('params.offset', getData);
  $scope.$watch('params.sortBy', getData, true);
});
