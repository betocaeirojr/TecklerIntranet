<HTML>
<HEAD><TITLE>Search Profile Info</TITLE></HEAD>
<BODY>
<H1>Teckler Administrative Portal</H1>
<H2>Search for user and profile related information</H2>
<P>You can search for : </P>
<FORM method="POST" action="process_profile_search.php">
<TABLE border=1>
	<TR>
		<TD>Profile ID: </TD>
		<TD><input type="text" name="profile_id"></TD>
	</TR>
	<TR>
		<TD><input type="submit" name="action" value="cancel"></TD>
		<TD><input type="submit" name="action" value="submit"></TD>
	</TR>
</TABLE>
</FORM>

<FORM method="POST" action="process_user_search.php">
<TABLE border=1>
        <TR>
                <TD>User ID: </TD>
                <TD><input type="text" name="user_id"></TD>
        </TR>
        <TR>
                <TD><input type="submit" name="action" value="cancel"></TD>
                <TD><input type="submit" name="action" value="submit"></TD>
        </TR>
</TABLE>
</FORM>

<FORM method="POST" action="process_teck_search.php">
<TABLE border=1>
        <TR>
                <TD>Teck ID: </TD>
                <TD><input type="text" name="teck_id"></TD>
        </TR>
        <TR>
                <TD><input type="submit" name="action" value="cancel"></TD>
                <TD><input type="submit" name="action" value="submit"></TD>
        </TR>
</TABLE>
</FORM>


</BODY>
</HTML>
