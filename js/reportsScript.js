$( document ).ready(function(){
  $(".outputReportData").empty();

  //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $("#reportFinAidBtn").click ( function finAidReport(){
      $("#outputReportData").empty();
        $("#outputReportData").append(
          '<table class="table table-hover dataout">'+
          '<thead><th></th><th>UMID</th><th>Last-Name</th><th>First-Name</th><th>Uniqname</th><th>Description</th></thead>'+
          '<tbody>');
    $.getJSON("finAidReport.php", function(data){
        $.each(data.result, function(){
          $(".dataout").append("<tr><td><button class='btn btn-xs btn-info' data-ID='" + this.uniqname +
           "'><i class='fa fa-info'></i></button></td><td>" + this.umid + "</td><td>" + this.lname + "</td><td>" + this.fname +
            "</td><td>" + this.uniqname + "</td><td>" + this.desc + "</td></tr>");
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
          $(".dataout").append("<tr><td><button class='btn btn-xs btn-info' data-ID='" + this.entryid +
           "'><i class='fa fa-info'></i></button></td><td>" + this.lname + "</td><td>" + this.fname +
            "</td><td>" + this.uniqname + "</td><td>" + this.recname1 + "</td><td>" + this.recname2 + "</td></tr>");
            });
    });
        $("#outputReportData").append('</tbody></table>');

  });

  //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $("#reportRatingBtn").click ( function ratingReport(){
      $("#outputReportData").empty();

    $.getJSON("ratingReport.php", function(data){
       //iterate through and get the names of all the contests that have been rated and push into an array
        var contestArray = [];
        $.each(data.result, function(){
          if (contestArray.indexOf(this.contestName) == -1){
          contestArray.push(this.contestName);
          }
        });

        for (var i=0;i<contestArray.length;i++){
        $("#outputReportData").append(
          '<h4>' + contestArray[i] + '</h4>'+
          '<table class="table table-hover dataout-' + i + '">'+
          '<thead><th><small>Entry ID</small></th><th>File</th><th>Title</th><th>Type</th><th>Pen Name</th><th>First-Name</th><th>Last Name</th><th>Rank</th><th>Ranked By</th><th>comment</th></thead>'+
          '<tbody>');

          $.each(data.result, function(){
            if (this.contestName == contestArray[i]){
              $(".dataout-" + i).append("<tr><td><small>" + this.entryid +
                "</small></td><td><a class='btn btn-xs btn-info' href='contestfiles/" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td><td>" + this.title + "</td><td>" + this.manuscriptType + "</td><td>" + this.penName +
                "</td><td>" + this.firstname + "</td><td>" + this.lastname + "</td><td>" + this.rank +
                 "</td><td>" + this.rankedby + "</td><td>" + this.comment + "</td></tr>");
            }
          });
        }
    });
    $("#outputReportData").append('</tbody></table>');

  });

});
