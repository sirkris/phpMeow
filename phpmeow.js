function phpmeow_selblock( div )
{
	var infield = document.getElementById( "f" + div.id );
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
