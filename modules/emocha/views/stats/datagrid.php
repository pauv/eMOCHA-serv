<link rel="stylesheet" href="/js/slickgrid/slick.grid.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/js/slickgrid/css/smoothness/jquery-ui-1.8.5.custom.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/js/slickgrid/slick.base.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/js/slickgrid/slick.pager.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/js/slickgrid/slick.columnpicker.css" type="text/css" media="screen" charset="utf-8" />
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
<script language="JavaScript" src="/js/slickgrid/lib/jquery.event.drag-2.0.min.js"></script>
<script language="JavaScript" src="/js/slickgrid/lib/jquery.event.drop-2.0.min.js"></script>
<script language="JavaScript" src="/js/slickgrid/lib/jquery-ui-1.8.5.custom.min.js"></script>
<script language="JavaScript" src="/js/slickgrid/slick.grid.js"></script>
<script language="JavaScript" src="../slick.editors.js"></script>
<script language="JavaScript" src="/js/slickgrid/slick.model.js"></script>
<script language="JavaScript" src="/js/slickgrid/slick.pager.js"></script>
<script language="JavaScript" src="/js/slickgrid/slick.columnpicker.js"></script>


<div style="width:600px;float:left;margin-top:20px" id="datagrid">
			<div class="grid-header" style="width:100%">
				<label>Data</label>
                <span style="float:right" class="ui-icon ui-icon-search" title="Toggle search panel" onclick="toggleFilterRow()"></span>
			</div>
			<div id="myGrid" style="width:100%;height:500px;"></div>
			<div id="pager" style="width:100%;height:20px;"></div>
		</div>
		<div class="options-panel" style="width:200px;margin-left:650px;margin-top:20px" >
				<b>Columns:</b> 
				<hr/>
				- Click column head to order<br />
				- Click and drag to move<br />
				- Right click to add/delete 
			</div>
		<div class="options-panel" style="width:200px;margin-left:650px;margin-top:20px" >
			<b>Filter:</b>
			<hr/>
			<div style="padding:6px;">
				<label style="width:200px;float:left">Village code:</label>
				<input type=text id="vgSearch" style="width:100px;">
				<br/><br/>
				<label style="width:200px;float:left">Household code:</label>
				<input type=text id="hcSearch" style="width:100px;">
				<br/><br/>
				<label style="width:200px;float:left">Patient code:</label>
				<input type=text id="pcSearch" style="width:100px;">
				<br/><br/>
				<label style="width:200px;float:left">First/Last name:</label>
				<input type=text id="nmSearch" style="width:100px;">
			</div>
		</div>

<script>

		var dataView;
		var grid;
		var data = [];
		var selectedRowIds = [];

		var columns = [
			{
                id: "#",
                name: "",
                width: 40,
                behavior: "selectAndMove",
                unselectable: true,
                resizable: false,
                cssClass: "cell-reorder dnd"
            },
			{id:"village_code", name:"Village", field:"village_code", sortable:true},
			{id:"household_code", name:"Household", field:"household", sortable:true},
			{id:"code", name:"Patient", field:"code", sortable:true},
			{id:"first_name", name:"First name", field:"first_name", sortable:true},
			{id:"last_name", name:"Last name", field:"last_name", sortable:true},
			{id:"age", name:"Age", field:"age", sortable:true},
			{id:"sex", name:"Sex", field:"sex", sortable:true}
		];

		var options = {
			editable: true,
			enableAddRow: true,
			enableCellNavigation: true,
			asyncEditorLoading: true,
			forceFitColumns: false,
            secondaryHeaderRowHeight: 25
		};
		
		
		var sortcol = "village_code";
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
            foreach($patients as $patient) { ?>
				data[<?php echo $i; ?>] = {
					id: "id_<?php echo $i; ?>",
                    village_code: "<?php echo $patient->household->village_code; ?>",
                    household_code: "<?php echo $patient->household_code; ?>",
                    code: "<?php echo $patient->code; ?>",
                    first_name: "<?php echo trim($patient->first_name); ?>",
                    last_name: "<?php echo trim($patient->last_name); ?>",
                    age: "<?php echo $patient->age; ?>",
                    sex: "<?php echo $patient->sex; ?>"
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
