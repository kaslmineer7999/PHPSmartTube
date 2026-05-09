var tab = document.getElementsByClassName('x-tabs')[0].getElementsByClassName('x-tab')[2];
tab.classList.add('x-tab-active');
tab.getElementsByTagName('a')[0].href = '#';
var progBarJuice = document.querySelector('.progress-bar .juice');
var ppBtn        = document.querySelector('.pp-btn');
var video        = document.querySelector('.c-video video');
var cTimeDisplay = document.querySelector('.controls .lr-time .curt');
var tTimeDisplay = document.querySelector('.controls .lr-time .total');
var volBtn       = document.querySelector('.controls .lr-time .vol');
var id           = null;

ppBtn.onclick = function(){
	if(video.paused){
		ppBtn.classList.add('pause');
		ppBtn.classList.remove('play');
		video.play();
	} else {
		ppBtn.classList.add('play');
		ppBtn.classList.remove('pause');
		video.pause();
	};
};
ppBtn.onfocus = function(){ ppBtn.blur(); };
document.onkeydown = function(e){
	if(e.key == ' ' || e.key == 'Space') ppBtn.onclick();
};
video.onclick = function(){ ppBtn.onclick(); };
video.ontimeupdate = function(){
	progBarJuice.style.width = (video.currentTime / video.duration) * 100 + '%';
	var cMin = Math.floor(video.currentTime / 60);
	var cSec = Math.floor(video.currentTime % 60);
	var tMin = Math.floor(video.duration / 60);
	var tSec = Math.floor(video.duration % 60);
	cTimeDisplay.innerHTML = String(cMin).padStart(2,'0') + ':' + String(cSec).padStart(2,'0');
	tTimeDisplay.innerHTML = String(tMin).padStart(2,'0') + ':' + String(tSec).padStart(2,'0');
};
video.ontimeupdate();
video.onended = function(){
	ppBtn.classList.add('play');
	ppBtn.classList.remove('pause');
};
progBarJuice.parentNode.onclick = function(e){
	video.currentTime = ((e.clientX - video.getBoundingClientRect().left) / video.offsetWidth) * video.duration;
};
progBarJuice.parentNode.onmousedown = function(){
	this.onmousemove = function(e){
		this.onclick(e);
		video.ontimeupdate(e);
	}
};
progBarJuice.parentNode.onmouseup = function(){
	this.onmousemove = null;
};
volBtn.onclick = function(){
	if(this.classList.contains('muted')){
		this.classList.add('unmuted');
		this.classList.remove('muted');
	} else {
		this.classList.add('muted');
		this.classList.remove('unmuted');
	};
	video.muted = !video.muted;
};
