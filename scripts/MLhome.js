<!-- Begin Library Hours Script 

var message = ""; 
today = new Date(); 
myDay = today.getDay(); 
myDayOfMonth = today.getDate(); 

<!-- Sample of a message with a link. Note the single quotes. --> 
<!-- message = "7:00am - 8:00pm <br> <a href='/libraryinfo/news/'>read more news</a>"; -->
<!-- --> 

function writeTip2() { 

<!-- Monday - Thursday hours --> 

if (myDay <= 4 && myDay >=1){ 

<!-- Semester Hours --> 

message = "7am - 10pm"; 

<!-- Break Hours --> 

<!--message = "7am - 6pm";--> 

} 

<!-- Friday hours --> 

if (myDay == 5){ 

<!-- Semester Hours --> 

message = "7am - 6pm"; 

<!-- Break Hours --> 

<!--message = "7am - 6pm";--> 

} 

<!-- Saturday hours --> 
if (myDay == 6){ 

<!-- Semester Hours --> 
message = "9am - 6pm"; 
<!-- Break Hours --> 
<!--message = "12pm - Midnight";--> 
} 
<!-- Sunday hours --> 
if (myDay == 0){ 

<!-- Semester Hours --> 

message = "1pm - 6pm"; 

<!-- Break Hours --> 

<!--message = "Closed";--> 
} 
<!-- Place monthly exceptions (holidays, shortened hours, etc.) below based on the day of the month they fall on. --> 

switch (myDayOfMonth){ 

case 30:
message = "CLOSED";
break
} 

document.write(message); 
} 

// End -->


<!--
//   javascript that swaps in a new header photo on reload
var pic = new Array()
pic[0] = "/images/libHoursbg1.jpg"
pic[1] = "/images/libHoursbg2.jpg"
pic[2] = "/images/libHoursbg3.jpg"
pic[3] = "/images/libHoursbg4.jpg"


var show = Math.floor(Math.random() * (pic.length))
//-->


/***********************************************
* Fade In Slideshow Script
***********************************************/
 /*
var fadeimages=new Array()

//	SET IMAGE PATHS. Extend or contract array as needed
fadeimages[0]=["/images/rotating/ebscoHostAd.jpg", "http://t-proxy.lib.utah.edu/login?url=http://search.ebscohost.com/login.aspx?authtype=ip,uid&profile=mobile", ""]
fadeimages[1]=["/images/rotating/libStoreAd.jpg", "/services/library-store.php", ""]
fadeimages[2]=["/images/rotating/Uspace_RotatingImage_B.jpg", "http://uspace.utah.edu/submit.php", ""]
fadeimages[3]=["/images/rotating/Espressomachine_MLIBslide.jpg", "/services/espresso-book-machine.php", ""]
fadeimages[4]=["/images/rotating/browseByCounty.jpg", "http://maps.google.com/maps/ms?msa=0&msid=109504353381514214049.00047d9a3a6a75559f30c&cd=2&sll=39.89046,-98.992383&sspn=22.575064,57.941719&hl=en&ie=UTF8&ll=39.529467,-112.423096&spn=6.947091,7.723389&z=7",""]
fadeimages[5]=["/images/rotating/FDB_Comix_MLIBslide.jpg", "http://comx.alexanderstreet.com/", ""]
fadeimages[6]=["/images/rotating/Antarctica_MLIB.jpg", "http://marriottlibrary.wordpress.com/2011/01/27/antarctic-lecture-series-at-the-j-willard-marriott-library/", ""]
fadeimages[7]=["/images/rotating/UPressMLIBslide2.jpg", "http://www.uofupress.com/portal/site/uofupress/", ""]
fadeimages[8]=["/images/rotating/TFMLIBslide.jpg", "http://www.events.utah.edu/webevent.cgi?cmd=showevent&ncmd=listmonth&id=151695&cal=cal10&ncals=&cat=&sib=1&sort=e,m,t&ws=0&cf=list&set=1&swe=1&sa=0&de=1&tf=0&sb=0&stz=Default&d=31&m=03&y=2011", ""]
*/



 
var fadebgcolor="#ffffff"

//	NO need to edit beyond here			/////////////
var fadearray=new Array() //array to cache fadeshow instances
var fadeclear=new Array() //array to cache corresponding clearinterval pointers
 
var dom=(document.getElementById) //modern dom browsers
var iebrowser=document.all
 
function fadeshow(theimages, fadewidth, fadeheight, borderwidth, delay, pause, displayorder){
this.pausecheck=pause
this.mouseovercheck=0
this.delay=delay
this.degree=10 //initial opacity degree (10%)
this.curimageindex=0
this.nextimageindex=1
fadearray[fadearray.length]=this
this.slideshowid=fadearray.length-1
this.canvasbase="canvas"+this.slideshowid
this.curcanvas=this.canvasbase+"_0"
if (typeof displayorder!="undefined")
theimages.sort(function() {return 0.5 - Math.random();}) //thanks to Mike (aka Mwinter) :)
this.theimages=theimages
this.imageborder=parseInt(borderwidth)
this.postimages=new Array() //preload images
for (p=0;p<theimages.length;p++){
this.postimages[p]=new Image()
this.postimages[p].src=theimages[p][0]
}
 
var fadewidth=fadewidth+this.imageborder*2
var fadeheight=fadeheight+this.imageborder*2
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers (ie: Firefox)
document.write('<div id="master'+this.slideshowid+'" style="position:relative;width:'+fadewidth+'px;height:'+fadeheight+'px;overflow:hidden;"><div id="'+this.canvasbase+'_0" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div><div id="'+this.canvasbase+'_1" style="position:absolute;width:'+fadewidth+'px;height:'+fadeheight+'px;top:0;left:0;filter:progid:DXImageTransform.Microsoft.alpha(opacity=10);opacity:0.1;-moz-opacity:0.1;-khtml-opacity:0.1;background-color:'+fadebgcolor+'"></div></div>')
else
document.write('<div><img name="defaultslide'+this.slideshowid+'" src="../files/'+this.postimages[0].src+'"></div>')
 
if (iebrowser&&dom||dom) //if IE5+ or modern browsers such as Firefox
this.startit()
else{
this.curimageindex++
setInterval("fadearray["+this.slideshowid+"].rotateimage()", this.delay)
}
}

function fadepic(obj){
if (obj.degree<100){
obj.degree+=10
if (obj.tempobj.filters&&obj.tempobj.filters[0]){
if (typeof obj.tempobj.filters[0].opacity=="number") //if IE6+
obj.tempobj.filters[0].opacity=obj.degree
else //else if IE5.5-
obj.tempobj.style.filter="alpha(opacity="+obj.degree+")"
}
else if (obj.tempobj.style.MozOpacity)
obj.tempobj.style.MozOpacity=obj.degree/101
else if (obj.tempobj.style.KhtmlOpacity)
obj.tempobj.style.KhtmlOpacity=obj.degree/100
else if (obj.tempobj.style.opacity&&!obj.tempobj.filters)
obj.tempobj.style.opacity=obj.degree/101
}
else{
clearInterval(fadeclear[obj.slideshowid])
obj.nextcanvas=(obj.curcanvas==obj.canvasbase+"_0")? obj.canvasbase+"_0" : obj.canvasbase+"_1"
obj.tempobj=iebrowser? iebrowser[obj.nextcanvas] : document.getElementById(obj.nextcanvas)
obj.populateslide(obj.tempobj, obj.nextimageindex)
obj.nextimageindex=(obj.nextimageindex<obj.postimages.length-1)? obj.nextimageindex+1 : 0
setTimeout("fadearray["+obj.slideshowid+"].rotateimage()", obj.delay)
}
}
 
fadeshow.prototype.populateslide=function(picobj, picindex){
var slideHTML=""
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML='<a href="../files/'+this.theimages[picindex][1]+'" target="'+this.theimages[picindex][2]+'">'
slideHTML+='<img src="../files/'+this.postimages[picindex].src+'" border="'+this.imageborder+'px">'
if (this.theimages[picindex][1]!="") //if associated link exists for image
slideHTML+='</a>'
picobj.innerHTML=slideHTML
}
 
 
fadeshow.prototype.rotateimage=function(){
if (this.pausecheck==1) //if pause onMouseover enabled, cache object
var cacheobj=this
if (this.mouseovercheck==1)
setTimeout(function(){cacheobj.rotateimage()}, 100)
else if (iebrowser&&dom||dom){
this.resetit()
var crossobj=this.tempobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
crossobj.style.zIndex++
fadeclear[this.slideshowid]=setInterval("fadepic(fadearray["+this.slideshowid+"])",50)
this.curcanvas=(this.curcanvas==this.canvasbase+"_0")? this.canvasbase+"_1" : this.canvasbase+"_0"
}
else{
var ns4imgobj=document.images['defaultslide'+this.slideshowid]
ns4imgobj.src=this.postimages[this.curimageindex].src
}
this.curimageindex=(this.curimageindex<this.postimages.length-1)? this.curimageindex+1 : 0
}
 
fadeshow.prototype.resetit=function(){
this.degree=10
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
if (crossobj.filters&&crossobj.filters[0]){
if (typeof crossobj.filters[0].opacity=="number") //if IE6+
crossobj.filters(0).opacity=this.degree
else //else if IE5.5-
crossobj.style.filter="alpha(opacity="+this.degree+")"
}
else if (crossobj.style.MozOpacity)
crossobj.style.MozOpacity=this.degree/101
else if (crossobj.style.KhtmlOpacity)
crossobj.style.KhtmlOpacity=this.degree/100
else if (crossobj.style.opacity&&!crossobj.filters)
crossobj.style.opacity=this.degree/101
}
 
 
fadeshow.prototype.startit=function(){
var crossobj=iebrowser? iebrowser[this.curcanvas] : document.getElementById(this.curcanvas)
this.populateslide(crossobj, this.curimageindex)
if (this.pausecheck==1){ //IF SLIDESHOW SHOULD PAUSE ONMOUSEOVER
var cacheobj=this
var crossobjcontainer=iebrowser? iebrowser["master"+this.slideshowid] : document.getElementById("master"+this.slideshowid)
crossobjcontainer.onmouseover=function(){cacheobj.mouseovercheck=1}
crossobjcontainer.onmouseout=function(){cacheobj.mouseovercheck=0}
}
this.rotateimage()
}
