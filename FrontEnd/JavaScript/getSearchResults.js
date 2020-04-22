//The mainContentPage is the content id we are using to rewrite everything
function getSearchResults(searchTextId, mainContentPage) {
	var i, content ="<div style='margin-top: 10%;'>";
	var xmlhttp = new XMLHttpRequest();
	console.log(searchTextId);
	console.log(mainContentPage);
	if((document.getElementById(searchTextId).value == "") && (mainContentPage == 'itempage_content'))
	{
		//console.log("search is empty and we are on the item page");
		displayAllProducts(mainContentPage);
		return;
	}
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
			content += "</div>";
			document.getElementById(mainContentPage).innerHTML = content;
		}
	}
	xmlhttp.open("POST", "../../api/get_items.php", true);
	xmlhttp.setRequestHeader("Content-type", 'application/json; charset=UTF-8');
	xmlhttp.send();
}


function displayAllProducts(ContentPage)
{
	var i, content ='<div class="row" style="margin-top: 10%;">';
					
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function ()
	{
		if(this.readyState == 4 && this.status == 200)
		{
			var info = JSON.parse(this.responseText);
			var itemname, itemdesc, itemcost, itemimage;
			for(i in info.items)
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
									<button type="button" class="btn btn-success" onclick="">Add to Cart</button>
								</div>
							</div>`;
			}
			content += "</div>";
			document.getElementById(ContentPage).innerHTML = content;
			
		}
	}
	xmlhttp.open("POST", "../../api/get_items.php", true);
	xmlhttp.setRequestHeader("Content-type", 'application/json; charset=UTF-8');
	xmlhttp.send();
	
	return;
}

function addtoCart($itemid, $itemquantity)
{
	
}