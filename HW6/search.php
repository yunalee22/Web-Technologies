<html>
    <head>
        <title>Facebook Search</title>
        <script language="JavaScript">
            // Shows the location and distance fields when "Places" is selected
            function showPlacesFields() {
                document.getElementById("places_fields").innerHTML =
                    "<td><label for='location'>Location:</label></td><td><input type='text' name='location' id='location' value='<?php if (isset($_GET['location'])) echo htmlspecialchars ($_GET['location']) ?>' required></td><td><label for='distance'>Distance (meters):</label></td><td><input type='number' name='distance' id='distance' value='<?php if (isset($_GET['distance'])) echo htmlspecialchars ($_GET['distance']) ?>' required></td>";
            }

            // Hides the location and distance fields when "Places" is unselected
            function hidePlacesFields() {
            	document.getElementById("places_fields").innerHTML = "";
            }
            
            function clearFormFields() {
                // Clear results
                if (document.getElementById("container")) {
                    document.getElementById("container").innerHTML = "";
                }

                // Clear form
                document.getElementById("searchform").reset();
                if (document.getElementById("type").selectedIndex == 4) {
                	document.getElementById("location").value = "";
                	document.getElementById("distance").value = "";
                    document.getElementById("places_fields").innerHTML = "";
                }
                document.getElementById("type").options[0].selected = true;
                document.getElementById("keyword").value = "";
            }

            function toggleAlbumsPanel() {
            	var albums_div = document.getElementById("albums_panel");
            	var posts_div = document.getElementById("posts_panel");
            	if (albums_div.style.display === 'none') {
            		albums_div.style.display = 'block';
            		posts_div.style.display = 'none';
            	} else {
            		albums_div.style.display = 'none';
            	}
            }

            function togglePostsPanel() {
            	var albums_div = document.getElementById("albums_panel");
            	var posts_div = document.getElementById("posts_panel");
            	if (posts_div.style.display === 'none') {
            		posts_div.style.display = 'block';
            		albums_div.style.display = 'none';
            	} else {
            		posts_div.style.display = 'none';
            	}
            }

            function toggle(element) {
            	console.log(element.nodeName);
            	if (element.style.display === 'none') {
            		element.style.display = 'block';
            	} else {
            		element.style.display = 'none';
            	}
            }
        </script>
    </head>
    <body>
        <h2 style="text-align: center;"><i>Facebook Search</i></h2><hr>
        <form name="searchform" id="searchform" method="GET" action="search.php">
            <table>
                <tr><td><label for="keyword">Keyword:</label></td>
                    <td><input type="text" name="keyword" id="keyword" value="<?php if (isset($_GET['keyword'])) echo htmlspecialchars ($_GET['keyword']) ?>" required></td></tr>
                <tr><td><label for="type">Type:</label></td>
                    <td><select name="type" id="type" onchange="if (this.selectedIndex == 4) showPlacesFields(); else hidePlacesFields();" required>
                            <option value="Users" selected <?php if (isset($_GET['type']) && $_GET['type'] == 'Users') echo 'selected="selected"'; ?>>Users</option>
                            <option value="Pages" <?php if (isset($_GET['type']) && $_GET['type'] == 'Pages') echo 'selected="selected"'; ?>>Pages</option>
                            <option value="Events" <?php if (isset($_GET['type']) && $_GET['type'] == 'Events') echo 'selected="selected"'; ?>>Events</option>
                            <option value="Groups" <?php if (isset($_GET['type']) && $_GET['type'] == 'Groups') echo 'selected="selected"'; ?>>Groups</option>
                            <option value="Places" <?php if (isset($_GET['type']) && $_GET['type'] == 'Places') echo 'selected="selected"'; ?>>Places</option>
                        </select></td></tr>
                <tr id="places_fields"></tr>
                <tr><td></td><td>
                    <input type="submit" name="submit" value="Search">
                    <input type="reset" name="clear" value="Clear" onclick="clearFormFields()"></td></tr>
            </table>
        </form>
        
        <?php
        	error_reporting(E_ERROR | E_PARSE);

        	if (isset($_GET["submit"]))
        	{
        		// Get form data
        		$q = $_GET["keyword"];
        		$type = "";
        		switch ($_GET["type"])
        		{
        			case "Users":
        				$type = "user";
        				break;
        			case "Pages":
        				$type = "page";
        				break;
        			case "Events":
        				$type = "event";
        				break;
        			case "Groups":
        				$type = "group";
        				break;
        			case "Places":
        				$type = "place";
        				break;
        			default:
        				break;
        		}
        		$fields = "id,name,picture.width(700).height(700)";
        		if ($type == "event")
        		{
        			$fields = $fields . ",place";
        		}
        		$center = "";
        		$distance = "";
        		if ($type == "place")
        		{
        			// Get coordinates from Google Geocoding API
        			$location = $_GET["location"];
        			$location = urlencode($location);
        			$api_key = "AIzaSyAXhvnVVcF_HATVwanAdFHoluWMrFdGOOE";
        			$url = "https://maps.googleapis.com/maps/api/geocode/json?address="
        				. $location . "&key=" . $api_key;
        			$response = file_get_contents($url);
    				$response = json_decode($response, true);
        			
        			$center = "";
        			if ($response['status'] != "OK") return null;
    				$latitude = $response['results'][0]['geometry']['location']['lat'];
    				$longitude = $response['results'][0]['geometry']['location']['lng'];
    				$center = $latitude . "," . $longitude;

        			$distance = $_GET["distance"];
        		}
        		$access_token = "EAARJFZBoE6F8BAFMParl2XT9t8OoGIK3lHVNzVOT8yF5Gr5QuI3ZBPZCUgK2KgJJ3ZCdVsmqqIlY8iBZAhMqcMO7gxwkr9mZCcj64RUbQf17Go3mEEV3iqCJZCK4p4RYV4YbKfZCjZBlMk2bhOdownWc8dVZCviM7Qz7UZD";

        		// Construct query
        		$query = "/search?q=" . $q . "&type=" . $type;
        		if ($type == "place")
        		{
        			$query = $query . "&center=" . $center . "&distance=" . $distance;
        		}
        		$query = $query . "&fields=" . $fields . "&access_token=" . $access_token;

        		// Send HTML request
        		require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
        		$fb = new Facebook\Facebook([
					'app_id' => '1206266966108255',
					'app_secret' => '9e51a47175af4867ad77b31b7350df49',
					'default_graph_version' => 'v2.8',
        			]);
        		try {
				  	// Returns a FacebookResponse object
					$response = $fb->get($query);
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				  echo 'Graph returned an error: ' . $e->getMessage();
				  exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  exit;
				}
				$response = $response->getDecodedBody();

				// Service returns an empty set
				if (count($response['data']) == 0)
				{
					echo "<div id='container'><p style='text-align: center;'>No records have been found.</p></div>";
					return;
				}

				// Display retrieved data in tabular format
				echo "<div id='container'><br><table border='1'><tr><th>Profile Photo</th><th>Name</th>";
				if ($type == "event") {
					echo "<th>Place</th>";
				} else {
					echo "<th>Details</th>";
				}
				echo "</tr>";
				foreach ($response['data'] as $r)
				{
					echo "<tr>";
					// Profile photo
					echo "<td><a href='" . $r['picture']['data']['url'] . "' target='_blank'><img src='" . $r['picture']['data']['url'] . "' width='30' height='40'></a></td>";
					// Name
					echo "<td>" . $r['name'] . "</td>";
					if ($type == "event") {
						// Place of event
						echo "<td><p>" . $r['place']['name'] . "</p></td>";
					} else {
						// Hyperlink that uses 'id' attribute
						echo "<td><a href='search.php?showDetails=true&id={$r['id']}'>Details</a></td>";
					}
					echo "<tr/>";
				}
				echo "</table></div>";
        	}
        ?>

        <?php
        	if (isset($_GET['showDetails']))
        	{
        		// Construct query
        		$id = $_GET['id'];
        		$access_token = "EAARJFZBoE6F8BAFMParl2XT9t8OoGIK3lHVNzVOT8yF5Gr5QuI3ZBPZCUgK2KgJJ3ZCdVsmqqIlY8iBZAhMqcMO7gxwkr9mZCcj64RUbQf17Go3mEEV3iqCJZCK4p4RYV4YbKfZCjZBlMk2bhOdownWc8dVZCviM7Qz7UZD";
        		$query = "/" . $id . "?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)&access_token=" . $access_token;

        		// Send HTML request
        		require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
        		$fb = new Facebook\Facebook([
					'app_id' => '1206266966108255',
					'app_secret' => '9e51a47175af4867ad77b31b7350df49',
					'default_graph_version' => 'v2.8',
        			]);
        		try {
				  	// Returns a FacebookResponse object
					$response = $fb->get($query);
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				  echo 'Graph returned an error: ' . $e->getMessage();
				  exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  exit;
				}
				$response = $response->getDecodedBody();

				// Display up to 5 albums
				if (count($response['albums']['data']) == 0) // Empty album set
				{
					echo "<div id='container'><p style='text-align: center;'>No albums have been found.</p></div>";
				}
				else
				{
					echo "<div id='container'><p style='text-align: center;'><a href='#' onclick='toggleAlbumsPanel()'>Albums</a></p>";
					echo "<div id='albums_panel' style='display: none;'><table border='1'>";
					$count = 0;
					foreach ($response['albums']['data'] as $a)
					{
						echo "<tr><td>";
						
						// Album name
						if (count($a['photos']['data']) == 0) {
							echo "<p>" . $a['name'] . "</p>";
						} else {
							echo "<a href='#' onclick='toggle(this.nextSibling.nextSibling)'>" . $a['name'] . "</a><br>";
						}
						echo "<div style='display: none;'>";

						// Display up to 2 images
						$imgcount = 0;
						foreach ($a['photos']['data'] as $img)
						{
							// Get high resolution image link
			        		$imgquery = "/" . $img['id'] . "/picture?redirect=false&access_token=" . $access_token;

			        		// Send HTML request
			        		$imgresponse;
			        		$fb = new Facebook\Facebook([
								'app_id' => '1206266966108255',
								'app_secret' => '9e51a47175af4867ad77b31b7350df49',
								'default_graph_version' => 'v2.8',
			        			]);
			        		try {
							  	// Returns a FacebookResponse object
								$imgresponse = $fb->get($imgquery);
							} catch(Facebook\Exceptions\FacebookResponseException $e) {
							  echo 'Graph returned an error: ' . $e->getMessage();
							  exit;
							} catch(Facebook\Exceptions\FacebookSDKException $e) {
							  echo 'Facebook SDK returned an error: ' . $e->getMessage();
							  exit;
							}
							$imgresponsebody = $imgresponse->getDecodedBody();

							$imgurl = $imgresponsebody['data']['url'];
							echo "<a href='" . $imgurl . "' target='_blank'>";
							echo "<img src='" . $img['picture'] . "' width='80' height='50'>";
							echo "</a>";

							$imgcount++;
							if ($count == 2) break;
						}

						echo "</div>";
						echo "</td><tr>";

						$count++;
						if ($count == 5) break;
					}
					echo "</table></div></div>";
				}

				// Display up to 5 posts
				if (count($response['posts']['data']) == 0) // Empty posts set
				{
					echo "<div id='container'><p style='text-align: center;'>No posts have been found.</p></div>";
				}
				else
				{
					echo "<div id='container'><p style='text-align: center;'><a href='#' onclick='togglePostsPanel()'>Posts</a></p>";
					echo "<div id='posts_panel' style='display: none;'><table border='1'>";
					echo "<tr><th>Message</th></tr>";
					$count = 0;
					foreach ($response['posts']['data'] as $p)
					{
						echo "<tr><td>";
						echo "<p>" . $p['message'] . "</p>";
						echo "</td></tr>";

						$count++;
						if ($count == 5) break;
					}
					echo "</table></div></div>";
				}

        	}
        ?>
    </body>
</html>