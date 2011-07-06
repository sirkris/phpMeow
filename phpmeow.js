function phpmeow_selblock( div )
{
	infield = document.getElementById( "f" + id );
	if ( infield.value == 0 )
	{
		div.style.border = "10px solid yellow";
		infield.value = 1;
	}
	else
	{
		div.style.border = "10px solid navy";
		infield.value = 0;
	}
}
