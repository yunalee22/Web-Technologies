<html>
    <head>
    </head>
    <body>
        <h2 style="text-align: center;"><i>Facebook Search</i></h2><hr>
        <form method="POST" action="/cgi-bin/temp.cgi">
            <table>
                <tr><td><label for="keyword">Keyword:</label></td>
                    <td><input type="text" name="keyword" id="keyword"></td></tr>
                <tr><td><label for="type">Type:</label></td>
                    <td><select name="type">
                            <option selected>Users</option>
                            <option>Pages</option>
                            <option>Events</option>
                            <option>Groups</option>
                            <option>Places</option>
                        </select></td></tr>
                <tr id="places_fields"><br></tr>
                <tr><td></td><td>
                    <input type="submit" value="Search">
                    <input type="reset" value="Clear"></td></tr>
            </table>
            
        </form>
    </body>
</html>