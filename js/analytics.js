function addAddressVariables(address){
	for(i=0;i<address.address_components.length;i++){
		if(address.address_components[i].types[0]=="sublocality"){
			_gaq.push(['_setCustomVar',
			            1,                
			            'District',     
			            address.address_components[i].long_name         
			]);
		}
		if(address.address_components[i].types[0]=="administrative_area_level_1")
		{
			_gaq.push(['_setCustomVar',
			            2,                
			            'State',     
			            address.address_components[i].long_name         
			]);	
		}		
		if(address.address_components[i].types[0]=="locality"){
			_gaq.push(['_setCustomVar',
			            3,                
			            'City',     
			            address.address_components[i].long_name         
			]);	
		}
	}
}
function trackMarkerUpdate(address,origin){
	addAddressVariables(address);
	_gaq.push(['_trackEvent', 'Search', origin,address.formatted_address]);
}
function trackRealEstateClick(url,price,index,type){
	_gaq.push(['_setCustomVar',
	            1,                
	            'Price',     
	            price         
	]);	
	_gaq.push(['_trackEvent', 'Click', type,url,index]);
}
function trackParameterChange(type,value)
{
	if(currentAddress!=null) addAddressVariables(currentAddress);
	_gaq.push(['_trackEvent', 'Parameter', "Change",type,value]);
}

function trackEmail(email,type){
	_gaq.push(['_setCustomVar',
	            1,                
	            'Email',     
	            email        
	]);
	_gaq.push(['_trackEvent', 'Account', "PreRegister",type]);
}
function trackIntention(type){
	_gaq.push(['_trackEvent', 'Account', "Display",type]);
}
function trackMenu(type){
	_gaq.push(['_trackEvent', 'Menu', type]);
}