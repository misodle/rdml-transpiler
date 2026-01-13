
 function SetFld(name,value)
 {
	if (document.FORM.elements[name])
	{
		document.FORM.elements[name].value = value;
	}
	else
	{
	var newdiv = document.createElement('div');
    newdiv.innerHTML = '<input type="hidden" name="' + name + '" value="' + value + '">';
    document.FORM.appendChild(newdiv);
		
	//	var element = document.createElement('input');
		
	//	var attr = document.createAttribute('type');
	//	attr.nodeValue = 'hidden';
	//	element.setAttributeNode(attr);
	
	//	attr = document.createAttribute('name');
	//	attr.nodeValue = name;
	//	element.setAtrributeNode(attr)
		
	//	attr = document.createAttribute('value');
	//	attr.nodeValue = value;
	//	element.setAtrributeNode(attr)
		
	//	document.FORM.appendChild(element);
	}
 }
 function SetPgm(buttonValue,funcName)
 {
	if (buttonValue)
	{
		SetFld('STD_BUTTON',buttonValue);
	}
	else
	{
		SetFld('STD_BUTTON','');
	}
	if (funcName)
	{
		document.FORM.action = funcName + '.php';
	}
	document.FORM.submit();
 }
 
 //alert('dude');

