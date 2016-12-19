$( document ).ready(function(){
  $("#outputEvalData").empty();

// ========== LOCAL RESULTS
    //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $(".reportRatingBtn").click ( function( event ){
    var useContests = $(this).data('contest');
    if ([9,11,20,19,2,21,22,23,24,25,26].includes(useContests)) {
      national_header = "<th><small>Send to National</small></th>";
      national_checkbox = "<td><input type='checkbox' id='inlineCheckbox1' value=''></td>";
    } else {
      national_header = "";
      national_checkbox = "";
    }
      $("#outputEvalData").empty();

    $.getJSON("ratingReport.php", {id: useContests}, function(data){
      if (data.result.length == 0 ){
        $("#outputEvalData").append("<p>There are no evaluated entries to display.</p>");
      } else {
        $("#outputEvalData").append(
          '<h4>' + data.result[0].contestName  + '</h4>' +
          '<table class="table table-hover dataout">'+
          '<thead>' + national_header + '<th><small>Entry ID</small></th><th>File</th><th>Title</th><th>Type</th><th>ClassLevel</th><th>Pen Name</th><th>First-Name</th><th>Last Name</th><th>UMID</th><th>Rating</th><th>Evaluator</th><th>Contestant comment</th><th>Committee comment</th></thead>'+
          '<tbody>');

          $.each(data.result, function(){
              $(".dataout").append("<tr>" + national_checkbox + "<td><small>" + this.entryid +
                "</small></td><td><a class='btn btn-xs btn-info' href='contestfiles/" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td><td>" + this.title + "</td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.penName +
                "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td><td>" + this.rank +
                 "</td><td>" + this.rankedby + "</td><td>" + this.contestantcomment + "</td><td>" + this.committeecomment + "</td></tr>");
          });
        $("#outputEvalData").append('</tbody></table>');
      }
    });
  });

// ========== NATIONAL RESULTS
    //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $(".reportNatRatingBtn").click ( function( event ){
    var useContests = $(this).data('contest');
      $("#outputEvalData").empty();

    $.getJSON("ratingNatReport.php", {id: useContests}, function(data){
      if (data.result.length == 0 ){
        $("#outputEvalData").append("<p>There are no evaluated entries to display.</p>");
      } else {
        $("#outputEvalData").append(
          '<h4>' + data.result[0].contestName  + '</h4>' +
          '<table class="table table-hover dataout">'+
          '<thead><th><small>Entry ID</small></th><th>File</th><th>Title</th><th>Type</th><th>ClassLevel</th><th>Pen Name</th><th>First-Name</th><th>Last Name</th><th>UMID</th><th>Rating</th><th>Evaluator</th><th>Contestant comment</th><th>Committee comment</th></thead>'+
          '<tbody>');

          $.each(data.result, function(){
              $(".dataout").append("<tr><td><small>" + this.entryid +
                "</small></td><td><a class='btn btn-xs btn-info' href='contestfiles/" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td><td>" + this.title + "</td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.penName +
                "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td><td>" + this.rank +
                 "</td><td>" + this.rankedby + "</td><td>" + this.contestantcomment + "</td><td>" + this.committeecomment + "</td></tr>");
          });
        $("#outputEvalData").append('</tbody></table>');
      }
    });
  });



});
