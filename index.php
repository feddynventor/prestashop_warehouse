<?php
$password = "salvini";
$token = "pj7X8bwp3Xm9COef";
if (!isset($_COOKIE["warehouse_code"]) && isset($_POST["pass"]) && hash_hmac("sha256",$_POST["pass"],"salveSHdronzi") == hash_hmac("sha256",$password,"salveSHdronzi")) {
	setcookie("warehouse_code",hash_hmac("sha256",$_POST["pass"],"salveSHdronzi"),strtotime("+30 days"),"/");
	header("Refresh: 0");
}
if (!isset($_COOKIE["warehouse_code"]) || $_COOKIE["warehouse_code"] != hash_hmac("sha256",$password,"salveSHdronzi")){
	echo "<form action='index.php' method='post'><input name='pass'></input><input type='submit' value='Login'></form>";
	die();
}
?>

<html>
<head>
<title>Magazzino</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
img {
    heigth: 15%;
}
.float-container {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 3px solid #fff;
    padding: 5px;
}
.float-child {
    float: left;
    padding: 20px;
}
</style>
</head>
<script>
$(document).ready(function() {
	document.getElementById("ean").focus();
	update_tobuy();
});
function al(title,desc,type,more){
	document.getElementById("eModalText").innerHTML = desc
	document.getElementById("eModalLabel").innerHTML = title
	if (type==0){
		document.getElementById("dismiss").setAttribute("style","")
		document.getElementById("confirm").setAttribute("style","display:none")
	}else{
		document.getElementById("confirm").setAttribute("style","")
		document.getElementById("form_submit").setAttribute("onclick",more)
		document.getElementById("dismiss").setAttribute("style","display:none")
	}
	$("#eModal").modal("show")
}
function minus(qta) {
	var product;
	$.ajax({
		url:'commandhub.php',
		type: 'GET',
		data: { token: 'pj7X8bwp3Xm9COef', cmd: "get", ean: document.getElementById("ean").value },
		success: function (data) {
			console.log("get",JSON.parse(data))
			if (JSON.parse(data).found==0){
				al("Errore","Articolo non esistente, aggiungerlo manualmente o prova con un altro riferimento",0);return
			}
			if (JSON.parse(data).ret==0){
				al("Errore","Parametro mancante",0);return
			}
			product = data; //serve a wish_add e wish_newknown
			document.getElementById("prod_details_title").innerHTML = JSON.parse(data).data.name + " | "
			document.getElementById("prod_details_qta").innerHTML = "Quantità definitiva: "+(parseInt(JSON.parse(data).data.quantity)-Math.abs(qta))
			$.ajax({
				url:'commandhub.php',
				type: 'GET',
				data: { token: 'pj7X8bwp3Xm9COef', cmd: "quant", ean: document.getElementById("ean").value, q:qta },
				success: function (data) {
					console.log("quant",JSON.parse(data))
				}
			});
			$.ajax({
				url:'commandhub.php',
				type: 'GET',
				data: { token: 'pj7X8bwp3Xm9COef', cmd: "wish_check", product: JSON.parse(product), add:1 },
				success: function (data) {
					console.log("wish_check",data)
					if (JSON.parse(data).ret == 0){
						let form = `<input type="text" id="product_newlink" class="form-control">`;
						al("Nuovo articolo",form,1,"updateProductLink("+product+")")
					} else {
						//aggiornare la tabella
						update_tobuy();
					}
				}
			});
		},
		error: function (jqXhr, textStatus, errorMessage) {
			al("Errore","Errore grave del backend\n"+errorMessage,0)
		}
	});

};
function plus(qta) {
	$.ajax({
		url:'commandhub.php',
		type: 'GET',
		data: { token: 'pj7X8bwp3Xm9COef', cmd: "get", ean: document.getElementById("ean").value },
		success: function (data) {
			console.log(JSON.parse(data))
			if (JSON.parse(data).found==0){
				al("Errore","Articolo non esistente, aggiungerlo manualmente o prova con un altro riferimento",0);return
			}
			if (JSON.parse(data).ret==0){
				al("Errore","Parametro mancante",0);return
			}
			document.getElementById("prod_details_title").innerHTML = JSON.parse(data).data.name + " | "
			document.getElementById("prod_details_qta").innerHTML = "Quantità definitiva: "+(parseInt(JSON.parse(data).data.quantity)+Math.abs(qta))
			$.ajax({
				url:'commandhub.php',
				type: 'GET',
				data: { token: 'pj7X8bwp3Xm9COef', cmd: "quant", ean: document.getElementById("ean").value, q:qta },
				success: function (data) {
					console.log(JSON.parse(data))
				}
			});
			product = data; //serve a wish_check
			$.ajax({
				url:'commandhub.php',
				type: 'GET',
				data: { token: 'pj7X8bwp3Xm9COef', cmd: "wish_check", product: JSON.parse(product), add:0 },
				success: function (data) {
					console.log("wish_check",data)
					if (JSON.parse(data).ret == 0){
						let form = `<input type="text" id="product_newlink" class="form-control">`;
						al("Nuovo articolo",form,1,"updateProductLink("+product+")")
					} else {
						//aggiornare la tabella / no perche ho comprato, non serve
					}
				}
			});
		},
		error: function (jqXhr, textStatus, errorMessage) {
			al("Errore","Errore grave del backend\n"+errorMessage,0)
		}
	});
};
function updateProductLink(product_data){
	console.log("sallvewvwev",product_data);
//	console.log("sallvewvwev",JSON.parse(product_data));
	$.ajax({
		url:'commandhub.php',
		type: 'GET',
		data: { token: 'pj7X8bwp3Xm9COef', cmd: "wish_newknown", product: product_data.data.id_product, url: document.getElementById("product_newlink").value },
		success: function (data) {
			console.log("wish_newknown",JSON.parse(data));
			if (JSON.parse(data).ret == 1){
				$.ajax({
					url:'commandhub.php',
					type: 'GET',
					data: { token: 'pj7X8bwp3Xm9COef', cmd: "wish_check", product: product_data, add:1 },
					success: function (data) {
						console.log("wish_check",data)
						if (JSON.parse(data).ret == 0){
							let form = `<input type="text" id="product_newlink" class="form-control">`;
							al("Nuovo articolo",form,1,"updateProductLink("+product+")")
						} else {
							//aggiornare la tabella
							update_tobuy();
						}
					}
				});
			}
		},
	});
}
function handleScannerInputField(){
if (document.getElementById("ean").value.length == 13) {
	let product_name;
	$.ajax({
		url:'commandhub.php',
		type: 'GET',
		data: { token: 'pj7X8bwp3Xm9COef', cmd: "get", ean: document.getElementById("ean").value },
		success: function (data) {
			if (JSON.parse(data).found==0){
				al("Errore","Articolo non esistente, aggiungerlo manualmente o prova con un altro riferimento",0);return
			}
			if (JSON.parse(data).ret==0){
				al("Errore","Parametro mancante",0);return
			}
			product_name = JSON.parse(data).data.name

		let form = `<h5>`+product_name+`</h5><div class=\"form-group row\">
    <label for=\"staticEmail\" class=\"col-sm-2 col-form-label\">Quantità</label>
    <div class=\"col-sm-10\">
 <input id=\"qta\" class=\"form-control\" type=\"number\" value=\"1\"></input>
    </div>
  </div>`
				if (document.getElementById("optvendi").checked)
					al("Vendi",form,1,"minus(document.getElementById(\"qta\").value*-1)")
				else
					al("Compra",form,1,"plus(document.getElementById(\"qta\").value)")
			}
		});
	}
}
</script>
<body>
<center><img src="https://videoforyou.it/img/video-for-you-logo-1607072746.jpg"></center>
<div class="container">
	<div class="float-container">
		<div class="float-child">
			<input id="ean" tabindex=0 oninput="handleScannerInputField()" maxlength="13" class="form-control">
		</div>
		<div class="float-child">
			<button onclick="plus(1)" class="btn btn-outline-warning">Acquista</button>
		</div>
		<div class="float-child">
			<button onclick="minus(-1)" class="btn btn-outline-success">Vendi</button>
		</div>
	</div>
	<div id="prod_details" style="display:flex;justify-content:center;align-items:center;border:10px solix #0000FF"><h3 id="prod_details_title"></h3><br><h6 id="prod_details_qta"></h6></div>

	<div class="float-container">Modalità barcode reader:</div>
	<div class="float-container">
<div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn btn-secondary active">
    <input type="radio" name="options" id="optvendi" autocomplete="off" checked> Vendi
  </label>
  <label class="btn btn-secondary">
    <input type="radio" name="options" id="optcompra" autocomplete="off"> Compra
  </label>
</div>
	</div>
</div>
<div class="modal fade" id="eModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="eModalText" class="modal-body">
      </div>
      <div class="modal-footer" id="dismiss" style="">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
      </div>
      <div class="modal-footer" id="confirm" style="display:none">
        <button id="form_submit" type="button" class="btn btn-success" data-dismiss="modal">Conferma</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div><br>
<script>

function generateTableHead(table, data) {
  let thead = table.createTHead();
  let row = thead.insertRow();
  for (let key of data) {
    let th = document.createElement("th");
    let text = document.createTextNode(key);
      if (key=="id_product"){
        continue;
      }
      if (key=="Qta"){
        continue;
      }
      if (key=="OK"){
        continue;
      }
    th.appendChild(text);
    row.appendChild(th);
  }
}

function generateTable(table, data) {
  for (let element of data) {
    let row = table.insertRow();
    for (key in element) {
      let text = document.createTextNode(element[key]);
      let link = document.createElement("button");
      var click; //vede se il prodotto è già stato cliccato
      var id;
      if (key=="id_product"){
        continue;
      }
      if (key=="Qta"){
        continue;
      }
      if (key=="OK"){
        click = element[key]
        continue;
      }
      let cell = row.insertCell();
      if (key=="ID"){
        id = element[key]
      }
      link.setAttribute("id","wishbtn"+id);
      if (element[key].includes("http://") || element[key].includes("https://")){
        cell.appendChild(link);
        if (click=="0"){
          link.setAttribute("class","btn btn-warning");
          link.innerHTML = "Riordina";
        } else {
          link.setAttribute("class","btn btn-success");
          link.innerHTML = "Fatto";
        }
        link.setAttribute("onclick","uncheck("+id+",\""+element[key]+"\")");
//        cell.appendChild(text);
      }else{
        cell.appendChild(text);
      }
    }
  }
}

function update_tobuy(){
$("#tobuy tr").remove(); 

var xmlhttp = new XMLHttpRequest();
xmlhttp.open('GET', 'tobuy.php', true);
xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4) {
        if(xmlhttp.status == 200) {
            var obj = JSON.parse(xmlhttp.responseText);
            console.log(obj)

            let table = document.querySelector("table");
            generateTableHead(table, Object.keys(obj[0]));
            generateTable(table, obj);

         }
    }
};
xmlhttp.send(null);
}

function uncheck(idd,link){
	$.ajax({
		url:'commandhub.php',
		type: 'GET',
		data: { token: 'pj7X8bwp3Xm9COef', cmd: "wish_uncheck", id: idd },
		success: function (data) {
			console.log(JSON.parse(data))
			if (JSON.parse(data).ret==0){
				al("Errore","Errore query\n"+JSON.parse(data).query,0)
			} else {
				document.getElementById("wishbtn"+idd).setAttribute("class","btn btn-success")
				document.getElementById("wishbtn"+idd).innerHTML = "Fatto"
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {
			alert("Errore "+errorMessage);
		}
	});
	window.open(link,"_blank");
}
</script>
<table id="tobuy" class="table">
<!--<thead><tr><th scope="col">#</th><th scope="col">Articolo</th><th scope="col">Qta</th><th scope="col">URL</th></thead><thead></thead>-->
<tbody>
</tbody>
</table>
</body>
</html>
