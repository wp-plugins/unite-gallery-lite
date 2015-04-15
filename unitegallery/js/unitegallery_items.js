function UGAdminItems(){
	
	var g_selectedCatID = -1;
	var g_catClickReady = false;
	var g_itemClickReady = false;
	var g_catFieldRightClickReady = true;		//avoid double menu on cat field
	
	var g_itemSpaceX = 20;
	var g_itemSpaceY = 20;
	var g_minHeight = 280, g_maxCatHeight = 450, g_itemsMaxHeight = 0;
	var g_lastSelectedItemID = null;
	var g_settings = new UniteSettingsUG();
	
	var g_temp = {
			isDisableUnselectAll: false,
			disableUnselectTime: 0
	}
	
	var g_objDrag = {
		isDragMode: false,
		isCopyMoveDialog: false,
		isClicked: false,
		isReorderEnabled: false,
		isOverItem:false,
		isExitFirstItem:false,
		clickedItemID:null,
		targetItemID:null,
		targetItemType:null,
		arrItems: [],
		arrItemIDs: [],
		arrInvalidTargetItemIDs: []
	};
	
	var g_objSelectBar = {
		isEnabled: false,
		startX: 0,
		startY: 0,
		mouseX: 0,
		mouseY: 0
	};
	
	
	function ___________GENERAL_FUNCTIONS________________(){}	//sap for outline	
	
	
	/**
	 * write something to debug line
	 */
	function debugLine(html){
		jQuery("#debug_line").show().html(html);
	}
	
	/**
	 * debug drag object
	 */
	function debugLineDrag(){
		var str = "";
		for(key in g_objDrag){
			var value = g_objDrag[key];
			str += key += " : " + value+" ";
		}
		
		debugLine(str);
		trace(g_objDrag, true);
	}
	
	
	/**
	 * get type, item or category of some item
	 */
	function getItemCategoryType(item){
		if(item == null || typeof item == "string")
			return("unknown");
		
		var parentID = item.parent().prop("id");
		switch(parentID){
			case "list_items":
				return("item");
			break;
			case "list_cats":
				return("category");
			break;
			default:
				return("unknown");
			break;
		}
	}

	
	/**
	 * set combo lists from response
	 */
	function setHtmlListCombo(response){
		var htmlItems = response.htmlItems;
		var htmlCats = response.htmlCats;
		
		setHtmlListItems(htmlItems);
		setHtmlListCats(htmlCats);
	}
	
	
	/**
	 * update global height, by of categories and items
	 */
	function updateGlobalHeight(catHeight, itemsHeight){
				
		if(!catHeight)
			var catHeight = getCatsHeight();
		
		if(!itemsHeight)
			var itemsHeight = g_itemsMaxHeight;
		
		if(catHeight > g_maxCatHeight)
			catHeight = g_maxCatHeight;
		
		var maxHeight = catHeight;
		
		if(itemsHeight > maxHeight)
			maxHeight = itemsHeight;
				
		maxHeight += 20;			
		
		if(maxHeight < g_minHeight)
			maxHeight = g_minHeight;
		
		//set list height
		jQuery("#items_list_wrapper").css("height",maxHeight+"px");
		jQuery("#cats_section").css("height",maxHeight+"px");
	}
	
	
	/**
	 * init gallery view
	 */		
	this.initGalleryView = function(selectedCatID){
		
		if(!g_ugAdmin)
			g_ugAdmin = new UniteAdminUG();		
		
		initCategories();
		initItems();
		
		updateGlobalHeight();
		
		//check first item select
		if(selectedCatID)
			selectCategory(selectedCatID);
		else
			checkSelectFirstCategory();
	};
	

	function ___________CONTEXT_MENU_FUNCTIONS________________(){}	//sap for outline	
	
	/**
	 * 
	 * set some menu on mouse position
	 */
	function showMenuOnMousePos(event,objMenu){
		
		var objOffset = jQuery("#galleryw").offset();
		var galleryY = objOffset.top;
		var galleryX = objOffset.left;
		
		var menuX = Math.round(event.pageX - galleryX);
		var menuY = Math.round(event.pageY - galleryY);
		
		jQuery("#gallery_shadow_overlay").show();
		objMenu.css({"left":menuX+"px","top":menuY+"px"}).show();
	}
	
	
	/**
	 * hide all context menus
	 */
	function hideContextMenus(){
		jQuery("#gallery_shadow_overlay").hide();
		jQuery(".content_wrapper .context_menu").hide();
	}
	
	
	/**
	 * 
	 * on item mouse up event handler - start the right click menus
	 */
	function onItemContextMenu(event){
				
		var objItem = jQuery(this);
		var itemID = objItem.data("id");
		var isSelected = isItemSelected(itemID);
		
		var menuID = "#rightmenu_item";
				
		if(isSelected == false){								
			unselectAllItems("onItemContextMenu");
			selectItem(itemID);
			checkSelectRelatedItems();
		}
	
		var numSelected = getNumItemsSelected();
				
		if(numSelected > 1)
			menuID = "#rightmenu_item_multiple";
		event.preventDefault();
		
		var objMenu = jQuery(menuID);
		
		showMenuOnMousePos(event, objMenu);
		
		return(false);
	}
	
	
	/**
	 * on category context menu click
	 */
	function onCategoryContextMenu(event){
		
		g_catFieldRightClickReady = false;
		
		var objCat = jQuery(this);
		var catID = objCat.data("id");
		var objMenu = jQuery("#rightmenu_cat");
		
		objMenu.data("catid",catID);
		showMenuOnMousePos(event, objMenu);
	}
	
	
	/**
	 * on field context menu, open right click field menu if alowed.
	 */
	function onFieldContextMenu(event){
		
		event.preventDefault();
		
		if(g_selectedCatID == -1)
			return(true);
		
		var objMenu = jQuery("#rightmenu_field");
		showMenuOnMousePos(event, objMenu);
		
	}
	
	
	/**
	 * on categories context menu
	 */
	function onCatsFieldContextMenu(event){
		
		if(g_catFieldRightClickReady == false){
			g_catFieldRightClickReady = true;
			return(true);
		}
		
		var objMenu = jQuery("#rightmenu_catfield");
		showMenuOnMousePos(event, objMenu);
	}
	
	
	/**
	 * 
	 * on category contextmenu click
	 */
	function onCategoryContextMenuClick(operation){
		
		var catID = jQuery("#rightmenu_cat").data("catid");
		
		switch(operation){
			case "edit_category":
				editCategoryByID(catID);
			break;
			case "delete_category":
				hideContextMenus();
				removeCategoryByID(catID);
			break;
			default:
				trace("unknown category operation: "+operation);
			break;
		}
		
	}
		
	
	/**
	 * on item context menu click
	 */
	function onItemContextMenuClick(){
		
		var objLink = jQuery(this);
		var operation = objLink.data("operation");
				
		switch(operation){
			case "preview_item":
				previewItemImage();
			break;
			case "copymove_copy":
				onCopyMoveOperationClick("copy");
			break;
			case "copymove_move":
				onCopyMoveOperationClick("move");
			break;
			case "delete_category":
			case "edit_category":
				onCategoryContextMenuClick(operation);
			break;
			case "add_category":
				addCategory();
			break;
            case "select_all":
                selectAllItems();
            break;
            case "add_image":
                addImageItem();
            break;
            case "add_video":
            	addMediaItem();
            break;
            case "edit_item":
				editItem();
			break;
			case "edit_title":
				editItemTitle();
			break;
			case "delete":
				removeSelectedItems();
			break;
			case "duplicate":
				duplicateItems();
			break;
			default:
				trace("unknown operation: "+operation);
			break;
		}
	}
	
	
	/**
	 * init context menu events
	 */
	function initContextMenus(){
		
		//on item right menu click
		jQuery(".content_wrapper .context_menu li a").mouseup(onItemContextMenuClick);
		
		//on item context menu
		jQuery("#list_items").delegate("li","contextmenu",onItemContextMenu);
		
		//on wrapper context menu
		jQuery("#items_list_wrapper").bind("contextmenu",onFieldContextMenu);
		
		//on category context menu
		jQuery("#list_cats").delegate("li","contextmenu",onCategoryContextMenu);
		
		jQuery("#galleryw, #gallery_shadow_overlay").bind("contextmenu",function(event){
			event.preventDefault();
		});
		
		jQuery("#cats_section").bind("contextmenu",onCatsFieldContextMenu);
		
	}
	
	
	function ___________CATEGORIES________________(){}	//sap for outline
	
	/**
	 * get height of the categories list
	 */
	function getCatsHeight(){
		var catsWrapper = jQuery("#cats_section .cat_list_wrapper");
		var height = catsWrapper.height();
		
		return(height);
	}
	
	
	/**
	 * get arr categories
	 */
	function getArrCats(){
		var arrCats = jQuery("#list_cats li").get();
		return(arrCats);
	}
	
	
	/**
	 * get num categories
	 */
	function getNumCats(){
		var numCats = jQuery("#list_cats li").length;
		return(numCats);
	}
	
	
	/**
	 * 
	 * get category by id
	 */
	function getCatByID(catID){
		var objCat = jQuery("#category_" + catID);
		return(objCat);
	}
	
	
	/**
	 * check if some category selected
	 * 
	 */
	function isCatSelected(catID){
		if(catID == g_selectedCatID)
			return(true);
		
		return(false);
	}
	
	
	
	/**
	 * remove category from html
	 */
	function removeCategoryFromHtml(catID){
		
		jQuery("#category_"+catID).remove();
		
		if(catID == g_selectedCatID)
			g_selectedCatID = -1;
		
		disableCatButtons();
	}
	
	
	
	
	
	/**
	 * 
	 * open the edit category dialog by category id
	 */
	function editCategoryByID(catID){
		var cat = getCatByID(catID);
		
		if(cat.length == 0){
			trace("category with id: " + catID + " don't exists");
			return(false);
		}
		
		var dialogEdit = jQuery("#dialog_edit_category");
		
		dialogEdit.data("catid", catID);
		
		//update catid field		
		jQuery("#span_catdialog_id").html(catID);
		
		var title = cat.data("title");
		
		jQuery("#input_cat_title").val(title).focus();
			
		var buttonOpts = {};
		
		buttonOpts[g_text.cancel] = function(){
			jQuery("#dialog_edit_category").dialog("close");
		};
		
		buttonOpts[g_text.update] = function(){							
			updateCategoryTitle();
		};
		
		
		jQuery("#dialog_edit_category").dialog({
			buttons:buttonOpts,
			minWidth:500,
			modal:true,
			open:function(){
				jQuery("#input_cat_title").select();
			}
		});
	}
	
	
	
	
	
	/**
	 * set html cats list
	 */
	function setHtmlListCats(htmlCats){
		
		jQuery("#list_cats").html(htmlCats);
		
	}
	
	/**
	 * select some category by id
	 */
	function selectCategory(catID){
		
		var cat = jQuery("#category_"+catID);
		if(cat.length == 0){
			return(false);
			//g_ugAdmin.showErrorMessage("category with id: "+catID+" not found");
			return(false);
		}
		
		cat.removeClass("item-hover");
		
		if(cat.hasClass("selected-item"))
			return(false);
		
		g_selectedCatID = catID;
		
		jQuery("#list_cats li").removeClass("selected-item");
		cat.addClass("selected-item");
		enableCatButtons();
		
		getSelectedCatItems();
		
		unselectAllItems("selectCategory");		
	}
	
	
	/**
	 * set first category selected
	 */
	function selectFirstCategory(){
		var arrCats = getArrCats();
		if(arrCats.length == 0)
			return(false);
		
		var firstCat = arrCats[0];
		var catID = jQuery(firstCat).data("id");
		selectCategory(catID);
	}
	
	/**
	 * check if number of cats = 1, if do, select it
	 */
	function checkSelectFirstCategory(){
		var arrCats = getArrCats();
		if(arrCats.length == 1)
			selectFirstCategory();
	}
	
	/**
	 * add category
	 */
	function addCategory(){
		
		ajaxRequestGallery("add_category","",g_text.adding_category,function(response){
			
			var html = response.htmlCat;
			
			jQuery("#list_cats").append(html);
			
			//update html cats select
			var htmlSelectCats = response.htmlSelectCats;
			jQuery("#select_item_category").html(htmlSelectCats);
			
			updateGlobalHeight();
		});		
	}
	
	
	/**
	 * remove some category by id
	 */
	function removeCategoryByID(catID){
		 
		if(confirm(g_text.do_you_sure_remove) == false)
			return(false);
		
		var data = {};
		data.catID = catID;
		
		//get if selected category will be removed
		var isSelectedRemoved = (catID == g_selectedCatID);
		
		ajaxRequestGallery("remove_category",data,g_text.removing_category,function(response){
			removeCategoryFromHtml(catID);
			
			//update html cats select
			var htmlSelectCats = response.htmlSelectCats;
			jQuery("#select_item_category").html(htmlSelectCats);
			
			//clear the items panel
			if(isSelectedRemoved == true){
				clearItemsPanel();
				checkSelectFirstCategory();
			}
			
			updateGlobalHeight();
			
		});
		
	}
	
	
	/**
	 * function invoke from the dialog update button
	 */
	function updateCategoryTitle(){
		var dialogEdit = jQuery("#dialog_edit_category");
		
		var catID = dialogEdit.data("catid");		
		
		var cat = getCatByID(catID);
		
		var numItems = cat.data("numitems");
		
		var newTitle = jQuery("#input_cat_title").val();
		var data = {
			catID: g_selectedCatID,
			title: newTitle
		};
		
		dialogEdit.dialog("close");
		
		var newTitleShow = newTitle;
		if(numItems && numItems != undefined && numItems > 0)
			newTitleShow += " ("+numItems+")";
			
		cat.html("<span>" + newTitleShow + "</span>");
		
		cat.data("title",newTitle);
		
		ajaxRequestGallery("update_category",data,g_text.updating_category);
	}
	
	/**
	 * init the categories actions
	 */
	function initCategories(){
		
		getCatsHeight();
		
		//add category
		jQuery("#button_add_category").click(addCategory);
		
		//remove category:
		jQuery("#button_remove_category").click(function(){
			if(!g_ugAdmin.isButtonEnabled(this))
				return(false);
			
			if(g_selectedCatID == -1)
				return(false);
			
			removeCategoryByID(g_selectedCatID);
		});
		
		
		//edit category
		jQuery("#button_edit_category").click(function(){
			if(!g_ugAdmin.isButtonEnabled(this))
				return(false);
			
			if(g_selectedCatID == -1)
				return(false);
			
			editCategoryByID(g_selectedCatID);
		});
		
		//list categories actions
		jQuery("#list_cats").delegate("li", "mouseover", function() {
			jQuery(this).addClass("item-hover");
			
		});
		
		jQuery("#list_cats").delegate("li", "mouseout", function() {
			jQuery(this).removeClass("item-hover");
		});
		
		jQuery("#list_cats").delegate("li", "click", function(event) {
			
	    	if(g_ugAdmin.isRightButtonPressed(event))
	    		return(true);
			
			if(g_catClickReady == false)
				return(false);
			
			if(jQuery(this).hasClass("selected-item"))
				return(false);
			
			var catID = jQuery(this).data("id");
			selectCategory(catID);
		});
		
		
		jQuery("#list_cats").delegate("li", "mousedown", function(event) {
			
			if(g_ugAdmin.isRightButtonPressed(event))
				return(true);
			
			g_catClickReady = true;
		});
		
		//update sortable categories		
		jQuery( "#list_cats" ).sortable({
			axis:'y',
			start: function( event, ui ) {
				g_catClickReady = false;
			},
			update: function(){
				updateCatOrder();
				//save sorting order
			}
		});		
		
		// set update title onenter function
		jQuery("#input_cat_title").keyup(function(event){
			if(event.keyCode == 13)
				updateCategoryTitle();
		});
		
	}
	
	
	function ___________ITEMS_DRAGGING________________(){}	//sap for outline	
	
	/**
	 * get item that the mouse if over them
	 * if over field, return string - "down"
	 */
	function getMouseOverItem(){
		
		//check mouseover items
		var arrItems = jQuery("#list_items li").get();
		
		for(var index in arrItems){
			var objItem = arrItems[index];
			objItem = jQuery(objItem);
			
			var isMouseOver = objItem.ismouseover();
			if(isMouseOver == true)
				return(objItem);
		}
		
		//check mouseover categories
		var arrCats = getArrCats();
		
		for(var index in arrCats){
			var objCat = arrCats[index];
			objCat = jQuery(objCat);
			
			var isMouseOver = objCat.ismouseover();
			if(isMouseOver == true)
				return(objCat);
		}
		
		
		//check if down enabled:
		var isOverField = jQuery("#items_list_wrapper").ismouseover();
		
		if(isOverField == true)
			return("down");
		
		return(null);
	}
	
	
	/**
	 * hide drag icons
	 */
	function hideVisualDragData(){
		
		//hide icons and target indicator
		var objIndicator = jQuery("#drag_indicator");
		
		objIndicator.hide();
		jQuery("#galleryw").css("cursor","default");
	}
	
	
	/**
	 * show the drag icon, set to mouse position.
	 */
	function operateDragIcons(event){
		
		if(g_objDrag.isExitFirstItem == false)
			return(false);
		
		if(g_objDrag.isDragMode == false){
			jQuery("#galleryw").css("cursor","default");
			//objIcon.hide();
			return(false);
		}
		
		//show not alowed icon
		if(g_objDrag.isOverItem == true && g_objDrag.isReorderEnabled == false){
			jQuery("#galleryw").css("cursor","no-drop");
			
		}else{	//show drag icon
			var cursorType = "move";
			if(g_objDrag.targetItemType == "category")
				cursorType = "copy";
			
			jQuery("#galleryw").css("cursor",cursorType);
		}
		
	}
	
	
	/**
	 * 
	 * indicate drag indicator by the item that the mouse is over it
	 */
	function operateDragIndicator(objItem, itemType){
		
		var objIndicator = jQuery("#drag_indicator");		
		
		if(objItem == null || objItem == "down" || g_objDrag.isDragMode == false || g_objDrag.isReorderEnabled == false || itemType == "category"){ 
			
			objIndicator.html("");
			objIndicator.hide();			
			return(false);
		}
		
		//set gap from item start
		var gapX = -70;
		var gapY = 10;
		
		//var id = objItem.data("id");
		var pos = objItem.position();
		//var itemWidth = objItem.width();
		var posX = Math.round(pos.left + gapX);
		var posY = pos.top + gapY;

		//set indicatory text
		if(objIndicator.html() == ""){
			var arrDraggingItems = g_objDrag.arrItems;
			var numItems = arrDraggingItems.length;
			if(numItems == 1){
				var objDraggingItem = jQuery(arrDraggingItems[0]);
				var html = objDraggingItem.data("title");
				objIndicator.html(html);
			}else{			
				var html = numItems + " items";
				objIndicator.html(html);
			}
		}
		
		//set indicator position
		objIndicator.show();
		objIndicator.css({"top":posY,"left":posX});
		
		//debugLine(id);
	} 
	
	
	/**
	 * check if target item valid for reorder
	 * item type can be category / item
	 */
	function isDragTargetItemValid(targetItemID, itemType){
		if(g_objDrag.isDragMode == false)
			return(false);
		
		//if it's category, drag allowed to non selected only
		if(itemType == "category"){
			if(isCatSelected(targetItemID))
				return(false);
			else
				return(true);
		}
		
		if(g_objDrag.arrInvalidTargetItemIDs.indexOf(targetItemID) == -1)
			return(true);
		
		return(false);
	}
	
	
	/**
	 * get invalid for target reorder put item id's
	 */
	function getInvalidTargetItemIDs(){
		
		var arrAll = getArrItemIDs();
		var arrSelected = g_objDrag.arrItemIDs;
		
		var arrInvalid = [];
		for(var index in arrAll){
			var itemID = arrAll[index];
			
			//check if the item is selected
			if(arrSelected.indexOf(itemID) != -1){
				arrInvalid.push(itemID);
				continue;
			}
			
			//check if previous item is selected
			if(index == 0)
				continue;
			
			var prevItemID = arrAll[index-1];
			if(arrSelected.indexOf(prevItemID) != -1)
				arrInvalid.push(itemID);
		}
		
		var lastItem = arrAll[arrAll.length-1];
		
		//check if can move down
		if( arrSelected.length == 1 && arrSelected[0] == lastItem )
			arrInvalid.push("down");
			
		return(arrInvalid);
	}
	
	
	/**
	 * on item mouse down - initiate drag functionality
	 */
	function onItemMouseDown(event){
		
		if(g_ugAdmin.isRightButtonPressed(event))
			return(true);
		
		var objItem = jQuery(this);
		var itemID = objItem.data("id");
		g_objDrag.arrItems = [];
		g_objDrag.arrItemIDs = [];
		
		var isSelected = isItemSelected(itemID);
	    g_objDrag.clickedItemID = itemID;
	    
		if(isSelected){
			g_objDrag.arrItems = getSelectedItems();
			g_objDrag.arrItemIDs = getSelectedItemIDs();
		}else{				
			g_objDrag.arrItems.push(objItem);
			g_objDrag.arrItemIDs.push(itemID);
		}
		
		g_objDrag.arrInvalidTargetItemIDs = getInvalidTargetItemIDs(); 
		
		g_objDrag.isClicked = true;
	}
	
	
	/**
	 * on wrapper mouse move event
	 */
	function onGalleryMouseMove(event){
		
		//set drag mode and exit first item vars
		if(g_objDrag.isClicked == true && g_objDrag.isDragMode == false){
			g_objDrag.isExitFirstItem = false;
			g_objDrag.isDragMode = true;
		}
		
		if(g_objDrag.isDragMode == true){
			
			var objDraggingTargetItem = getMouseOverItem();
			var itemType = getItemCategoryType(objDraggingTargetItem);
			
			g_objDrag.targetItemType = itemType;
			
			//if the mouse over item, check if it's valid for reordering
			if(objDraggingTargetItem != null){
				
				var targetItemID;
				if(objDraggingTargetItem == "down")
					targetItemID = "down";
				else					
					targetItemID = objDraggingTargetItem.data("id");
				
				if(g_objDrag.isExitFirstItem == false){
					if(g_objDrag.clickedItemID != targetItemID)
						g_objDrag.isExitFirstItem = true;
				}
				
				g_objDrag.isOverItem = true;
				var isValid = isDragTargetItemValid(targetItemID, itemType);
				
				g_objDrag.isReorderEnabled = isValid;
				
				if(isValid)
					g_objDrag.targetItemID = targetItemID;
				else
					g_objDrag.targetItemID = null;
				
			}else{		//if mouse not over item - reorder not enabled				
				g_objDrag.isReorderEnabled = false;
				g_objDrag.isOverItem = false;
				g_objDrag.isExitFirstItem = true;
				g_objDrag.targetItemID = null;
			}
						
			operateDragIndicator(objDraggingTargetItem, itemType);
		}
		
		operateDragIcons(event);		
	}
	
	
	/**
	 * reorder items after dragging.
	 */
	function reorderItemsAfterDrag(){
		
		var targetID = g_objDrag.targetItemID;
		
		if(targetID == null)
			return(false);
		
		var arrIDs = getArrItemIDs();
		var arrSelectedIDs = g_objDrag.arrItemIDs;
				
		//create new array of item id's
		var arrNew = [];
		for(var index in arrIDs){
			var itemID = arrIDs[index];
			if(arrSelectedIDs.indexOf(itemID) != -1)
				continue;
			
			if(itemID == targetID){
				arrNew = arrNew.concat(arrSelectedIDs);
			}
			
			arrNew.push(itemID);
		}
		
		//move down selected items
		if(targetID == "down")
			arrNew = arrNew.concat(arrSelectedIDs);
		
		var objList = jQuery("#list_items");
		var objTempList = jQuery("<ul></ul>");
		
		//create new list item
		for(var index in arrNew){
			var itemID = arrNew[index];
			var item = getItemByID(itemID);
			objTempList.append(item);
		}
		
		//objTempList
		
		objTempList.children().each(function(){
			objList.append(this);
		});
		
		updateItemPositions(true);
		unselectAllItems("reorder");
		
		return(true);
	}
	
	
	
	/**
	 * on body mouse up event
	 * check items reordering
	 */
	function onBodyMouseUp(event){
		
		if(g_ugAdmin.isRightButtonPressed(event))
			return(true);
		
		var hideMenus = true;
		
		//debugLineDrag();
		
		g_objDrag.isClicked = false;
		
		if(g_objDrag.isDragMode == true){
			
			//reorder or disable drag mode
			if(g_objDrag.isReorderEnabled == true && g_objDrag.isExitFirstItem == true){
				
				//in case of category
				if(g_objDrag.targetItemType == "category"){
					
					startCopyMoveDialogMode(event);
					hideMenus = false;
					
				}else{	//in case of item
					var isReordered = reorderItemsAfterDrag();
					if(isReordered == true)
						updateItemsOrder();
					
				}
			}
		}
		
		if(g_objDrag.targetItemType != "category" || g_objDrag.isReorderEnabled == false)
			resetDragData();
		
		if(hideMenus == true){
			hideContextMenus();
			if(g_objDrag.isCopyMoveDialog == true)
				resetDragData();
		}
		
	}
	
	
	/**
	 * clear all the drag data
	 */
	function resetDragData(){
		
		g_objDrag.isDragMode = false;
		g_objDrag.isCopyMoveDialog = false;
		g_objDrag.arrItemIDs = [];
		g_objDrag.arrItems = [];
		g_objDrag.clickedItemID = null;
		g_objDrag.isClicked = false;
		g_objDrag.isExitFirstItem = false;
		g_objDrag.isOverItem = false;
		g_objDrag.isReorderEnabled = false;
		g_objDrag.targetItemID = null;
		g_objDrag.arrInvalidTargetItemIDs = [];
		
		hideVisualDragData();
	}
	
	
	/**
	 * 
	 * on body mousemove
	 */
	function onBodyMouseMove(event){
		//debugLineDrag();
		
		if(jQuery("#galleryw").ismouseover() == false){
			operateDragIndicator(null);
			g_objDrag.isOverItem = false;
			g_objDrag.targetItemID = null;
		}
		
	}
	
	
	/**
	 * start copy / move dialog mode, when dragging to some category
	 */
	function startCopyMoveDialogMode(event){
		g_objDrag.isDragMode = false;
		g_objDrag.isCopyMoveDialog = true;
		
		var objMenu = jQuery("#menu_copymove");

		showMenuOnMousePos(event, objMenu);
	}
	
	
	/**
	 * make some copy/move operation and close the dialog
	 */
	function onCopyMoveOperationClick(operation){
		
		var data = {};
		data.operation = operation;
		data.targetCatID = g_objDrag.targetItemID;
		data.selectedCatID = g_selectedCatID;
		data.arrItemIDs = g_objDrag.arrItemIDs;
		
		copyMoveItems(data);
		
		//trace(operation);
		resetDragData();
	}
	
	
	/**
	 * init items drag events
	 */
	function initItemsDragEvents(){
		
		// on body mousemove - operate icon hide
		jQuery("body").mousemove(onBodyMouseMove);
		
		//on list wrapper mousemove - operate dragging targetcheck  
		jQuery("#galleryw").mousemove(onGalleryMouseMove);
		
		
		//on body mouseup
		jQuery("body").mouseup(onBodyMouseUp);
		jQuery("body").mousedown(function(event){
			
			if(g_ugAdmin.isRightButtonPressed(event))
				return(true);
		});
		
		//on item mousedown
		jQuery("#list_items").delegate("li","mousedown",onItemMouseDown);
		
	}
	
	
	function ___________SELECT_BAR_FUNCTIONS________________(){}	//sap for outline	
	
	/**
	 * get all the data for the select bar
	 */
	function getSelectBarData(){
		
		var data = {};
		if(g_objSelectBar.mouseX > g_objSelectBar.startX){
			data.left = g_objSelectBar.startX;
			data.right = g_objSelectBar.mouseX;			
		}else{
			data.left = g_objSelectBar.mouseX;						
			data.right = g_objSelectBar.startX;
		}
		
		if(g_objSelectBar.mouseY > g_objSelectBar.startY){
			data.top = g_objSelectBar.startY;
			data.bottom = g_objSelectBar.mouseY;
		}else{
			data.top = g_objSelectBar.mouseY; 
			data.bottom = g_objSelectBar.startY;
		}
		
		data.width = Math.round(data.right - data.left);
		data.height = Math.round(data.bottom - data.top);
		
		//fix position by field position
		var objField = jQuery("#items_list_wrapper");
		var objOffset = objField.offset();
		var fieldY = objOffset.top;
		var fieldX = objOffset.left;
		
		data.top = Math.round(data.top - fieldY);
		data.left = Math.round(data.left - fieldX);
		
		data.right = Math.round(data.right - fieldX);
		data.bottom = Math.round(data.bottom - fieldY);
		
		return(data);
	}
	
	
	/**
	 * draw select bar
	 */
	function drawSelectBar(){
		var data = getSelectBarData();
		
		//draw the bar
		var css = {
			"left": data.left+"px",
			"top": data.top+"px",
			"width": data.width+"px",
			"height": data.height+"px"
		};
				
		jQuery("#select_bar").show().css(css);
	}
	
	/**
	 * hide the select bar
	 */
	function hideSelectBar(){
		jQuery("#shadow_bar").hide();
		jQuery("#select_bar").hide();
	}
	
	
	/**
	 * get overlap size of 2 objects 
	 */
	function getOverlapSize(item){
		
		var barData = getSelectBarData();
		
		 var d0 = item.position(),
         x11 = d0.left,
         y11 = d0.top,
         x12 = d0.left + item.width(),
         y12 = d0.top + item.height(),
         x21 = barData.left,
         y21 = barData.top,
         x22 = barData.right,
         y22 = barData.bottom,     
         x_overlap = Math.max(0, Math.min(x12,x22) - Math.max(x11,x21)),
         y_overlap = Math.max(0, Math.min(y12,y22) - Math.max(y11,y21)),
         size = x_overlap * y_overlap;
		 
		 return(size);
	}
	
	
	/**
	 * select item by the select bar position
	 * mode - normal / shift / control
	 */
	function runSelectBarSelection(mode){
				
		if(!mode)
			var mode = "normal";
		
		switch(mode){
			case "shift":
			break;
			case "control":
			break;								
			case "normal":
				unselectAllItems("runSelectBarSelection");
			break;
			default:
				trace("unknown selection mode");
				return(false);
			break;
		}
		
		var objBar = jQuery("#select_bar");
		var arrItems = getArrItems();
		
		for(var index in arrItems){
			var objItem = jQuery(arrItems[index]);
			var overlapSize = getOverlapSize(objItem);
					
			if(overlapSize != 0){
				 var itemID = objItem.data("id");
				 switch(mode){
					case "shift":
					case "normal":
						 selectItem(itemID);
					break;
					case "control":
						if(isItemSelected(itemID))
							 unselectItem(itemID);						 
						 else
							 selectItem(itemID);
					 break;
					 
				 }
			}
		}
		
		checkSelectRelatedItems();
	}
	
	
	
	
	/**
	 * init the events of the select bar
	 */
	function initSelectBar(){
		
		//on wrapper mouse down
		jQuery("#items_list_wrapper").mousedown(function(event){
						
			if(g_ugAdmin.isRightButtonPressed(event))
				return(true);
			
			var itemOver = getMouseOverItem();
			
			if(itemOver != "down")
				return(true);
			
			g_objSelectBar.isEnabled = true;
			
			jQuery("#shadow_bar").show();
			
			g_objSelectBar.startX = event.pageX;
			g_objSelectBar.startY = event.pageY;
		});
		
		
		//on body mousemove - draw the bar
		jQuery("body").mousemove(function(event){
			
			if(g_objSelectBar.isEnabled == true){
				if(event.buttons == 0){
					hideSelectBar();
					g_objSelectBar.isEnabled = false;
				}else{
					g_objSelectBar.mouseX = event.pageX;
					g_objSelectBar.mouseY = event.pageY;
					drawSelectBar();
				}
			}
			
		});
		
		
		// on body mouse up - stop the bar and check selection
		jQuery("body").mouseup(function(event){
						
			if(g_objSelectBar.isEnabled == true){
				event.stopPropagation();
				
				hideSelectBar();								
				g_objSelectBar.mouseX = event.pageX;
				g_objSelectBar.mouseY = event.pageY;
				var mode = "normal";				
				if(event.shiftKey)
					mode = "shift";
				else if(event.ctrlKey)
					mode = "control";
							
				runSelectBarSelection(mode);
				
				g_objSelectBar.isEnabled = false;
				
				disableUnselectAllItems();
			}
				
		});
		
		
	}
	

	
	
	function ___________ITEMS_FUNCTIONS________________(){}	//sap for outline	
	
	
	/**
	 * get selected item id's
	 */
	function getSelectedItemIDs(){
		var arrIDs = getArrItemIDs(true);
		return(arrIDs);
	}
	
	
	/**
	 * get array of all item id's
	 */
	function getArrItemIDs(selectedOnly){
		if(!selectedOnly)
			var selectedOnly = false;
		
		var selector = "#list_items li";
		if(selectedOnly == true)
			selector = "#list_items li.item-selected";
		
		var arrIDs = [];
		jQuery(selector).each(function(){
			var itemID = jQuery(this).data("id"); 
			arrIDs.push(itemID); 
		});

		return(arrIDs);
		
	}
	
	/**
	 * get items array
	 */
	function getArrItems(){
		var arrItems = jQuery("#list_items li").get();
		return(arrItems);
	}
	
	/**
	 * get selected items
	 */
	function getSelectedItems(){
		var arrItems = jQuery("#list_items li.item-selected").get();
		return(arrItems);
	}
	
	
	/**
	 * get if multiple items or single items selected
	 */
	function getNumItemsSelected(){
		var numSelected = jQuery("#list_items li.item-selected").length;
		return(numSelected);
	}
	
	/**
	 * get num items in items panel
	 */
	function getNumItems(){
		var numItems = jQuery("#list_items li").length;
		return(numItems);		
	}
	
	
	/**
	 * get item by itemID
	 */
	function getItemByID(itemID){
		var objItem = jQuery("#item_"+itemID);
		return(objItem);
	}
	
		
    /**
     * preview image of the selected item
     */
    function previewItemImage(){
    	var arrSelectedItems = getSelectedItems();
    	if(arrSelectedItems.length != 1)
    		return(false);

    	//get image url
    	var objItem = jQuery(arrSelectedItems[0]);    	
		
    		var itemType = objItem.data("type");
    		var urlImage = objItem.data("image");
    		
    		var fancyType = "image";
    		var fancyHref = urlImage;
    		var fancyContent = "";
    		
    		switch(itemType){
    			default:
    		   		//skip items without images
    	    		if(urlImage == "")
    	    			return(false);				
    			break;
    			case "youtube":
    				fancyType = "iframe";
    				var videoID = objItem.data("videoid");
    	    		fancyHref = "https://www.youtube.com/embed/"+videoID+"?autoplay=1";
    			break;
    			case "vimeo":
    				fancyType = "iframe";
    				var videoID = objItem.data("videoid");
    	    		fancyHref = "http://player.vimeo.com/video/"+videoID+"?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1";
    			break;
    			case "wistia":
    				fancyType = "iframe";
    				var videoID = objItem.data("videoid");
    	    		fancyHref = "https://fast.wistia.net/embed/iframe/"+videoID;
    			break;
    			case "html5video":
    				var videoMp4 = objItem.data("mp4");
    				var videoWebm = objItem.data("webm");
    				var videoOgv = objItem.data("ogv");
    				
    				fancyType = "inline";
    				fancyContent = '<video autoplay="autoplay" preload="none" width="800" height="450" controls="controls"><source  src="'+videoMp4+'" type="video/mp4"><source src="'+videoWebm+'" type="video/webm"><source src="'+videoOgv+'" type="video/ogg"></video>';
    			break;
    		}
    		
    		var fancyboxItem = {
        	        type: fancyType,
        	        href: fancyHref,
        	        content: fancyContent,
        	        maxHeight: 450,
        	        title: objItem.data("title")
       		};
    		
    	
		jQuery.fancybox(fancyboxItem);
    }
    
    
    /**
     * 
     * select items by shift mode
     */
    function selectItemsShiftMode(itemID){
    	
    	var arrSelectedItems = getSelectedItems();
    	var arrIDs = getArrItemIDs();
    	
    	//select only one item
    	if(g_lastSelectedItemID == null || arrSelectedItems.length == 0){
    		
    		selectItem(itemID);
    		
    	}else{
    		
    		//select row of items
    		
        	var indexLast = arrIDs.indexOf(g_lastSelectedItemID);
        	var indexCurrent = arrIDs.indexOf(itemID);
        	if(indexLast == -1 || indexCurrent == -1 || indexLast == indexCurrent)
        		return(false);
        	
        	var firstIndex = indexLast;    	
        	var secondIndex = indexCurrent;
        	if(firstIndex > secondIndex){
            	firstIndex = indexCurrent;
            	secondIndex = indexLast;    		
        	}
        	
        	unselectAllItems("selectItemsShiftMode");
        	
        	for(var index = firstIndex; index <= secondIndex; index++){
        		var itemID = arrIDs[index];
        		selectItem(itemID);
        	}
    		
    	}  
    }
    
    
    /**
     * remove selected items
     */
    function removeSelectedItems(){
		if(g_ugAdmin.isButtonEnabled(this) == false)
			return(false);
		
		if(confirm(g_text.confirm_remove_items) == false)
			return(false);
		
		var arrIDs = getSelectedItemIDs();
		
		removeItems(arrIDs);
    }
	
	
	
	/**
	 * check all select related items
	 */
	function checkSelectRelatedItems(){
		
		var numSelected = getNumItemsSelected();
		var arrItems = getArrItems();
		var buttonSelectAll = jQuery("#button_select_all_items");
		
		//operate top buttons
		if(numSelected == 0){
			
			disableItemButtons();
			
		}else{
			
			g_ugAdmin.enableButton("#gallery_buttons a");
						
			if(numSelected > 1){
				g_ugAdmin.disableButton("#button_edit_item");
				g_ugAdmin.disableButton("#button_edit_item_title");
			}
		}
		
		//check the select all button
		var textSelect = buttonSelectAll.data("textselect");
		var textUnselect = buttonSelectAll.data("textunselect");
		
		if(numSelected > 0 && numSelected == arrItems.length){
			buttonSelectAll.html(textUnselect);
		}else{
			buttonSelectAll.html(textSelect);
		}
		
		//update bottom operations
		updateBottomOperations();
	}
	
	
	/**
	 * update bottom operations
	 */
	function updateBottomOperations(){
		
		var numSelected = getNumItemsSelected();
		var numCats = getNumCats();
		
		jQuery("#num_items_selected").html(numSelected);
				
		//in case of less then 2 cats - disable operations
		if(numCats <= 1){
			
			jQuery("#item_operations_wrapper").hide();
			return(false);
		}
		
		//in case of more then one cat
		jQuery("#item_operations_wrapper").show();
		
		if(numSelected > 0){
			jQuery("#select_item_operations, #select_item_category").prop("disabled","");
			jQuery("#button_items_operation").removeClass("button-disabled");
		}else{		//disable operations
			jQuery("#select_item_operations, #select_item_category").prop("disabled","disabled");
			jQuery("#button_items_operation").addClass("button-disabled");
		}
		
		//hide / show operation categories 
		jQuery("#select_item_category option").show();
		var arrOptions = jQuery("#select_item_category option").get();
		
		var firstSelected = false;
		for(var index in arrOptions){
			var objOption = jQuery(arrOptions[index]);
			var value = objOption.prop("value");
			
			if(value == g_selectedCatID)
				objOption.hide();
			else
				if(firstSelected == false){
					objOption.attr("selected","selected");
					firstSelected = true;
				}
		}
			
		
	}
	
	/**
	 * disable item buttons
	 */
	function disableItemButtons(){
		g_ugAdmin.disableButton('#gallery_buttons a');
		if(g_selectedCatID == -1)
			return(false);
		
		g_ugAdmin.enableButton("#button_add_images, #button_add_video");
		
		var numItems = getNumItems();
		if(numItems > 0)
			g_ugAdmin.enableButton("#button_select_all_items");
	}
	
	
	/**
	 * check if the item selected
	 */
	function isItemSelected(itemID){		
		var item = getItemByID(itemID);
		
		if(item.length == 0)
			return(false);
		
		if(item.hasClass("item-selected"))
			return(true);
		else
			return(false);
	}
	
	/**
	 * disable unselect all items for a second
	 */
	function disableUnselectAllItems(){
		
		g_temp.isDisableUnselectAll = true;
		g_temp.disableUnselectTime = jQuery.now();
				
	}
	
	
	/**
	 * unselect all items
	 */
	function unselectAllItems(fromWhere){
				
		//don't do the unselect operation if command given
		if(g_temp.isDisableUnselectAll == true){
			var currentTime = jQuery.now();
			var diff = currentTime - g_temp.disableUnselectTime;
			g_temp.isDisableUnselectAll = false;

			if(diff < 100)
				return(true);
		}
		
		jQuery("#list_items li").removeClass("item-selected").removeClass("item-hover");
		checkSelectRelatedItems();
		
	}
	
	
	/**
	 * select all items
	 */
	function selectAllItems(){
		jQuery("#list_items li").addClass("item-selected");
		g_lastSelectedItemID = null;
		checkSelectRelatedItems();
	}
		
	
	/**
	 * unselect some item
	 */
	function unselectItem(itemID){
		var item = getItemByID(itemID);
		if(item.length == 0)
			return(false);
		
		item.removeClass("item-selected");
		
		//trace("unselect");
	}
	
	
	/**
	 * select some item
	 */
	function selectItem(itemID){
				
		var item = getItemByID(itemID);
		
		if(item.length == 0)
			return(false);
				
		item.addClass("item-selected");
	}
	
	
	/**
	 * 
	 * clear the items panel, and the buttons too.
	 */
	function clearItemsPanel(){
				
		jQuery("#items_loader").hide();
		jQuery("#list_items").hide();
		jQuery("#no_items_text").hide();
		
		checkSelectRelatedItems();
		
		g_selectedCatID = -1;
	}
	
	
	/**
	 * update positions of the items
	 */
	function updateItemPositions(isFancy){
		if(!isFancy)
			var isFancy = false;
		
		var marginX = 20;
		var marginY = 20;
		
		var objField = jQuery("#items_list_wrapper");
		
		var fieldWidth = objField.width();
		var startPosx = marginX;
		var startPosy = marginY;
		var maxHeight = 0;
				
		jQuery("#list_items li").each(function(){
			
			var objItem = jQuery(this);
			var itemWidth = objItem.width();
			var itemHeight = objItem.height();
			
			var endPosX = startPosx + itemWidth;
			
			if(endPosX > (fieldWidth - marginX)){
				startPosx = marginX;
				startPosy += itemHeight + g_itemSpaceY;
			}
			
			if(isFancy == true){
				objItem.animate({"left":startPosx, "top":startPosy+"px"},300,"swing");
			}
			else
				objItem.css({"left":startPosx, "top":startPosy+"px"});
			
			startPosx += itemWidth + g_itemSpaceX;
			
			maxHeight = startPosy + itemHeight + marginY;
			
		});
		
		g_itemsMaxHeight = maxHeight;
		
		updateGlobalHeight(null, maxHeight);
		
	}
	
	
	/**
	 * set list items html
	 */
	function setHtmlListItems(htmlItems){
		
		jQuery("#items_loader").hide();
		
		jQuery("#list_items").html(htmlItems).show();
		
		if(jQuery("#list_items li").length == 0){
			jQuery("#list_items").hide();
			jQuery("#no_items_text").show();
		}
		
		updateItemPositions();
	}
	
	
	/**
	 * init items actions
	 */
	function initItems(){
		
		//add item
		jQuery("#button_add_images").click(function(){
			
			if(g_ugAdmin.isButtonEnabled(this) == false)
				return(false);
			
			addImageItem();	
		});
		
		//add video
		jQuery("#button_add_video").click(function(){
			
			if(g_ugAdmin.isButtonEnabled(this) == false)
				return(false);

			addMediaItem();
		});
		
		//on click
		jQuery("#list_items").delegate("li", "click", onItemClick); 
		
		jQuery("#list_items").delegate("li", "mousedown", function(event) {
			
			if(g_ugAdmin.isRightButtonPressed(event))
				return(true);
						
			g_itemClickReady = true;
		});		
		
		//background click event
		jQuery("#items_list_wrapper").click(function(event){
			
	    	if(g_ugAdmin.isRightButtonPressed(event))
	    		return(true);
	    	
	    	if(g_objSelectBar.isEnabled == true)
	    		return(true);
	    	
			unselectAllItems("items_list_wrapper");
		});
		
		jQuery("#list_items").delegate("li", "mouseover", function() {
			jQuery(this).addClass("item-hover");
		});
		
		//mouse out
		jQuery("#list_items").delegate("li", "mouseout", function() {
			jQuery(this).removeClass("item-hover");
		});
		
		//double click
		jQuery("#list_items").delegate("li", "dblclick", function() {
			editItem();
		});
		
		
		//select all
		jQuery("#button_select_all_items").click(function(){
			var numSelected = getNumItemsSelected();
			var arrItems = getArrItems();
			if(numSelected == arrItems.length)
				unselectAllItems("button_select_all_items");
			else
				selectAllItems();						
		});
		
		
		//remove item
		jQuery("#button_remove_item").click(removeSelectedItems);
		
		//edit item title
		jQuery("#button_edit_item_title").click(function(){
			if(jQuery(this).hasClass("button-disabled"))
				return(false);
			
			editItemTitle();
		});
		
		//edit item button click
		jQuery("#button_edit_item").click(function(){
			if(jQuery(this).hasClass("button-disabled"))
				return(false);
			
			editItem();
		});
		
		//duplicate items
		jQuery("#button_duplicate_item").click(function(){
			if(jQuery(this).hasClass("button-disabled"))
				return(false);
			
			duplicateItems();
		});
		
		
		// do items operations
		jQuery("#button_items_operation").click(onCopyMoveOperationsClick);
		
		
		initItemsDragEvents();
		initSelectBar();
		
		// set update title onenter function
		jQuery("#input_item_title").keyup(function(event){
			if(event.keyCode == 13)
				updateItemTitle();
		});
		
		initContextMenus();
		
	}
	
	/**
	 * replace item html, remember it's position
	 */
	function replaceItemHtml(objItem, newHtml){
		
		var pos = objItem.position();
		var itemID = objItem.prop("id");
		
		objItem.replaceWith(newHtml);
		
		jQuery("#"+itemID).css({"top":pos.top+"px","left":pos.left+"px"});
	}
	
	function ___________ITEMS_EVENTS________________(){}
	
	/**
	 * on item click event
	 */
    function onItemClick(event){
    	
    	if(g_ugAdmin.isRightButtonPressed(event))
    		return(true);
    	
		if(g_itemClickReady == false)
			return(true);
		
		event.stopPropagation()
		
		var itemID = jQuery(this).data("id");
		
		var isMultiple = event.ctrlKey;
				
		if(event.shiftKey == true)
			selectItemsShiftMode(itemID);
		else
		if(event.ctrlKey && isItemSelected(itemID) == true)
			unselectItem(itemID);
		else{
			if(isMultiple == false)		//remove ctrl mode
				unselectAllItems("onItemClick");
			
			selectItem(itemID);				
			g_lastSelectedItemID = itemID;
		}
		
		checkSelectRelatedItems();
    }
	
	
	
	function ___________ITEMS_AJAX_OPERATIONS________________(){}	//sap for outline	
	
	
	/**
	 * get item data from server
	 */
	function getItemData(itemID, callbackFunction){
		
		var data = {itemid:itemID};
		ajaxRequestGallery("get_item_data",data,g_text.loading_item_data,callbackFunction);
	}
	
	/**
	 * get category items
	 */
	function getSelectedCatItems(){
		
		jQuery("#items_loader").show();
		jQuery("#list_items").hide();
		jQuery("#no_items_text").hide();
		
		var data = {catID:g_selectedCatID};
		g_ugAdmin.ajaxRequest("get_cat_items",data,function(response){
			setHtmlListItems(response.itemsHtml);
			checkSelectRelatedItems();
		});
	}
	
	
	/**
	 * request item 
	 */
	function addItem_request(data){
		
		ajaxRequestGallery("add_item",data,g_text.adding_item,function(response){
			
			jQuery("#list_items").show();
			jQuery("#no_items_text").hide();
			
			jQuery("#list_items").append(response.htmlItem);
			
			updateItemPositions();
			
			//update categories list
			setHtmlListCats(response.htmlCats);
			
		});
		
	}
	
	/**
	 * add some item in gallery view
	 */
	function addImageItem(){
		
		if(g_selectedCatID == -1)
			return(false);
				
		g_ugAdmin.openAddImageDialog(g_text.add_image ,function(urlImage, imageID){
			
			var data = {};
			data.type = "image";
			data.catID = g_selectedCatID;
			data.urlImage = urlImage;
			
			if(imageID)
				data.imageID = imageID;
			
			addItem_request(data);
			
		}, true);//open add image dialog
	}
	
	
	/**
	 * add video function
	 */
	function addMediaItem(){
		
		if(g_selectedCatID == -1)
			return(false);
		
		g_ugAdmin.openVideoDialog(function(data){
			data.catID = g_selectedCatID;
			
			addItem_request(data);
		
		});
		
	}
	
	
	/**
	 * update categories order
	 */
	function updateCatOrder(){
		
		//get sortIDs
		var arrSortCats = jQuery( "#list_cats" ).sortable("toArray");
		var arrSortIDs = [];
		for(var i=0;i < arrSortCats.length; i++){
			var catHtmlID = arrSortCats[i];
			var catID = catHtmlID.replace("category_","");
			arrSortIDs.push(catID);
		}
		
		var data = {cat_order:arrSortIDs};
		ajaxRequestGallery("update_cat_order",data,g_text.updating_categories_order);
	}
	
	
	/**
	 * remove items
	 */
	function removeItems(arrIDs){
		var data = {};
		data.arrItemIDs = arrIDs;
		data.catid = g_selectedCatID;
		
		ajaxRequestGallery("remove_items",data, g_text.removing_items, function(response){
			setHtmlListCombo(response);
		});
		
	}
	
	/**
	 * edit item image dialog
	 */
	function editItem_image(objItem){
				
		var itemID = objItem.data("id");
		var itemTitle = objItem.data("title");
				
		objDialog = jQuery("#dialog_edit_item");
		objDialog.data("itemid",itemID);
		
		var dialogTitle = g_text.edit_item + ": " + itemTitle;
		
		var buttonOpts = {};
		
		buttonOpts[g_text.cancel] = function(){
			objDialog.dialog("close");
		};
		
		buttonOpts[g_text.update] = function(){
			
			//validate input:
			var newTitle = jQuery("#ug_item_title").val();
			newTitle = jQuery.trim(newTitle);
			
			if(newTitle == ""){
				jQuery("#dialog_edit_error_message").show().html(g_text.please_fill_item_title);
				return(true);
			}
			
			//update title in html item
			jQuery("#item_"+itemID+" .item_title").html(newTitle);
			jQuery("#item_"+itemID).data("title", newTitle);
			
			jQuery("#dialog_edit_item").dialog("close");
			
			var objItemData = g_settings.getSettingsObject("form_item_settings");
			var data = {itemID: itemID, 
					    params: objItemData};
			
			ajaxRequestGallery("update_item_data",data,g_text.updating_item_data);
			
		};
		
		jQuery("#dialog_edit_item_loader").show();		
		jQuery("#dialog_edit_item_content").html("");
		jQuery("#dialog_edit_error_message").hide();
		
		objDialog.dialog({
			buttons:buttonOpts,
			minWidth:800,
			modal:true,
			title: dialogTitle,
			open:function(){
				
				getItemData(itemID, function(response){
					
					jQuery("#dialog_edit_item_loader").hide();
					jQuery("#dialog_edit_item_content").html(response.htmlSettings);
										
					//update setting object events
					g_settings.updateEvents();
					
					//try to set focus on description
					jQuery("#dialog_edit_item #description").focus();
					
				});
							
			}
		});
	}
	
	
	/**
	 * edit item media dialog
	 */
	function editItem_media(objItem){
		
		var itemTitle = objItem.data("title");
		
		var data = {};
		data.itemID = objItem.data("id");
		data.dialogTitle = g_text.edit_media_item + ": " + itemTitle;
		data.requestFunction = getItemData;
		
		g_ugAdmin.openVideoDialog(function(response){
			
			ajaxRequestGallery("update_item_data",response,g_text.updating_item_data,function(responseUpdate){
				var htmlItem = responseUpdate.html_item;
				replaceItemHtml(objItem, htmlItem);
			});
			
		}, data);
				
	}
	
	
	/**
	 * edit item operation. open quick edit dialog
	 */
	function editItem(){
		
		//get selected item
		var arrItems = getSelectedItems();
		if(arrItems.length != 1)
			return(false);
		
		var objItem = jQuery(arrItems[0]);
		var itemType = objItem.data("type");
		
		switch(itemType){
			case "image":
				editItem_image(objItem);
			break;
			default:		//edit media item
				editItem_media(objItem);
			break;
		}
		
		
	}
	
	
	/**
	 * edit item title function
	 */
	function editItemTitle(){
		
		var arrIDs = getSelectedItemIDs();
		
		if(arrIDs.length == 0)
			return(false);
		
		var itemID = arrIDs[0];
		var title = jQuery("#item_"+itemID).data("title");
		var objDialog = jQuery("#dialog_edit_item_title");
		
		jQuery("#input_item_title").val(title).focus();
		
		var buttonOpts = {};
		
		buttonOpts[g_text.cancel] = function(){
			jQuery("#dialog_edit_item_title").dialog("close");
		};
		
		buttonOpts[g_text.update] = function(){
			updateItemTitle();
		}
		
		objDialog.data("itemid",itemID);
		
		objDialog.dialog({
			buttons:buttonOpts,
			minWidth:500,
			modal:true,
			open:function(){
				jQuery("#input_item_title").select();
			}
		});
		
	}
	
	/**
	 * update item title - on dialog update press
	 */
	function updateItemTitle(){
		var objDialog = jQuery("#dialog_edit_item_title");
		var itemID = objDialog.data("itemid");
		var objTitle = jQuery("#item_"+itemID);
		var titleHolder = jQuery("#item_"+itemID+" .item_title");
		
		var newTitle = jQuery("#input_item_title").val();
		var data = {
			itemID: itemID,
			title: newTitle
		};
		
		objDialog.dialog("close");
		
		//update the items
		objTitle.data("title", newTitle);
		titleHolder.html(newTitle);
		
		ajaxRequestGallery("update_item_title",data,g_text.updating_title);		
	}
	
	/**
	 * duplicate items
	 */
	function duplicateItems(){
		
		var arrIDs = getSelectedItemIDs();
		if(arrIDs.length == 0)
			return(false);
		
		if(g_selectedCatID == -1)
			return(false);
		
		var data = {
				arrIDs: arrIDs,
				catID: g_selectedCatID
		};
		
		ajaxRequestGallery("duplicate_items",data,g_text.duplicating_items,function(response){
			setHtmlListCombo(response);
		});	
	}
	
	
	/**
	 * update items order in server
	 */
	function updateItemsOrder(){
		
		var arrIDs = getArrItemIDs();
		
		var data = {items_order:arrIDs};
		ajaxRequestGallery("update_items_order",data,g_text.updating_items_order);
	}
	
	
	/**
	 * run gallery ajax request
	 */
	function ajaxRequestGallery(action,data,status,funcSuccess){
		
		jQuery("#status_loader").show();
		jQuery("#status_text").show().html(status);
		
		g_ugAdmin.ajaxRequest(action,data,function(response){
			jQuery("#status_loader").hide();
			jQuery("#status_text").hide();
			if(typeof funcSuccess == "function")
				funcSuccess(response);
			
			checkSelectRelatedItems();
		});
		
	}
	
	
	/**
	 * 
	 * on GO button (copy / move) operations click
	 */
	function onCopyMoveOperationsClick(){
			var arrIDs = getSelectedItemIDs();
			if(arrIDs.length == 0)
				return(false);
			
			var targetCatID = jQuery("#select_item_category").val();
			if(targetCatID == g_selectedCatID){
				alert("Can't move items to same category");
				return(false);
			}
			
			var data = {};
			data.operation = jQuery("#select_item_operations").val();
			data.targetCatID = targetCatID;
			data.selectedCatID = g_selectedCatID;
			data.arrItemIDs = arrIDs;
			
			copyMoveItems(data);
	}
	
	/**
	 * copy / move items
	 */
	function copyMoveItems(data){
		
		//set status text
		var text = "";
		switch(data.operation){
			case "copy":
				text = g_text.copying_items;
			break;
			case "move":
				text = g_text.moving_items;
			break;
		}
		
		ajaxRequestGallery("copy_move_items",data,text,function(response){
			setHtmlListCombo(response);
		});
		
	}
	
		
	
	function ___________BUTTONS________________(){}		


	/**
	 * enable category buttons
	 */
	function enableCatButtons(){
		
		//cat butons:
		g_ugAdmin.enableButton("#button_remove_category, #button_edit_category");
		
		//items buttons:
		g_ugAdmin.enableButton("#button_add_images, #button_add_video");
	}
	
	/**
	 * enable category buttons
	 */
	function disableCatButtons(){
		
		g_ugAdmin.disableButton("#button_remove_category, #button_edit_category");
		
		//items buttons:
		g_ugAdmin.disableButton("#button_add_images, #button_add_video");		
	}
	
	
};