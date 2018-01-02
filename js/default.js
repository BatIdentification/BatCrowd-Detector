//Variables
var audioPage = 0;
var foundPlayingFile = false;
var fileName = "";
var staticElements = 1;

//Helper Functions
function tellBatPiTime(){
	var d = new Date();
	currentDate = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds()
	$.post("setTime.php", {time: currentDate});
}

function addPageButtons(){
	foundElement = undefined;
	sideNav = $(".side-nav")[0];
	arrLength = $("li:not('.hidden') > .audiofile").length;
	//Check if there are more files than the height of the bar
	if(sideNav.offsetHeight < sideNav.scrollHeight){
		$("li:not('.hidden') > .audiofile").each(function(e){
			//Check if a file is currently playing
			if(fileName != "" && foundPlayingFile == false){	
				//Check if this <li> element corresponds to that file
				foundPlayingFile = this.innerHTML == fileName ? true : false;
				foundElement = this
			}
			rect = this.getBoundingClientRect();
			//The bottom of this <a> tag is not visible
			if(rect.bottom > sideNav.offsetHeight){
				i = $(".audiofile").index(this)	
				lastElement = (rect.bottom - sideNav.offsetHeight) < 21 ? this : $(".audiofile")[i - 1]
				//We are playing a file and it is not in this row of files, therefore we need to get rid of all of the currently visible audiofiles
				//Runs if we havn't found the file yet, if the playing audio file is the not going to be visible. Because this function found it, it will stop here but we need it to go forward one more page to see the audiofile
				if(fileName != "" && foundPlayingFile == false || fileName == this.innerHTML || foundElement == lastElement){
					audioPage += 1;
					$(lastElement).parent().prevAll().slice(0, -2).addClass("hidden");
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
				}
			}
		});					
	}
	if(audioPage != 0 && $(".pageControl").length == 0){
		console.log(audioPage);
		$("<li class='center-text bottom-of-div'><a class='pageControl' id='previousPage'>Previous</a></li>").insertAfter($("li:not('.hidden') > .audiofile").last().parent());
	}
}
//Page stuff
$(document).ready(function(){
	//Code for setting up the audio-files side-bar
	$(document).on("click", "a#nextPage" , function() {
		$(this).parent().prevAll().slice(0, -staticElements).addClass("hidden");         		
		$(this).parent().remove();
		audioPage += 1;
		addPageButtons();
	});
	$(document).on("click", "a#previousPage", function(){
		i = 0
		prevBottom = undefined;
		//Gets the first(visible) element under audiofiles
		topFile = $(this).parent().prevAll().slice(0,-staticElements).filter(":not(.hidden)").last()[0]
		topRect = topFile.getBoundingClientRect();
		//While this element is able to be seen do this
		while(topRect.bottom < sideNav.offsetHeight){
			//Remove the hidden class from the first non-visible element
			$(topFile).prevAll("li").eq(i).removeClass("hidden")
			//Get this elements position again
			topRect = topFile.getBoundingClientRect();
			i += 1
			//topRect has stopped moving downwards
			if(prevBottom == topRect.bottom){
				break
			}
			prevBottom = topRect.bottom;
		}
		//Due to how this method works(pushing down the audiofile) we get one extra audiofile than what we can have(since we will be adding the pages buttons), therefore we will hide the last audiofile that we added.
		$(topFile).prevAll("li > a.audiofile").eq(i-1).addClass("hidden")
		console.log(i);
		$(this).parent().remove();
		audioPage -= 1;
		addPageButtons();
	});
});