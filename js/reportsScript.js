$( document ).ready(function(){
  $(".outputReportData").empty();

  //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $("#reportFinAidBtn").click ( function finAidReport(){
      $("#outputReportData").empty();
        $("#outputReportData").append(
          '<table class="table table-hover dataout">'+
          '<thead><th></th><th>Last-Name</th><th>First-Name</th><th>Uniqname</th><th>Description</th></thead>'+
          '<tbody>');
    $.getJSON("finAidReport.php", function(data){
        $.each(data.result, function(){
          $(".dataout").append("<tr><td><button class='btn btn-xs btn-info' data-ID='" + this.uniqname + "'><i class='fa fa-info'></i></button></td><td>" + this.lname + "</td><td>" + this.fname + "</td><td>" + this.uniqname + "</td><td>" + this.desc + "</td></tr>");
            });
    });
        $("#outputReportData").append('</tbody></table>');

  });

  //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $("#reportRecLetterBtn").click ( function refLetterReport(){
      $("#outputReportData").empty();
        $("#outputReportData").append(
          '<table class="table table-hover dataout">'+
          '<thead><th></th><th>Last-Name</th><th>First-Name</th><th>Uniqname</th><th>Recommender1</th><th>Recommender2</th></thead>'+
          '<tbody>');
    $.getJSON("recLetterReport.php", function(data){
        $.each(data.result, function(){
          $(".dataout").append("<tr><td><button class='btn btn-xs btn-info' data-ID='" + this.entryid + "'><i class='fa fa-info'></i></button></td><td>" + this.lname + "</td><td>" + this.fname + "</td><td>" + this.uniqname + "</td><td>" + this.recname1 + "</td><td>" + this.recname2 + "</td></tr>");
            });
    });
        $("#outputReportData").append('</tbody></table>');

  });


});