// Construct AngularJS module
var app = angular.module("fbSearchApp", []);
app.controller('fbSearchCtrl', function($scope) {
});

$(document).ready(function() {

	// Search button onclick handler
	$("#search-button").click(function() {

		console.log("Clicked search button!");

		var search = $("#search-text-field").val();
		if (search == "") {
			alert("Please enter a search query.");
		}
		var dataString = "search=" + search;

		console.log("Search string is " + dataString);

	    $.ajax({
	    	type: 'GET',
	    	url: "http://fbsearch-hw8.us-west-2.elasticbeanstalk.com/",
	    	crossDomain: true,
	    	contentType: 'text',
	    	xhrFields: { withCredentials: false },
	    	data: dataString,
	    	
	    	success: function(response, status, xhr) {
	    		// Parse the JSON response
	    		var data = JSON.parse(response);
	    		var users_data = JSON.parse(data["users"]);
	    		var pages_data = JSON.parse(data["pages"]);
	    		var events_data = JSON.parse(data["events"]);
	    		var places_data = JSON.parse(data["places"]);
	    		var groups_data = JSON.parse(data["groups"]);

	    		// Log response
	    		console.log(users_data["data"]);
	    		console.log(pages_data["data"]);
	    		console.log(events_data["data"]);
	    		console.log(places_data["data"]);
	    		console.log(groups_data["data"]);

	    		// Update interface
	    		updateUsersTab(users_data);
	    	},
	    	error: function(xhr, status, error) {
	    		// parse the error
	    		console.log("Failed with status: " + status + " and error: " + error);
	    	}
	    });
	});
})

function updateUsersTab(data) {

}

function updatePagesTab(data) {

}