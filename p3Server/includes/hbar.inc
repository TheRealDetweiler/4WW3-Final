<div class="hbar" > <!-- the hbar is the tag corresponding to the search bar which is on every page -->
				<form action="results_sample.php" method="GET"> 
						<input type="text" name="textSub" id="search" value="" >
						<input type="submit" id="searchSub" name="searchSub" value="Seach!" >
						
						<!-- 2 critera for searching based on name or location by text, or by overal rating, controlled by radio button -->
						<!-- new function button added to search by geo location-->
		  				<input type="radio" name="searchOption" id="s1" value="Text" onclick="searchText();" checked > Text
		  				<input type="radio" name="searchOption" id="s2" value="Rating" onclick="searchRate();"> Rating
		  				<span id ="searchDrop">
			  				<select name="rateDrop" >
							    <option value="1">1</option>
							    <option value="2">2</option>
							    <option value="3">3</option>
							    <option value="4">4</option>
							    <option value="5">5</option>
						  	</select>
					  	</span >
					  	<!-- <input type="button" id="geoSearch" value="Search by my Location!">  new search by location on each pagee, still need to be programmed-->
				</form>
			</div>