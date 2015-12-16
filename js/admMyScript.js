$( document ).ready(function(){
  $('#contest').hide();
  $('#applicant').hide();
  $('#manuscript_type').hide();
  $('#contests').hide();
  $('#admin_access').hide();
  $('#output').hide();

  $('#admContestBtn').click( function(){
    $('#contest').toggle();
    $('#manuscript_type').hide();
    $('#contests').hide();
    $('#admin_access').hide();
    $('#initialView').hide();
    $('#output').hide();
  });

  $('#admContestsBtn').click( function(){
    $('#contests').toggle();
    $('#applicant').hide();
    $('#contest').hide();
    $('#admin_access').hide();
    $('#initialView').hide();
    $('#output').toggle();
    $("span#outputData").empty();
  });

  $('#admAdminManageBtn').click( function(){
    $('#admin_access').toggle();
    $('#applicant').hide();
    $('#contest').hide();
    $('#contests').hide();
    $('#manuscript_type').hide();
    $('#initialView').hide();
    $('#output').hide();
  });

  //When the submit button of the admin page is selected the database is updated with
  //  the new admins name entered in the text box
  $("#adminSub").click(function() {
    //set up a regex string and test the textbox entry against.
    var reg = /^[a-z]{1,8}$/;
    if (!reg.test($("#myAdminForm :input").val())){
           alert ("!! You did not enter a uniqname here !!");
      } else { // enty meets requirements for a valid uniqname string
      $.ajax({
        type: 'post',
        url: 'myAdminFormSubmit.php',
        data: $("#myAdminForm :input"),
        success: done()
      });
    }
    clearInput();
  });

  //on the admin management page each admin uniqname has a RED X that has an anchor tag with the  class of 'delete'

  // $('.btnDelADM').click( function ( event ){ 
  //  console.log($(this).data('delid'));
  // });
  $('.btnDelADM').click( function ( event ){
    var stuff = $(this).data('delid');
    $.post('deleteAdm.php', {'delid' : stuff}).done(function( data ){
        console.log( "result: " + data );
    }).success(done());
  });

  //simply sets the input(s) of the named form to an empty value
  function clearInput(){
      $("#myAdminForm :input").each( function() {
        $(this).val('');
      });
  }

  //Give a timer delay then calls a function (in this case it calls the updates fxn)
  function done(){
    setTimeout( function(){
    updates();
    }, 200);
  }

  //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  function updates(){
    $.getJSON("myAdminFormView.php", function(data){
        $("span#currAdmins").empty();
        $.each(data.result, function(){
          $("span#currAdmins").append("<div><button class='btn btn-xs btn-danger btnDelADM' data-delID='" + this.adminID + "'><span class='glyphicon glyphicon-remove'></span></button>&nbsp;<strong>" + this.admin + "</strong> -- " + this.adminFname + " " + this.adminLname + "</div>");
            });
    });

  }

  $('#contests').on('click', '.editBtn', function ( event ){
    var useContests = $(this).data('contestsid');
    var contestName = "";
    $.getJSON("contestsInstance.php", {id: useContests} ,function(data){
      $("span#outputData").empty();
      $.each(data.result, function () {
        contestName = this.name;
        $("span#outputData").append("<div class='outputContainer'><div class='contestInstance' id='contest-" + this.id + "'><strong>Opened:</strong> " + this.date_open + " <strong>Closed:</strong> " + this.date_closed + "  (<strong>Notes:</strong> " + this.notes + ")</div></div>");
      });
      if (contestName === ""){
        $("span#outputData").prepend("<span><strong>There are no contests listed for this item</strong><span>");
      } else {
        $("span#outputData").prepend("<span><h4>Here is the complete list of all contests related to the " +  contestName + "</h4><span>");
      }
    });
  });

  $('#addContest').click( function(){
    var optionSet = "";
    $.getJSON("contestsList.php", function( data ){
      $.each(data.result, function(){
        optionSet += "<option value='" + this.contestsID + "'>" + this.name + "</option>";
      });
    $("span#outputData").empty();
    $("span#outputData").append("<div class='outputContainer'><form action='ADMIN/newContestSubmit.php' method='post' id='addContestForm' class='form-horizontal'><div class='form-group'> \
      <label for='contest' class='col-sm-2 control-label'>Select the type of contest</label><div class='col-sm-10'><select name='contestID' class='form-control' required>\
      <option value=''>Select a Contest Type</option>" + optionSet + "</select>\
      </div></div><div class='form-group'><label for='openDate' class='col-sm-2 control-label'>Open Date</label><div class='col-sm-10'>\
      <input type='datetime-local' name='openDate' required></div></div><div class='form-group'><label for='closeDate' class='col-sm-2 control-label'>Close Date</label>\
      <div class='col-sm-10'><input type='datetime-local' name='closeDate' required></div></div><div class='form-group'><label for='notes' class='col-sm-2 control-label'>Notes</label>\
      <div class='col-sm-10'><input type='text' class='form-control' name='notes' placeholder='Notes'></div></div><div class='form-group'>\
      <div class='col-sm-offset-2 col-sm-10'><button id='addContestSubmit' type='submit' class='btn btn-default'>Submit</button></div></div></form></div>");
    });
  });


});