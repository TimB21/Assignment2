<HTML>
<BODY>

<CENTER>
<H1>Product Page</H1>
(One person's garbage is another person's treasure!)<BR>
<A href="assignment2.php">Search</A>
<BR><BR>

<?php
// Useful functions written by me
require_once("helper.php");
?>


Fill out information about the product you intend to sell<BR>
<TABLE border="1">
<FORM name="newproductform" action="product.php" method="POST"><TR>
	<TH colspan="2" align="center">Your Information</TH>
</TR>
<TR>
	<TD align="right">
				Your E-mail Address
			</TD>
	<TD align="left"><INPUT type="text" size="20" name="emailaddressusername" id="emailaddressusername" value="" maxlength="255"></TD>
</TR>
<TR>
	<TH colspan="2" align="center">The Product's Information</TH>
</TR>
<TR>
	<TD align="right">Category</TD>
	<TD align="left">
		<SELECT name="productcategoryid" id="productcategoryid"><OPTION value="1" >
			   PlayerCard</OPTION><OPTION value="2" >
			   CardBundle</OPTION><OPTION value="3" >
			   AutographedCard</OPTION><OPTION value="4" >
			   Collectable<OPTION><OPTION value="4" >	</TD>
</TR>
<TR>
	<TD align="right">Name</TD>
	<TD align="left"><INPUT type="text" size="20" name="name" id="name" value="" maxlength="255"></TD>
</TR>
<TR>
	<TD align="right" valign="top">Description</TD>
	<TD align="left">
		<TEXTAREA name="description" id="description" cols="20" rows="10"></TEXTAREA>	</TD>
</TR>
<TR>
	<TH colspan="2" align="center">Details of Your Offer</TH>
</TR>
<TR>
	<TD align="right">Number Offered</TD>
	<TD align="left"><INPUT type="text" size="10" name="numberavailable" id="numberavailable" value="" maxlength="10"></TD>
</TR>
<TR>
	<TD align="right">Selling Price</TD>
	<TD align="left">$<INPUT type="text" size="10" name="sellingprice" id="sellingprice" value="" maxlength="10"></TD>
</TR>
<TR>
	<TD align="right" valign="top">Details</TD>
	<TD align="left"><TEXTAREA name="details" id="details" cols="20" rows="10"></TEXTAREA></TD>
</TR>
<TR>
	<TD colspan="2" align="center"><INPUT type="submit" name="submitnew" id="submitnew" value="Submit"></TD>
</TR>
</FORM></TABLE>

</BODY>
</HTML>
