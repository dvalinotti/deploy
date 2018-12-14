function bundle(){
	//debugger;
	var xhr = new XMLHttpRequest();
	var url = "clientBundle.php";
	var source = document.getElementById("source").value;
	var version = document.getElementById("version").value;
	
	var data = "source="+source+"&version="+version;
	xhr.open("POST",url, true);
	xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xhr.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200){
			response = this.responseText;
			endresult = JSON.parse(response);
			document.getElementById("output").innerHTML = "Campaign started"+" on "+subredditname+ " called " + title +" with post " +post +".";
		}
};
	xhr.send(data);
	
}