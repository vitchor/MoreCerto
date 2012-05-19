<style type="text/css">
	html,body {overflow:hidden;}
	.blue-button {
		cursor: pointer;
		background: -o-linear-gradient(top, #3294DB, #1C69A0);
		background: -moz-linear-gradient(top, #3294DB, #1C69A0);
		background: -webkit-gradient(linear, left top, left bottom, from(#3294DB), to(#1C69A0));
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#3294DB', endColorstr='#1C69A0');
		border: none;
		padding: 8px;
		font-size:14px;
		color: white;
		font-family: Georgia, "Times New Roman", Times, serif;
		font-style: italic;
		border-radius: 10px;
		font-weight: bold;
		display:inline-block;
		text-decoration: none;
	}
</style>
<body onload="changeUrl()">
	<a href="#" id="link" target="_blank" class="blue-button">Veja no MoreCerto</a>
</body>
<script type="text/javascript">
function getUrlVars()
{
	var url = (window.location != window.parent.location) ? document.referrer: document.location;
    var vars = [], hash;
    var hashes =url.toString().slice(url.toString().indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
function changeUrl(){
	var params= getUrlVars();
	document.getElementById("link").href="http://www.morecerto.com.br/realestates/ufsc/"+params["id"];
}
</script>
