function SetDiagramPosition (sideToHug, extraPaddingSide, extraPaddingTop, extraPaddingSideIE, extraPaddingTopIE) {			
	var xPos;
	var yStart;
	var yPos;
	var yInc;
	
	// Make sure our numbers are not blank
	if (extraPaddingSide == '')
		extraPaddingSide = 0;
	if (extraPaddingTop == '')
		extraPaddingTop = 0;
	if (extraPaddingSideIE == '')
		extraPaddingSideIE = 0;
	if (extraPaddingTopIE == '')
		extraPaddingTopIE = 0;
	
	if (IsNetScapeBrowser ()) {
			// Get the width and height of our PriceBox_Layer
			var yWidth = document.getElementById ("PriceBox_Layer").clientWidth;
			var yHeight = document.getElementById ("PriceBox_Layer").clientHeight;
			
			// Determine our spacing from the side of the window
			xPos = 0 + extraPaddingSide;
			
			// Determine our spacing from the top of the window
			if (yHeight == 0)
				yHeight = 128;
			yPos = window.pageYOffset + window.innerHeight - yHeight - extraPaddingTop;
			
			// Do some fault handling
			if (xPos < 0)
				xPos = 0;
			if (yPos < 0)
				yPos = 0;
			
			yStart = document.getElementById("PriceBox_Layer").style.top;			
			yInc = Math.log(Math.pow(yPos - yStart, 3));
			
			if (yStart + yInc >= yPos)
				yStart = yPos;
			else if (yStart < yPos)	
				yStart += yInc;
			else
				yStart = yPos;		
			
			// Set our pricebox location
			document.getElementById("PriceBox_Layer").style.top = yStart;
			if (sideToHug == 'left')
				document.getElementById("PriceBox_Layer").style.left = xPos;
			else
				document.getElementById("PriceBox_Layer").style.right = xPos;
	}
	else {
			
			// Determine our spacing from the side of the window
			xPos = 0 + extraPaddingSideIE;
			
			// Determine our spacing from the top of the window		
			yPos = document.body.scrollTop + extraPaddingTopIE;
			
			// Do some fault handling
			if (xPos < 0)
				xPos = 0;
			if (yPos < 0)
				yPos = 0;
			
			yStart = PriceBox_Layer.style.pixelTop;		
			yInc = Math.log(Math.pow(yPos - yStart, 3));		
			if (yStart + yInc >= yPos)
				yStart = yPos;
			else if (yStart < yPos)	
				yStart += yInc;
			else
				yStart = yPos;		
			
			// Set our pricebox location
			PriceBox_Layer.style.pixelTop  = yStart;
			if (sideToHug == 'left')
				PriceBox_Layer.style.pixelLeft = xPos;
			else
				PriceBox_Layer.style.pixelRight = xPos;
			

	}
	
	var nTimeOut = 10;
	setTimeout('SetDiagramPosition(\'' + sideToHug + '\', ' + extraPaddingSide + ', ' + extraPaddingTop + ', ' + extraPaddingSideIE + ', ' + extraPaddingTopIE + ')', nTimeOut);

}


function IsNetScapeBrowser () {
	return !document.all;
}





