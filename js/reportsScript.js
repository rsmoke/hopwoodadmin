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
      if ($.isEmptyObject(data.result)){
        $(".dataout").append("<tr><td colspan='5'>There are no records.</td></td>");
      } else {
        $.each(data.result, function(){
          $(".dataout").append("<tr><td><button class='btn btn-xs btn-info' data-ID='" + this.uniqname +
           "'><i class='fa fa-info'></i></button></td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td><td>" + this.lname + "</td><td>" + this.fname +
            "</td><td>" + this.uniqname + "</td><td>" + this.desc + "</td></tr>");
            });
      }
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
      if ($.isEmptyObject(data.result)){
        $(".dataout").append("<tr><td colspan='5'>There are no records.</td></td>");
      } else {
        $.each(data.result, function(){
          $(".dataout").append("<tr><td><button class='btn btn-xs btn-info' data-ID='" + this.entryid +
           "'><i class='fa fa-info'></i></button></td><td>" + this.lname + "</td><td>" + this.fname +
            "</td><td>" + this.uniqname + "</td><td>" + this.recname1 + "</td><td>" + this.recname2 + "</td></tr>");
            });
      }
    });
        $("#outputReportData").append('</tbody></table>');

  });

  //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $("#reportRatingBtn").click ( function ratingReport(){
      $("#outputReportData").empty();

    $.getJSON("ratingReport.php", function(data){
      if ($.isEmptyObject(data.result)){
        $(".dataout").append("<tr><td colspan='5'>There are no records.</td></td>");
      } else {
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
          '<thead><th><small>Entry ID</small></th><th>File</th><th>Title</th><th>Type</th><th>ClassLevel</th><th>Pen Name</th><th>First-Name</th><th>Last Name</th><th>UMID</th><th>Rating</th><th>Evaluator</th><th>Contestant comment</th><th>Committee comment</th></thead>'+
          '<tbody>');

          $.each(data.result, function(){
            if (this.contestName == contestArray[i]){
              $(".dataout-" + i).append("<tr><td><small>" + this.entryid +
                "</small></td><td><a class='btn btn-xs btn-info' href='contestfiles/" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td><td>" + this.title + "</td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.penName +
                "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td><td>" + this.rank +
                 "</td><td>" + this.rankedby + "</td><td>" + this.contestantcomment + "</td><td>" + this.committeecomment + "</td></tr>");
              //https://webapps.lsa.umich.edu/UGStuFileV2/App/Trnscrpt/TrnscrptInfoList.aspx?ID=XXXXXXXX
            }
          });
        }
      }
    });
    $("#outputReportData").append('</tbody></table>');

  });

    //Set the selected region to empty then populate it with the formatted
    //result of the JSON string that is returned
  $("#reportNationalEvalBtn").click ( function natEvalReport(){
      $("#outputReportData").empty();

    $.getJSON("natEvalReport.php", function(data){
       //iterate through and get the national evaluations of and push into an
       //array
        var contestArray = [];
        $.each(data.result, function(){
          if (contestArray.indexOf(this.ContestInstance) == -1){
          contestArray.push(this.ContestInstance);
          }
        });

        for (var i=0;i<contestArray.length;i++){
        $("#outputReportData").append(
          // '<h4>' + contestArray[i] + '</h4>'+
          '<h4>Hopwood Graduate / Undergraduate</h4><a href="natEvalDownload.php"><button id="facApptDownload" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="right" title="Download the list"><span class="glyphicon glyphicon-download-alt"></span></button></a>'+
          '<table class="table table-hover dataout-' + i + '">'+
          '<thead>'+
          '<th>Entry ID</th><th>File</th><th>Title</th><th>Type</th>'+
          '<th>Grad/Ugrad</th><th>Pen Name</th><th>First Name</th>'+
          '<th>Last Name</th><th>Applicant<br>uniqname</th><th>Evaluation</th>'+
          '<th>Judge</th><th>comment (scroll to see more)</th><th><small>Eval<br>TimeStamp</small></th>'+
          '</thead><tbody>');

          $.each(data.result, function(){
            if (this.ContestInstance == contestArray[i]){
              if(this.gradeLevel == "G"){
                 var tblRow = "tblGrow"
                }else{""};
              $(".dataout-" + i).append(
                "<tr class='"+tblRow+ "'>" +
                "<td>" + this.entryid + "</td>"+
                "<td><a class='btn btn-xs btn-info' href='contestfiles/" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td>"+
               "<td>" + this.title + "</td><td>" + this.manuscriptType + "</td>"+
               "<td class='tblCenter'>" + this.gradeLevel + "</td><td>" + this.penName + "</td>" +
               "<td>" + this.firstname + "</td><td>" + this.lastname + "</td>"+
               "<td>" + this.applicantUniq + "</td>"+
                "<td class='tblCenter'>" + this.evaluation + "</td><td>" + this.evaluator + "</td>"+
                "<td><div class='cellHeight'>" + this.comment + "</div></td><td>" + this.created + "</td></tr>");
            }
          });
        }
    });
    $("#outputReportData").append('</tbody></table>');

  });

});
