//The mainContentPage is the content id we are using to rewrite everything
function getSearchResults(searchTextId, mainContentPage) {
	var i, content ="<div style='margin-top: 10%;'>";
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function ()
	{
		if(this.readyState == 4 && this.status == 200)
		{
			var info = JSON.parse(this.responseText);
			var itemname, itemdesc, itemcost, itemimage;
			for(i in info.items)
			{
				if(info.items[i].name.toLowerCase() === document.getElementById(searchTextId).value.toLowerCase()) 
				{
					itemname = info.items[i].name;
					itemdesc = info.items[i].desc;
					itemcost = info.items[i].cost;
					itemimage =info.items[i].image;
						content += `<div class="card col-md-4" style="width: 18rem;">
										<img class="card-img-top" src="..." alt="Image name: ${itemimage}">
											<div class="card-body">
											<p class="card-text"><b>${itemname}</b></p>
											<p class="card-text">${itemdesc}</p>
											<p class="card-text">$${itemcost}</p>
											<button type="button" class="btn btn-info">Request Discount</button>
											<button type="button" class="btn btn-success">Add to Cart</button>
										</div>
									</div>`;
				}
			}
			document.getElementById(mainContentPage).innerHTML = content;
		}
	}
	xmlhttp.open("POST", "../../api/get_items.php", true);
	xmlhttp.setRequestHeader("Content-type", 'application/json; charset=UTF-8');
	xmlhttp.send();
}