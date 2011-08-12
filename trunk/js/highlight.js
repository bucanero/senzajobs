// Table rows highlight functions

var OrigClass;

function setColor(RowNumber) {
  var Row = document.getElementById("row"+RowNumber);
  OrigClass = Row.className;
  Row.className = "highlight";
}

function origColor(RowNumber) {
  var Row = document.getElementById("row"+RowNumber);
  Row.className = OrigClass;
}

// Drop down menu functions

var timeout = 500;
var closetimer = 0;

function showdiv(myElem) {
	cancelclose();
	var ddm = document.getElementById(myElem);
	ddm.style.display="block";
}

function hidediv(myElem) {
	var ddm = document.getElementById(myElem);
	ddm.style.display="none";
}

function mclosetime() {
	closetimer = window.setTimeout("hidediv('dropdown')",timeout);
}

function cancelclose() {
		window.clearTimeout(closetimer);
		closetimer = null;
}

// AJAX functions

