webApp = angular.module('Enigmaster', [
	'ui.bootstrap',
	'ui.grid'
]).filter('clock', function() {
    return function(date) {
        function pad(n) {
            return (n<10)?"0"+n:n;
        }
        date = new Date(date);
        
        var s = date.getTime()/1000;
        var h = Math.floor(s/3600);
        var m = Math.floor(s/60);
        var s = Math.floor(s%60);
        return h+":"+pad(m)+":"+pad(s);
    };
});