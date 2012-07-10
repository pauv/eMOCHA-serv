<link rel="stylesheet" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.grid.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/css/smoothness/jquery-ui-1.8.5.custom.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.base.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.pager.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.columnpicker.css" type="text/css" media="screen" charset="utf-8" />
<style>
		.cell-title {
			font-weight: bold;
		}
		.cell-effort-driven {
			text-align: center;
		}
		.cell-reorder {
			cursor: move;
			background: url("../images/drag-handle.png") no-repeat center center;
		}

        .recycle-bin {
            width: 120px;
            border: 1px solid gray;
            background: beige;
            padding: 4px;
            font-size: 12pt;
            font-weight: bold;
            color: black;
            text-align: center;
            -moz-border-radius: 10px;
        }
	</style>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/lib/jquery.event.drag-2.0.min.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/lib/jquery.event.drop-2.0.min.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/lib/jquery-ui-1.8.5.custom.min.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.grid.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slick.editors.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.model.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.pager.js"></script>
<script language="JavaScript" src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slickgrid/slick.columnpicker.js"></script>

<div id="inner_content">


<h3>This is the list of existing forms. Please click on one to show a table with all received data.</h3>
<?php 

	foreach($forms AS $form) {
		$link = '/stats/exportgrid/'.$form->id;
		echo Html::anchor($link, $form->name).', ';
	}	

?>


<?php if($selected_form_id) { 


	$link = '/stats/export_as_csv/'.$selected_form_id;
	echo '<br /><div>'.Html::anchor($link, 'Download for spreadsheet import (tab separated)').'</div>';


?>

<div style="width:850px;float:left;margin-top:20px" id="datagrid">
			<div class="grid-header" style="width:100%">
				<label>Data</label>
                <span style="float:right" class="ui-icon ui-icon-search" title="Toggle search panel" onclick="toggleFilterRow()"></span>
			</div>
			<div id="myGrid" style="width:100%;height:500px;"></div>
			<div id="pager" style="width:100%;height:20px;"></div>
</div>

<br class="clear_float" />
<script>

		var dataView;
		var grid;
		var data = [];
		var selectedRowIds = [];
		
		<?php 
		// make js friendly ids and labels
		$js_cols = array();
		foreach ($columns as $column) { 
            $js_cols[$column]['id'] = str_replace('.', '_', $column);
            $nodes = explode('.', $column);
            $node_name = $nodes[sizeof($nodes)-1];
            $js_cols[$column]['val'] = $node_name;
      	} ?>

		var columns = [
			{
                id: "#",
                name: "",
                width: 40,
                behavior: "selectAndMove",
                unselectable: true,
                resizable: false,
                cssClass: "cell-reorder dnd"
            }
            <?php foreach ($js_cols as $id=>$col) { 
            	?>,
			{id:"<?php echo $col['id']; ?>", name:"<?php echo $col['val']; ?>", field:"<?php echo $col['id']; ?>", sortable:true}<?php } ?>
		];

		var options = {
			editable: true,
			enableAddRow: true,
			enableCellNavigation: true,
			asyncEditorLoading: true,
			forceFitColumns: false,
            secondaryHeaderRowHeight: 25
		};
		
		
		var sortcol = "";
		var sortdir = 1;
		var searchString = "";
		var searchField = "";

		function requiredFieldValidator(value) {
			if (value == null || value == undefined || !value.length)
				return {valid:false, msg:"This is a required field"};
			else
				return {valid:true, msg:null};
		}

		function myFilter(item) {
			if (searchField=="vgSearch" && searchString != "" && item["village_code"].indexOf(searchString) == -1)
				return false;
			if (searchField=="nmSearch" && searchString != "" && item["first_name"].indexOf(searchString) == -1 && item["last_name"].indexOf(searchString) == -1)
				return false;
			if (searchField=="hcSearch" && searchString != "" && item["household_code"].indexOf(searchString) == -1)
				return false;
			if (searchField=="pcSearch" && searchString != "" && item["code"].indexOf(searchString) == -1)
				return false;

			return true;
		}


		function comparer(a,b) {
			var x = a[sortcol], y = b[sortcol];
			return (x == y ? 0 : (x > y ? 1 : -1));
		}
		
		function addItem(newItem,columnDef) {
			var $item = {};
			$.extend(item,newItem);
			dataView.addItem(item);
		}
		


        function toggleFilterRow() {
            if ($(grid.getSecondaryHeaderRow()).is(":visible"))
                grid.hideSecondaryHeaderRow();
            else
                grid.showSecondaryHeaderRow();
        }


        $(".grid-header .ui-icon")
            .addClass("ui-state-default ui-corner-all")
            .mouseover(function(e) {
                $(e.target).addClass("ui-state-hover")
            })
            .mouseout(function(e) {
                $(e.target).removeClass("ui-state-hover")
            });

		$(function() {
            var data = [];
            <?php 
            $i=0;
            foreach($rows as $row) { ?>
				data[<?php echo $i; ?>] = {
					id: "id_<?php echo $i; ?>"<?php foreach ($js_cols as $id=>$col) { 
						?>,
                    <?php 
                    $val = array_key_exists($id, $row) ? $row[$id] : "";
                    echo $col['id'].": \"".$val."\"";
                    } ?>
                };
			<?php 
				$i++;
			} ?>
			
			dataView = new Slick.Data.DataView();
			grid = new Slick.Grid($("#myGrid"), dataView.rows, columns, options);
			var pager = new Slick.Controls.Pager(dataView, grid, $("#pager"));
			var columnpicker = new Slick.Controls.ColumnPicker(columns, grid, options);
			
			// move the filter panel defined in a hidden div into an inline secondary grid header row
            $("#inlineFilterPanel")
                .appendTo(grid.getSecondaryHeaderRow())
                .show();

            grid.onCellChange = function(row,col,item) {
                dataView.updateItem(item.id,item);    
            };

			grid.onAddNewRow = addItem;

			grid.onKeyDown = function(e) {
                // select all rows on ctrl-a
                if (e.which != 65 || !e.ctrlKey)
                    return false;

                var rows = [];
                selectedRowIds = [];

                for (var i = 0; i < dataView.rows.length; i++) {
                    rows.push(i);
                    selectedRowIds.push(dataView.rows[i].id);
                }

                grid.setSelectedRows(rows);

                return true;
            };

			grid.onSelectedRowsChanged = function() {
                selectedRowIds = [];
                var rows = grid.getSelectedRows();
                for (var i = 0, l = rows.length; i < l; i++) {
                    var item = dataView.rows[rows[i]];
                    if (item) selectedRowIds.push(item.id);
                }
            };

			
			grid.onSort = function(sortCol, sortAsc) {
                sortdir = sortAsc ? 1 : -1;
                sortcol = sortCol.field;
                dataView.fastSort(sortcol,sortAsc);
            };
            

			// wire up model events to drive the grid
			dataView.onRowCountChanged.subscribe(function(args) {
				grid.updateRowCount();
                grid.render();
			});

			dataView.onRowsChanged.subscribe(function(rows) {
				grid.removeRows(rows);
				grid.render();

				if (selectedRowIds.length > 0)
				{
					// since how the original data maps onto rows has changed,
					// the selected rows in the grid need to be updated
					var selRows = [];
					for (var i = 0; i < selectedRowIds.length; i++)
					{
						var idx = dataView.getRowById(selectedRowIds[i]);
						if (idx != undefined)
							selRows.push(idx);
					}

					grid.setSelectedRows(selRows);
				}
			});

			dataView.onPagingInfoChanged.subscribe(function(pagingInfo) {
				var isLastPage = pagingInfo.pageSize*(pagingInfo.pageNum+1)-1 >= pagingInfo.totalRows;
                var enableAddRow = isLastPage || pagingInfo.pageSize==0;
                var options = grid.getOptions();

                if (options.enableAddRow != enableAddRow)
    				grid.setOptions({enableAddRow:enableAddRow});
			});



			var h_runfilters = null;



			// wire up the search textbox to apply the filter to the model
			$("#vgSearch,#hcSearch,#pcSearch,#nmSearch").keyup(function(e) {
                Slick.GlobalEditorLock.cancelCurrentEdit();
				// clear on Esc
				if (e.which == 27)
					this.value = "";

				searchString = this.value;
				searchField = this.id;
				dataView.refresh();
			});

	
            
            // initialize the model after all the events have been hooked up
			dataView.beginUpdate();
			dataView.setItems(data);
			dataView.setFilter(myFilter);
			dataView.endUpdate();

			$("#gridContainer").resizable();
		})

</script>
<?php } ?>

</div>