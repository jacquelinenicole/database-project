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
                    itemid = info.items[i].id;
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
											<button type="button" class="btn btn-info" onClick="gen_code(${itemid})">Request Discount</button>
											<button type="button" class="btn btn-success" onClick="addtoCart(${itemid}, 1)">Add to Cart</button>
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
                itemid = info.items[i].id;
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
									<button type="button" class="btn btn-info" onClick="gen_code(${itemid})">Request Discount</button>
									<button type="button" class="btn btn-success" onclick="addtoCart(${itemid}, 1)">Add to Cart</button>
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

function gen_code(itemid)
{
	var message = `{"itemid" : "${itemid}"}`;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function ()
	{
		if(this.readyState == 4 && this.status == 200)
		{
			console.log(this.responseText);
			var info = JSON.parse(this.responseText);
		}
	}
	xmlhttp.open("POST", "../../api/get_items.php", true);
	xmlhttp.setRequestHeader("Content-type", 'application/json; charset=UTF-8');
	xmlhttp.send();
}

function addtoCart(itemid, itemquantity)
{
    var message = `{"foo" : "addCart", "id" : "${itemid}", "quantity" : "${itemquantity}"}`;


    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        console.log(this.responseText);
        var info = JSON.parse(this.responseText);
    }
    xmlhttp.open("POST", "../../api/cart.php", true);
    xmlhttp.setRequestHeader("Content-type", 'application/json; charset=UTF-8');
    xmlhttp.send(message);
    
}

function displayShoppingCart(ContentPage) {
    var content = ``;

           
    var message = `{"foo" : "getCart"}`; //, "id" : "${itemid}", "quantity" : "${itemquantity}"}`;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        console.log(this.responseText);
        if (this.readyState == 4 && this.status == 200) {
            var info = JSON.parse(this.responseText);

            if (info.emptyCart) {
                content += `<div class="row justify-content-center" style = "margin-top: 10%;" >
                            <div class="card col-md-4" style="width: 18rem;">
								<div class="card-body">
									<p class="card-text">Your shopping cart is empty.</p>
									</div>
							</div></div>`;
            }
            else {
                var itemname, itemdesc, itemcost, itemimage;

                var firstItem = true;

                for (i in info.items) {
                    console.log(i);
                    itemid = info.items[i].id;
                    itemname = info.items[i].name;
                    itemdesc = info.items[i].desc;
                    itemquant = info.items[i].quantity;
                    itemcost = info.items[i].cost * itemquant;
                    itemimage = info.items[i].image;
                    
                    if (firstItem) {
                        content += `<div class="row justify-content-center" style = "margin-top: 10%;" >`;
                        firstItem = false;
                    }
                    else 
                        content += `<div class="row justify-content-center" >`;

                    content += `<div class="card" id="cart-full" style="width: 18rem;">
                                <img class="card-img-top" src="${itemimage}" alt="Pic of item">
                                    <div class="card-body">
                                        <p class="card-text">${itemname}</p>
                                        <p class="card-text">Quantity: ${itemquant}</p>
                                        <p class="card-text">$${itemcost}</p>
                                        <button type="button" class="btn btn-danger" onclick="removeItem()">Remove</button>
                                        <a href="./checkout.html"><button type="button" class="btn btn-success">Proceed to Checkout</button></a>
                                    </div>
                                </div>
                                <div class="card" id="discount" style="width: 18rem;">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="discount-field" placeholder="Enter your discount code">
                                        </div>
                                            <button type="button" class="btn btn-info" onclick="applyDiscount()">Apply discount</button>
                                     </div>
                                </div></div><br>`;
                    
                }
            }
            content += `</div>`;
            document.getElementById(ContentPage).innerHTML = content;

        }
    }
    xmlhttp.open("POST", "../../api/cart.php", true);
    xmlhttp.setRequestHeader("Content-type", 'application/json; charset=UTF-8');
    xmlhttp.send(message);

    return;
}
