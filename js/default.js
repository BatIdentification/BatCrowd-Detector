//Variables
var audioPage = 0;
var foundPlayingFile = 0;
var fileName = "";
var staticElements = 1;
pages = [];

//Helper Functions
function tellBatPiTime(){
	var d = new Date();
	currentDate = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds()
	$.post("commands.php", {time: currentDate});
}

function addPageButtons(){
	foundElement = undefined;
	sideNav = $(".side-nav")[0];
	arrLength = $("li:not('.hidden') > .audiofile").length;
	//Check if there are more files than the height of the bar
	if(sideNav.offsetHeight < sideNav.scrollHeight + 10){
		$("li:not('.hidden') > .audiofile").each(function(e){
			//Check if a file is currently playing
			if(fileName != "" && foundPlayingFile == 0){	
				//Check if this <li> element corresponds to that file
				foundPlayingFile = this.innerHTML == fileName ? 1 : 0;
				foundElement = this
			}
			rect = this.getBoundingClientRect();
			//The bottom of this <a> tag is not visible
			if(rect.bottom > sideNav.offsetHeight){
				i = $(".audiofile").index(this)	
				lastElement = (rect.bottom - sideNav.offsetHeight) < 21 ? this : $(".audiofile")[i - 1]
				//We are playing a file and it is not in this row of files, therefore we need to get rid of all of the currently visible audiofiles
				//Runs if we havn't found the file yet, if the playing audio file is the not going to be visible. Because this function found it, it will stop here but we need it to go forward one more page to see the audiofile
				if(fileName != "" && foundPlayingFile == 0 || fileName == this.innerHTML && foundPlayingFile != 2 || foundElement == lastElement){
					audioPage += 1;
					pages.push($(lastElement).parent().prevAll(":not(.hidden)").slice(0, -staticElements))
					$(lastElement).parent().prevAll().slice(0, -staticElements).addClass("hidden");	
				}else{
					tabLi = audioPage == 0 ? $("<li class='center-text'><a class='pageControl' id='nextPage'>Next</a></li>") : $("<li class='center-text'><a class='pageControl' id='previousPage'>Previous</a><a class='pageControl' id='nextPage'>Next</a></li>")
					$(tabLi).insertBefore($(lastElement).parent());	
					//Check to see if the element that we did the insertBefore on is still visible
					lastElementRect = lastElement.getBoundingClientRect();
					if(lastElementRect.top < sideNav.offsetHeight){
						liTopMargin = sideNav.offsetHeight - lastElementRect.top
						$("li.center-text").css("margin-top", liTopMargin);
					}		
					return false;
					foundPlayingFile = 2;
				}
			}
		});					
	}
	if(audioPage != 0 && $(".pageControl").length == 0){
		$("<li class='center-text bottom-of-div'><a class='pageControl' id='previousPage'>Previous</a></li>").insertAfter($("li:not('.hidden') > .audiofile").last().parent());
	}
	foundPlayingFile = 2;
}
//Page stuff
$(document).ready(function(){
	//Code for setting up the audio-files side-bar
	$(document).on("click", "a#nextPage" , function(){
		if(pages[audioPage] == undefined){
			pages.push($(this).parent().prevAll(":not(.hidden)").slice(0, -staticElements));	
			$(this).parent().prevAll(":not(.hidden)").slice(0, -staticElements).addClass("hidden");     
		}else{
			pages[audioPage].each(function(){
				$(this).addClass("hidden");
			})
		}
		audioPage += 1;
		$(this).parent().remove();
		addPageButtons();
	});
	$(document).on("click", "a#previousPage", function(){
		audioPage -= 1;
		pages[audioPage].each(function(){
			$(this).removeClass("hidden");
		});
		$(this).parent().remove();
		addPageButtons();
	});
	$(document).click(function(){
		$.post("commands.php", {shutdown: true});
	});
});
