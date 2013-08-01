// Returns an array of elements that match a particular class
function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}


// Highlights the current page on the left navigation
function highlightNavItem( nodeClass ) {
	var documentHREF = document.location.href;
	var navitems = getElementsByClass( nodeClass );
	
	if( navitems ) {
		for( idx = 0; idx < navitems.length; idx++ ) {
			if( getElementsByClass( nodeClass )[idx].childNodes[0].href == documentHREF ) {
				getElementsByClass( nodeClass )[idx].className = "navItem selected";
			}
		}
	}
}

function highlightCurrentPage() {
	highlightNavItem( "navItem" );

}
$(document).ready(function() { highlightCurrentPage(); });
