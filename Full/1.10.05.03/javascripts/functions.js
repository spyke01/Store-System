/*-------------------------------------------------------------------------*/
// General Functions
/*-------------------------------------------------------------------------*/	
function confirmDelete(text) {
    return confirm("Are you sure you want to delete this "+ text +"?");
}

function fetchItem(itemID) {
	if (document.getElementById) { return document.getElementById(itemID); }
	else if (document.all) { return document.all[itemID]; }
	else if (document.layers) { return document.layers[itemID]; }
	else { return null; }
}

function sqr_show_hide(id) {
	var item = fetchItem(id)

	if (item && item.style) {
		if (item.style.display == "none") {
			item.style.display = "";
		}
		else {
			item.style.display = "none";
		}
	}
	else if (item) {
		item.visibility = "show";
	}
}

function sqr_show(id) {
	var item = fetchItem(id)

	if (item && item.style) {
		item.style.display = "";
	}
	else if (item) {
		item.visibility = "show";
	}
}

function sqr_hide(id) {
	var item = fetchItem(id)

	item.style.display = "none";
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.0
	var p,i,x; if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function sqr_show_hide_with_img(itemID) {
	obj = fetchItem('slideDiv' + itemID);
	img = fetchItem('slideImg' + itemID);

	if (!obj) {
		// nothing to collapse!
		if (img) {
			// hide the clicky image if there is one
			img.style.display = 'none';
		}
		return false;
	}
	else {
		if (obj.style.display == 'none') {
			obj.style.display = '';
			if (img) {
				img_re = new RegExp("_collapsed\\.jpg$");
				img.src = img.src.replace(img_re, '.jpg');
			}
		}
		else {
			obj.style.display = 'none';
			if (img) {
				img_re = new RegExp("\\.jpg$");
				img.src = img.src.replace(img_re, '_collapsed.jpg');
			}
		}
	}
	return false;
}

function more_info_win(id, place) {
	newWindow = window.open('moreinfo.php?id=' + id + '#'+place, 'MoreInfo', 'height=570,width=715,status=yes, scrollbars=yes,toolbar=no,menubar=no,location=no');
}

/*-------------------------------------------------------------------------*/
// Ajax Functions
/*-------------------------------------------------------------------------*/	
function ajaxDeleteNotifier(spinDivID, action, text, row) {
    if (confirm("Are you sure you want to delete this "+ text +"?")) {
		sqr_show_hide(spinDivID);
		new Ajax.Request(action, {asynchronous:true, onSuccess:function(){ new Effect.SlideUp(row);}});
	}
}

function ajaxModelPicker() {
	sqr_show_hide('modelPickerSpinner');
	new Ajax.Updater('updateMe', 'ajax.php?action=searchparts&id=' + document.searchPartsForm.model.options[document.searchPartsForm.model.selectedIndex].value, {asynchronous:true, onSuccess:function(){ sqr_show_hide('modelPickerSpinner'); }});
}

function ajaxDeleteSystemNotifier(systemid, orderid, action, text, row) {
    if (confirm("Are you sure you want to delete this "+ text +"?")) {
		sqr_show_hide(systemid + 'SystemSpinner');
		sqr_show_hide(orderid + 'itemsTotalSpinner');
		sqr_show_hide(orderid + 'taxSpinner');
		sqr_show_hide(orderid + 'priceSpinner');
		sqr_show_hide(orderid + 'shippingFeeSpinner');
		shippingChoiceID = fetchItem('shipping');
		new Ajax.Request(action, {asynchronous:true, onSuccess:function(){ new Effect.SlideUp(row);}});
		new Ajax.Updater('subtotalContainer', 'ajax.php?action=getitem&table=orders&item=items_total&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'itemsTotalSpinner'); }});
		new Ajax.Updater('taxContainer', 'ajax.php?action=getitem&table=orders&item=tax&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'taxSpinner'); }});
		new Ajax.Updater('priceContainer', 'ajax.php?action=getitem&table=orders&item=price&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'priceSpinner'); }});
		new Ajax.Updater('shippingFeeContainer', 'ajax.php?action=calculateShipping&id=' + orderid + '&shippingChoiceID=' + shippingChoiceID.options[shippingChoiceID.selectedIndex], {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'shippingFeeSpinner'); }});
	}
}

function ajaxUpdateOrderTotals(orderid, systemid) {
	sqr_show_hide(systemid + 'itemsSpinner');
	new Ajax.Request('ajax.php?action=reCalculateOrderPrices&id=' + orderid, {asynchronous:true});
	new Ajax.Updater(systemid + 'itemsContainer', 'ajax.php?action=getitem&table=systems&item=total_price&id=' + systemid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(systemid + 'itemsSpinner'); }});
	ajaxUpdateCartInfo(orderid);
}

function ajaxUpdateCartInfo(orderid) {
	sqr_show_hide(orderid + 'itemsTotalSpinner');
	sqr_show_hide(orderid + 'taxSpinner');
	sqr_show_hide(orderid + 'priceSpinner');
	sqr_show_hide(orderid + 'shippingFeeSpinner');
	new Ajax.Updater('subtotalContainer', 'ajax.php?action=getitem&table=orders&item=items_total&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'itemsTotalSpinner'); }});
	new Ajax.Updater('taxContainer', 'ajax.php?action=getitem&table=orders&item=tax&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'taxSpinner'); }});
	new Ajax.Updater('priceContainer', 'ajax.php?action=getitem&table=orders&item=price&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'priceSpinner'); }});
	new Ajax.Updater('shippingFeeContainer', 'ajax.php?action=calculateShipping&id=' + orderid + '&shippingChoiceID=' + shippingChoiceID, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'shippingFeeSpinner'); }});
}

function ajaxUpdateShippingInfo(orderid, shippingChoiceID) {
	sqr_show_hide(orderid + 'shippingFeeSpinner');
	sqr_show_hide(orderid + 'priceSpinner');
	new Ajax.Updater('shippingFeeContainer', 'ajax.php?action=calculateShipping&id=' + orderid + '&shippingChoiceID=' + shippingChoiceID, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'shippingFeeSpinner'); }});
	new Ajax.Updater('priceContainer', 'ajax.php?action=getitem&table=orders&item=price&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'priceSpinner'); }});
}

function ajaxCalculateShippingInfo(orderid, zipCode, shippingChoiceID) {
	sqr_show_hide(orderid + 'shippingFeeSpinner');
	sqr_show_hide(orderid + 'priceSpinner');
	new Ajax.Request('ajax.php?action=calculateShippingFromZipCode&zipCode=' + zipCode, {asynchronous:true});
	new Ajax.Updater('shippingFeeContainer', 'ajax.php?action=calculateShipping&id=' + orderid + '&shippingChoiceID=' + shippingChoiceID, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'shippingFeeSpinner'); }});
	new Ajax.Updater('priceContainer', 'ajax.php?action=getitem&table=orders&item=price&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'priceSpinner'); }});
}

function ajaxUpdateRushFee(orderid, processingChoiceID) {
	sqr_show_hide(orderid + 'rushFeeSpinner');
	sqr_show_hide(orderid + 'priceSpinner');
	new Ajax.Updater('rushFeeContainer', 'ajax.php?action=calculateRushFee&id=' + orderid + '&processingChoiceID=' + processingChoiceID, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'rushFeeSpinner'); }});
	new Ajax.Updater('priceContainer', 'ajax.php?action=getitem&table=orders&item=price&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'priceSpinner'); }});
}

function ajaxAddOrderCoupon(orderid, couponCode) {
	sqr_show(orderid + 'couponRow');
	sqr_show_hide(orderid + 'couponSpinner');
	sqr_show_hide(orderid + 'taxSpinner');
	sqr_show_hide(orderid + 'priceSpinner');
	new Ajax.Updater('couponContainer', 'ajax.php?action=addCoupon&id=' + orderid + '&value=' + couponCode, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'couponSpinner'); }});
	new Ajax.Updater('couponResponse', 'ajax.php?action=couponExists&id=' + orderid + '&value=' + couponCode, {asynchronous:true});
	new Ajax.Updater('taxContainer', 'ajax.php?action=getitem&table=orders&item=tax&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'taxSpinner'); }});
	new Ajax.Updater('priceContainer', 'ajax.php?action=getitem&table=orders&item=price&id=' + orderid, {asynchronous:true, onSuccess:function(){ sqr_show_hide(orderid + 'priceSpinner'); }});
}

function ajaxSubmitCreateUser(theForm, quoteID, updateParentTable) {
    sqr_show_hide('createUserFormSpinner');
	new Ajax.Updater('updateMe', 'ajax.php?action=submitCreateUser', {asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true, onSuccess:function(){ sqr_show_hide('createUserFormSpinner'); }});
	if (updateParentTable = '1') {
		self.parent.ajaxUpdateCreateUser(quoteID);
	}
}

function ajaxUpdateCreateUser(quoteID) {
	new Ajax.Updater('quoteOwnerListHolder', 'ajax.php?action=updateQuoteOwnerListHolder&&id=' + quoteID, {asynchronous:true, evalScripts:true});
	new Ajax.Updater('quoteEnteredByListHolder', 'ajax.php?action=updateQuoteEnteredByListHolder&&id=' + quoteID, {asynchronous:true, evalScripts:true});
}

function ajaxSubmitUserPanelUserAddressEdit(theForm, updateParentTable, userID) {
    sqr_show_hide('userPanelUserAddressEditSpinner');
	new Ajax.Updater('updateMe', 'ajax.php?action=submitUserPanelUserAddressEdit&id=' + userID, {asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true, onSuccess:function(){ sqr_show_hide('userPanelUserAddressEditSpinner'); }});
	if (updateParentTable = '1') {
		self.parent.ajaxUpdateUserPanelUserAddresses();
	}
}

function ajaxUpdateUserPanelUserAddresses() {
	new Ajax.Updater('userBillAddressHolder', 'ajax.php?action=updateUserPanelUserAddressHolder&type=0&id=' + userID, {asynchronous:true, evalScripts:true});
	new Ajax.Updater('userShipAddressHolder', 'ajax.php?action=updateUserPanelUserAddressHolder&type=1&id=' + userID, {asynchronous:true, evalScripts:true});
}

function ajaxSubmitCreditCardEdit(theForm, updateParentTable, userID) {
    sqr_show_hide('creditCardEditSpinner');
	new Ajax.Updater('updateMe', 'ajax.php?action=submitCreditCardEdit&id=' + userID, {asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true, onSuccess:function(){ sqr_show_hide('creditCardEditSpinner'); }});
	if (updateParentTable = '1') {
		self.parent.ajaxUpdateCreditCardes();
	}
}

function deleteCreditCardInfo() {
    sqr_show_hide('creditCardEditSpinner');
	new Ajax.Updater('updateMe', 'ajax.php?action=deleteCreditCardInfo&id=' + userID, {asynchronous:true, evalScripts:true, onSuccess:function(){ sqr_show_hide('creditCardEditSpinner'); }});
	if (updateParentTable = '1') {
		self.parent.ajaxUpdateCreditCardes();
	}
}

function ajaxUpdateCreditCardes() {
	new Ajax.Updater('userCreditCardHolder', 'ajax.php?action=updateCreditCardHolder&id=' + userID, {asynchronous:true, evalScripts:true});
}

function ajaxShowHideSliderWithImg(itemID) {
	obj = fetchItem('slideDiv' + itemID);
	img = fetchItem('slideImg' + itemID);
	status = fetchItem('slideStatus' + itemID);

	if (!obj) {
		// nothing to collapse!
		if (img) {
			// hide the clicky image if there is one
			img.style.display = 'none';
		}
		return false;
	}
	else {
		if (status.value == '0') {
			new Effect.SlideDown('slideDiv' + itemID);
			status.value = '1';
			if (img) {
				img_re = new RegExp("_collapsed\\.jpg$");
				img.src = img.src.replace(img_re, '.jpg');
			}
		}
		else {
			new Effect.SlideUp('slideDiv' + itemID);
			status.value = '0';
			if (img) {
				img_re = new RegExp("\\.jpg$");
				img.src = img.src.replace(img_re, '_collapsed.jpg');
			}
		}
	}
	return false;
}

function moveModels(fromSelectName, toSelectName) {	
	fromSelect = document.forms[0][fromSelectName];
	toSelect = document.forms[0][toSelectName];										
	// Copy the items
	selectLen = fromSelect.options.length;
	i = 0;
	while (i < selectLen) {
		if (fromSelect.options[i].selected && fromSelect.options[i].text != "") {
			//move to the other form
			toSelect.options[toSelect.options.length] = new Option(fromSelect.options[i].text, fromSelect.options[i].value);
			// Delete the items
			fromSelect.options[i] = null;
			selectLen--;
		}
		else { i++; }
	}
				
	killBlankEntries(fromSelectName);
	killBlankEntries(toSelectName);								
	return false;
}

function killBlankEntries(killSelect) {	
	killSelect = document.forms[0][killSelect];	
	selectLen = killSelect.options.length;
	if (selectLen > 0) {
		i = 0;
		while (i < selectLen) {
			if (killSelect.options[i].value == "" || killSelect.options[i].text == "") {
				killSelect.options[i] = null;
				selectLen--;
			}
			else { i++; }
		}
	}
}
			
function selectAllItems(selectBoxName) {	
	selectBox = document.forms[0][selectBoxName];	
	for (i = 0; i < selectBox.options.length; i++) {
		selectBox.options[i].selected = true;
	}
}

function showStateDropBox(countryBox, addressType) {
	stateDropBoxRow = addressType + 'StateRow';
	stateDropBoxRow2 = addressType + 'StateRow2';
	
    if (countryBox.options[countryBox.selectedIndex].value == 'USA') {
		sqr_show(stateDropBoxRow);
		sqr_hide(stateDropBoxRow2);
	}
	else { 
		sqr_show(stateDropBoxRow2);
		sqr_hide(stateDropBoxRow);
	}
}

function sameAsBillingCheck(checkBoxItem) {
	Ship_first_name = fetchItem('Ship_first_name');
	Ship_last_name = fetchItem('Ship_last_name');
	Ship_company = fetchItem('Ship_company');
	Ship_street_1 = fetchItem('Ship_street_1');
	Ship_street_2 = fetchItem('Ship_street_2');
	Ship_city = fetchItem('Ship_city');
	Ship_country = fetchItem('Ship_country');
	Ship_state = fetchItem('Ship_state');
	Ship_state2 = fetchItem('Ship_state2');
	Ship_zip = fetchItem('Ship_zip');
	Ship_day_phone = fetchItem('Ship_day_phone');
	Ship_day_phone_ext = fetchItem('Ship_day_phone_ext');
	Ship_night_phone = fetchItem('Ship_night_phone');
	Ship_night_phone_ext = fetchItem('Ship_night_phone_ext');
	Ship_fax = fetchItem('Ship_fax');
		
	if (checkBoxItem.checked) {
		// Fill in the shipping section using the billing info
		Bill_first_name = fetchItem('Bill_first_name');
		Bill_last_name = fetchItem('Bill_last_name');
		Bill_company = fetchItem('Bill_company');
		Bill_first_name = fetchItem('Bill_first_name');
		Bill_last_name = fetchItem('Bill_last_name');
		Bill_street_1 = fetchItem('Bill_street_1');
		Bill_street_2 = fetchItem('Bill_street_2');
		Bill_city = fetchItem('Bill_city');
		Bill_country = fetchItem('Bill_country');
		Bill_state = fetchItem('Bill_state');
		Bill_state2 = fetchItem('Bill_state2');
		Bill_zip = fetchItem('Bill_zip');
		Bill_day_phone = fetchItem('Bill_day_phone');
		Bill_day_phone_ext = fetchItem('Bill_day_phone_ext');
		Bill_night_phone = fetchItem('Bill_night_phone');
		Bill_night_phone_ext = fetchItem('Bill_night_phone_ext');
		Bill_fax = fetchItem('Bill_fax');
		
		Ship_first_name.value = Bill_first_name.value;
		Ship_last_name.value = Bill_last_name.value;
		Ship_company.value = Bill_company.value;
		Ship_street_1.value = Bill_street_1.value;
		Ship_street_2.value = Bill_street_2.value;
		Ship_city.value = Bill_city.value;
		Ship_country.selectedIndex = Bill_country.selectedIndex;
		showStateDropBox(Ship_country, 'ship');
		Ship_state.selectedIndex = Bill_state.selectedIndex;
		Ship_state2.value = Bill_state2.value;
		Ship_zip.value = Bill_zip.value;
		Ship_day_phone.value = Bill_day_phone.value;
		Ship_day_phone_ext.value = Bill_day_phone_ext.value;
		Ship_night_phone.value = Bill_night_phone.value;
		Ship_night_phone_ext.value = Bill_night_phone_ext.value;
		Ship_fax.value = Bill_fax.value;													
	}
	else { 
		// Clear the shipping section
		Ship_first_name.value = "";
		Ship_last_name.value = "";
		Ship_company.value = "";
		Ship_street_1.value = "";
		Ship_street_2.value = "";
		Ship_city.value = "";
		Ship_zip.value = "";
		Ship_day_phone.value = "";
		Ship_day_phone_ext.value = "";
		Ship_night_phone.value = "";
		Ship_night_phone_ext.value = "";
		Ship_fax.value = "";
	}		
	return false;
}