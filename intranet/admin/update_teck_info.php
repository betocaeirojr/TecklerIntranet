<HTML>
<HEAD><TITLE> Update Teck Info</TITLE></HEAD>
<BODY>
<H1>Teckler Administrative Portal</H1>
<H2>Update the info of a Teck</H2>

<P>Update Teck Language Code</P>
<P>Update Language Setting </P>
<FORM method="POST" action="process_teck_update.php">
<TABLE border=1>
	<TR>
		<TD>Teck ID: </TD>
		<TD><input type="text" name="teck_id"></TD>
	</TR>
	<TR><TD>Language</td>
	    <TD>
		<select name="teck_lang_code">
			<option value="en">Select One	</option>
			<option value="ar">Arabic 	</option>
			<option value="en">English 	</option>
			<option value="fr">French 	</option>
			<option value="de">German 	</option>
			<option value="he">Hebrew 	</option>
			<option value="hi">Hindi 	</option>
			<option value="it">Italian 	</option>
			<option value="jp">Japonese	</option>
			<option value="ko">Korean 	</option>
			<option value="zh">Mandarin	</option>
			<option value="pt">Portuguese 	</option>
			<option value="ru">Russian 	</option>
			<option value="es">Spanish 	</option>
		</select>
	    </TD>
	</TR>
	<TR>
		<TD><input type="submit" name="action" value="cancel"></TD>
		<TD><input type="hidden" name="change" value="lang"><input type="submit" name="action" value="submit"></TD>
		
	</TR>
</TABLE>
</FORM>
<HR>
<P>Update Teck Status (1-Published, 2-Draft, 3-Blocked) </P>
<FORM method="POST" action="process_teck_update.php">
<TABLE border=1>
        <TR>
                <TD>Teck ID: </TD>
                <TD><input type="text" name="teck_id"></TD>
        </TR>

	<TR><TD>Status</TD> <TD>
		<select name="teck_status">
			<option value="1">Select One </option>
			<option value="1">Published </option>
			<option value="2">Draft </option>
			<option value="3">Blocked </option>
		</select></TD>
	</TR>        

	
	<TR>
                <TD><input type="submit" name="action" value="cancel"></TD>
                <TD><input type="hidden" name="change" value="status"><input type="submit" name="action" value="submit"></TD>
        </TR>
</TABLE>
</FORM>
<HR>

<P>Set an Teck as an Editorial</P>
<FORM method="POST" action="process_teck_update.php">
<TABLE border=1>
	<TR>
                <TD>Teck ID: </TD>
                <TD><input type="text" name="teck_id"></TD>
        </TR>
	<TR>
                <TD>Editor ID: </TD\>
                <TD><SELECT name="editor_id">
			<OPTION value="7" > Adolfo Sabino </OPTION>
			<OPTION value="38"> Ana Araujo </OPTION>
			<OPTION value="24"> Beto Caeiro</OPTION>
			<OPTION value="15"> Claudio Gandelman </OPTION>
			<OPTION value="3" > Fabio Campos </OPTION>
			<OPTION value="13"> Leo Lima </OPTION>
			<OPTION value="85"> Octavio Bokel</OPTION>
			<OPTION value="97"> Renato Mendes</OPTION>
		    </SELECT>
		</TD>
        </TR>
 	<TR>
                <TD><input type="submit" name="action" value="cancel"></TD>
                <TD><input type="hidden" name="change" value="editorial"><input type="submit" name="action" value="submit"></TD>
        </TR>
</TABLE>
</FORM>
<HR>

</BODY>
</HTML>
