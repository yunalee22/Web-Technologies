<?php
	// Set CORS response headers
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true ");
	header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
	header("Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers");

	// Get search query from form
	$search = "";
	if (isset($_GET["search"])) {
		$search = $_GET["search"];
	} else {
		echo "No search entered";
		exit;
	}

	$access_token = "EAARJFZBoE6F8BAFMParl2XT9t8OoGIK3lHVNzVOT8yF5Gr5QuI3ZBPZCUgK2KgJJ3ZCdVsmqqIlY8iBZAhMqcMO7gxwkr9mZCcj64RUbQf17Go3mEEV3iqCJZCK4p4RYV4YbKfZCjZBlMk2bhOdownWc8dVZCviM7Qz7UZD";

	// Get latitude and longitude
	$latitude = 0;
	$longitude = 0;

	// Construct queries
	$search = urlencode($search);
	$users_query = "https://graph.facebook.com/v2.8/search?q=" . $search . "&type=user&fields=id,name,picture.width(700).height(700)&access_token=" . $access_token;
	$pages_query = "https://graph.facebook.com/v2.8/search?q=" . $search . "&type=page&fields=id,name,picture.width(700).height(700)&access_token=" . $access_token;
	$events_query = "https://graph.facebook.com/v2.8/search?q=" . $search . "&type=event&fields=id,name,picture.width(700).height(700)&access_token=" . $access_token;
	$places_query = "https://graph.facebook.com/v2.8/search?q=" . $search . "&type=place&fields=id,name,picture.width(700).height(700)&center=" . $latitude . "," . $longitude . "&access_token=" . $access_token;
	$groups_query = "https://graph.facebook.com/v2.8/search?q=" . $search . "&type=group&fields=id,name,picture.width(700).height(700)&access_token=" . $access_token;

	// Send HTML requests
	$data["users"] = file_get_contents($users_query);
	$data["pages"] = file_get_contents($pages_query);
	$data["events"] = file_get_contents($events_query);
	$data["places"] = file_get_contents($places_query);
	$data["groups"] = file_get_contents($groups_query);

	// Send content back to application
	echo json_encode($data);

	// Add albums and posts queries later!!

?>