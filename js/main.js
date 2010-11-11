function setStatus(tStatus, tMsg) {
	$("#status").removeClass("st_OK st_ERR").addClass("st_" + tStatus).text(tMsg || ".");
}
