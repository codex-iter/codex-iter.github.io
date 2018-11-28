function get_parentid(arr, depth)
{
	for(var i=arr.length-1; i>=0; i--)
	{
		if (arr[i]['depth'] == depth) return arr[i]['forumid'];
	}
}

function get_forums_hierarchy()
{

	var ul_content = document.getElementById('menu-to-edit');
	var lis = ul_content.getElementsByTagName('LI');

	var forums_hierarchy_arr = new Array();

	for(var i = 0; i < lis.length; i++)
	{
		forums_hierarchy_arr[i] = new Array();
		forums_hierarchy_arr[i]['forumid'] =  lis[i].id.replace('menu-item-', '');
		var depth =  lis[i].getAttribute('class').replace('menu-item menu-item-depth-', '');
		
		forums_hierarchy_arr[i]['depth'] = depth;
		
		if(depth == 0)
		{
			forums_hierarchy_arr[i]['parentid'] = 0;
		}else
		{
			var previous_depth = depth - 1;
			forums_hierarchy_arr[i]['parentid'] = get_parentid(forums_hierarchy_arr, previous_depth);
		}
		
		forums_hierarchy_arr[i]['order'] = i + 1;
		
		var h_id = 'forumid-' + forums_hierarchy_arr[i]['forumid'];
		var h_parentid = 'parentid-' + forums_hierarchy_arr[i]['forumid'];
		var h_order = 'order-' + forums_hierarchy_arr[i]['forumid'];
		
		document.getElementById(h_id).value = forums_hierarchy_arr[i]['forumid'];
		document.getElementById(h_parentid).value = forums_hierarchy_arr[i]['parentid'];
		document.getElementById(h_order).value = forums_hierarchy_arr[i]['order'];
		
	}

	document.getElementById("forum-hierarchy").submit();

}

function mode_changer(v){
	
	if(v=='true'){
		document.getElementById("forum_submit").value= wpforo_admin.phrases.move;
		document.getElementById("forum_select").disabled=false
	}else{
		document.getElementById("forum_submit").value= wpforo_admin.phrases.delete;
		document.getElementById("forum_select").disabled=true
	}
}
function select_all(){
	
	var sel_all = document.getElementById('cb-select-all-1');
	
	if(sel_all.checked){
		var table = document.getElementById('the-list');
		var list=table.getElementsByTagName("INPUT");
		
		for (var i=0; i<list.length; i++){
			document.getElementById(list[i].id).checked=true
		}
	}else{
		var table = document.getElementById('the-list');
		var list=table.getElementsByTagName("INPUT");
		
		for (var i=0; i<list.length; i++){
			document.getElementById(list[i].id).checked=false
		}
	}
}
function costum_or_inherit(){
	
	var chack = document.getElementById('custom');
	
	if(chack.checked){
		document.getElementById("permis").disabled=true;
	}else{
		document.getElementById("permis").disabled=false;
	}
}

function mode_changer_ug(v){
	
	if(v=='true'){
		document.getElementById("ug_select").disabled=false
	}else{
		document.getElementById("ug_select").disabled=true
	}
}


