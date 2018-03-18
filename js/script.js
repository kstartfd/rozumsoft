$(document).ready(function() {

  var data = 0;

  getSelectData();
  getData();

  //get data to table from  Contacts, are bind Used table and Status table
  function getData() {

  	$.ajax({
      	dataType: 'json',
      	url: 'api/getData.php',
        data: data
  	}).done(function(data){
  		manageRow(data.data);
        getSelectData();
  	});
  }

    function manageRow(data) {
  	var	rows = '';
  	$.each( data, function( key, value ) {
  	  	rows = rows + '<tr>';
  	  	rows = rows + '<td>'+value.contactName+'</td>';
  	  	rows = rows + '<td>'+value.statusName+'</td>';
  	  	rows = rows + '<td>'+value.usedStatus+'</td>';
  	  	rows = rows + '</tr>';
  	});
  	$("tbody").html(rows);
  }

    //load data to dropdown from table Contacts
    function getSelectData() {
        $.ajax({
            dataType: 'json',
            url: 'api/getDataDropDown.php',
            data:  data
        }).done(function(data){
            manageDropDown(data.data);
            $("#selectpick").find("option[id='no_records']").hide();
        });
    }

    function manageDropDown(data) {
        var	optoins = '';
        $.each( data, function( key, value ) {
            optoins =  optoins + '<option id="no_records" value="">Please select contact</option>';
            optoins =  optoins + '<option value="'+value.idContact+'">'+value.contactName+'</option>';
        });
        $("#selectpick").html(optoins);
    }


        //update isUsed if choice value from dropdown
        $("#selectpick").change(function () {

            var id = $(this).find(":selected").val();

            $.ajax({
                url: 'api/update.php',
                dataType: "json",
                type: 'POST',
                data: {id: id},
                cache: false,
                success: function (data) {
                    if (data !== null) {
                        getSelectData();
                        UpdateNotUsed();
                        getData();
                    } else {
                        getData();
                        $("#no_records").show();
                    }

                }
            });


        });

    //update prev value to isNotUsed if choice another value from dropdown
    function UpdateNotUsed() {

        var sel = $("#selectpick");

        sel.data("prev", sel.val());

        sel.change(function (data) {

            var jqThis = $(this);
            previous = jqThis.data("prev");

            $.ajax({
                url: 'api/updateNotUsed.php',
                dataType: "json",
                type: 'POST',
                data: {id: previous},
                cache: false,
                success: function (data) {
                        getSelectData();
                        getData();
                }
            });

            console.log("Is not used", jqThis.data("prev"));


        });
    }


     function UpdateEveryTimeToUsed() {
        check = 1;
        $.ajax({
            type: 'POST',
            url: 'api/selectOneRow.php',
            data : {check: check},
            success: function(data){
                getSelectData();
                getData();
                console.log("isUsed");
            }
        });
    }


    function UpdateEveryTimeToNotUsed() {
        check = 2;
        $.ajax({
            type: 'POST',
            url: 'api/selectOneRow.php',
            data : {check: check},
            success: function(data){
                getSelectData();
                getData();
                console.log("isNotUsed");
            }
        });
    }

    // every 20 sec update idUsed to 1
    setInterval(UpdateEveryTimeToUsed, 20000);
    // every 17 sec update idUsed to 2
    setInterval(UpdateEveryTimeToNotUsed, 17000);

});
