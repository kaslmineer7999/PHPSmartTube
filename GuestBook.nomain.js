!function() {
	var pars = document.getElementsByClassName('gbentry');
	for(par in pars) {
		var tempRef = pars[par].getElementsByTagName('span')[0];
		var temp = tempRef.innerHTML;
		var newDate = new Date(temp * 1000).toGMTString();
		tempRef.innerHTML = newDate;
	};
}();
