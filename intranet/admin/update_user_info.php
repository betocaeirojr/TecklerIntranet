<HTML>
<HEAD><TITLE>Search Profile Info</TITLE></HEAD>
<BODY>
<H1>Teckler Administrative Portal</H1>
<H2>Update User Information</H2>


<P> User Language Configuration</P>
<FORM method="POST" action="process_user_update.php">
<TABLE border=1>
	<TR>
		<TD>User ID:</TD>
		<TD><input type="text" name="user_id"></TD>
	</TR>
	<TR>
		<TD>User Language Code</TD>
		<TD><SELECT name="user_lang_code">
			<OPTION value="en">Select One </OPTION>
			<OPTION value="ar">Arabic </OPTION>		
			<OPTION selected value="en">English </OPTION>
			<OPTION value="fr">French </OPTION>
			<OPTION value="de">German </OPTION>
			<OPTION value="he">Hebrew </OPTION>
			<OPTION value="hi">Hindi </OPTION>
			<OPTION value="it">Italian </OPTION>
			<OPTION value="jp">Japonese </OPTION>
			<OPTION value="ko">Korean </OPTION>
			<OPTION value="zh">Mandarin </OPTION>	
			<OPTION value="pt">Portuguese </OPTION>
			<OPTION value="ru">Russian </OPTION>
			<OPTION value="es">Spanilh </OPTION>
		    </SELECT>
		</TD>
	</TR>
	<TR>
		<TD><input type="submit" name="action" value="cancel"></TD>
		<TD><input type="hidden" name="change" value="lang"><input type="submit" name="action_lang" value="submit"></TD>
		
	</TR>
</TABLE>
</FORM>
<P>PS: Changing the Language Code of an User, also changes ALL Tecks for ALL Profiles of this user!<BR>
So, use it wisely! </P>

<HR>

<P>User Status</P>
<FORM method="POST" action="process_user_update.php">
<TABLE border=1>
        <TR>
                <TD>User ID:</TD>
                <TD><input type="text" name="user_id"></TD>
        </TR>
	<TR>
                <TD>User Status</TD>
                <TD><SELECT name="user_status">
                        <OPTION value="a">Active </OPTION>
                        <OPTION value="i">Inactive </OPTION>
                        <OPTION value="f">Fraud </OPTION>
		    </SELECT>
                </TD>
        </TR>
        <TR>
                <TD><input type="submit" name="action" value="cancel"></TD>
                <TD><input type="hidden" name="change" value="status"><input type="submit" name="action" value="submit"></TD>
        </TR>
</TABLE>
</FORM>
<HR>
<!--
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
<HR-->
</BODY>
</HTML>
