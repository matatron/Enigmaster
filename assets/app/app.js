webApp = angular.module('Enigmaster', [
	'ui.bootstrap',
	'ui.grid'
]).filter('clock', function() {
    return function(date) {
        var prefix = '';
        function pad(n) {
            return (n<10)?"0"+n:n;
        }
        date = new Date(date);
        date = date.getTime();
        if (date<0) {
            prefix = '-';
            date = -date;
        }
        var s = date/1000;
        var h = Math.floor(s/3600);
        var m = Math.floor(s/60)%60;
        var s = Math.round(s%60);
        return prefix+h+":"+pad(m)+":"+pad(s);
    };
});