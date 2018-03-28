// author: brian.muhumuza@gmail.com

// cascading drop down fields:
// method to retrieve & populate child options based on the selected parent option
function populateCascadingDropDown(search_path, parent_select, child_select){
	// get selected value in the parent drop down
	var parent_value = document.getElementById(parent_select).value;
	
	// clean out all options from the child drop down
	var child_obj = document.getElementById(child_select);
	child_obj.options.length = 0;

	// get child options only if parent selection was meaningful
	if (parent_value.length > 0){
		var jsonObject = {};
		var xhr = new XMLHttpRequest();
		xhr.open("GET", search_path + "&query=" + parent_value, true);
		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200){
				jsonObject = JSON.parse(xhr.responseText);
				
				for(var i in jsonObject){
					// add drop down entry
					child_obj.options[child_obj.options.length] = new Option(jsonObject[i], i, false, false);
				}
			}
		}
		xhr.send(null);	
	}
}

// Table Row Copy & Paste
// copy_from: row number to copy FROM counting from the bottom of the table i.e
// copy_from=2 means copy second last row
//
// paste_after_position: after which position in table to paste TO counting from the bottom of the table i.e
// paste_after_position=1 means paste into the second last position
function duplicateTableRow(table_id, copy_from, paste_after_position)
{
	if (copy_from === undefined) {
		copy_from = 2;
	}
	
	if (paste_after_position === undefined) {
		paste_after_position = 1;
	}
	
	var table = document.getElementById(table_id);
	// get the last row
	var last_row_html = table.rows[table.rows.length-copy_from].innerHTML;
	// copy last row into new row
	var new_row = table.insertRow(table.rows.length-paste_after_position);
	new_row.innerHTML = last_row_html;
}


//Accepts two parameters: The first of which is the check_all_box - it should be a 
//FIELD level reference to your checkbox (not a form or a checked status or a value).
// If you use the onchange method to switch it like I do then all you need to do is 
//pass the ‘this’ keyword to it. See my example below.
//Parameter two is the obj_set_name which is the name of the "object set" or the name 
//you use for your checkbox list. This is what allows the use of multiple lists on one page.
function toggleAllCheckBoxes(check_all_box, obj_set_name)
{
	var selection_status = check_all_box.checked;

//This is weird because intuitivly, once you click it the first time it would be 
//true - for some reason javascript hasn’t yet registered the click so it still thinks it is false.
//This also occurs if the box is already checked - javascript still thinks its checked 
//even after you click it.
	if(selection_status == false){
		uncheckAll(obj_set_name);
	}
	else{
		checkAll(obj_set_name);
	}
}

function checkAll(obj_set_name){
	var checkboxes = document.getElementsByName(obj_set_name);
	var total_boxes = checkboxes.length;

	for(i=0; i<total_boxes; i++ ){
		current_value = checkboxes[i].checked;

		if(current_value == false){
			checkboxes[i].checked = true;
		}
	}
}


function uncheckAll(obj_set_name){
	var checkboxes = document.getElementsByName(obj_set_name);
	var total_boxes = checkboxes.length;

	for(i=0; i<total_boxes; i++ ){
		current_value = checkboxes[i].checked;

		if(current_value == true){
			checkboxes[i].checked = false;
		}
	}
}
