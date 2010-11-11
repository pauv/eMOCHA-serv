function getFirst(str) {
	var pos = str.indexOf('_');
	if(pos > 0) {
		return str.substr(0, pos);
	}
	return str;
}
function getSkipFirst(str) {
	return str.substr(str.indexOf('_')+1);
}