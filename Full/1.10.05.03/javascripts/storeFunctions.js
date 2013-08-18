function updateMenu(mytype, partid) {
	try {
		var i, myarray;
		var price, difference;

		var numKeys=PartIDs[mytype].length;
		
		if(numKeys == 0){
		        return // Uh, no parts?
		}
		else if(numKeys == 1){
			document.getElementById(mytype).checked = true;
			document.getElementById(mytype).className = 'customizedisabled';
		}
		else {			
			// Calculate differences
			for(var i = 0; i < PartIDs[mytype].length; i++) { //This returns empty in IE
	  			price = "";
				currentIterationPartID = PartIDs[mytype][i];
				
				if(currentIterationPartID != partid) {  // Don't calculate difference for selected item (the difference is 0)
					difference = Parts[mytype][currentIterationPartID] / priceMask - Parts[mytype][partid] / priceMask;
					if(difference > 0) price = "  [+" + format_number(difference, 2) + "]";
					else price = "  [" + format_number(difference, 2) + "]";	
				}
				document.getElementById('pd' + currentIterationPartID + mytype).innerHTML = price;
		  	}
		}
	}
	catch(er) {
	}
}

function startUpdate(parttype, partid) {
	updateMenu(parttype, partid);
	updatePrices(parttype, partid);
}

function updatePrices(mytype, partid) {
	var total = basePrice;
	var boxes = document.getElementsByTagName('input');
	
	for (var i = 0; i < boxes.length; i++) {
		var e = boxes[i];
		if (e.type == 'radio' && e.checked) {
			total += (Parts[e.id][parseInt(e.value)] / priceMask); // The element's id holds the type, and the value holds the partid. parseInt() just in case it becomes a string.
			e.parentNode.parentNode.className = 'customizePartRowSelected';
			
			if (document.getElementById('sliderSpan' + mytype)) {
				document.getElementById('sliderSpan' + mytype).innerHTML = PartNames[partid] + '<br />';
			}
		}
		if (e.type == 'radio' && e.checked != true) {
			e.parentNode.parentNode.className = 'customizePartRow';
		}
    }
	
	// Apply discount percentage
	discountPercentage = (baseDiscountPercentage / 100) * total;
	
	// Update displayed prices
	document.getElementById("PriceTop").innerHTML = currencySymbol + format_number((total - discountPercentage), 2);
	document.getElementById("PriceBottom").innerHTML = currencySymbol + format_number((total - discountPercentage), 2);
	document.getElementById("PriceSlideBase").innerHTML = currencySymbol + format_number((total + baseDiscount), 2);
	document.getElementById("PriceSlideDiscount").innerHTML = currencySymbol + format_number((baseDiscount + discountPercentage), 2);
	document.getElementById("PriceSlide").innerHTML = currencySymbol + format_number((total - discountPercentage), 2);
}

function checkMouseOut() {
	var boxes = document.getElementsByTagName('input');
	
	for (var i = 0; i < boxes.length; i++) {
		var e = boxes[i];
		if (e.type == 'radio') {
			if (e.checked) {
				e.parentNode.parentNode.className = "customizePartRowSelected";
			}
			else{
				e.parentNode.parentNode.className = "customizePartRow";
			}
		}
	}
}

function format_number(pnumber,decimals) {
    if (isNaN(pnumber)) { return 0};
    if (pnumber=='') { return 0};
    var snum = new String(pnumber);
    var sec = snum.split('.');
    var whole = parseFloat(sec[0]);
    var result = '';
    if(sec.length > 1){
        var dec = new String(sec[1]);
        dec = String(parseFloat(sec[1])/Math.pow(10,(dec.length - decimals)));
        dec = String(whole + Math.round(parseFloat(dec))/Math.pow(10,decimals));
        var dot = dec.indexOf('.');
        if(dot == -1){
            dec += '.'; 
            dot = dec.indexOf('.');
        }
        while(dec.length <= dot + decimals) { dec += '0'; }
        result = dec;
    } else{
        var dot;
        var dec = new String(whole);
        dec += '.';
        dot = dec.indexOf('.');        
        while(dec.length <= dot + decimals) { dec += '0'; }
        result = dec;
    }    
    return result;
}

function isNumber(x) { 
	return ( (typeof x === typeof 1) && (null !== x) && isFinite(x) );
}
