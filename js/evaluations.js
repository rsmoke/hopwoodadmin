function htmlEntities(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

$( document ).ready(function(){
  $('.js-loading-bar').modal({
    backdrop: 'static',
    show: false
  });

  $("#outputEvalData").empty();

  // ========== LOCAL RESULTS
    //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $(".reportRatingBtn").click ( function( event ){
    var $modal = $('.js-loading-bar'),
    $bar = $modal.find('.progress');

    $modal.modal('show');
    $bar.addClass('animate');
    setTimeout(function() {
      $bar.removeClass('animate');
      $modal.modal('hide');
    }, 7000);

    var useContests = $(this).data('contest');
    var isNational = false;
    if ([9,11,20,19,2,21,22,23,24,25,26].includes(useContests)){
      isNational = true;
    }
      $("#outputEvalData").empty();
    $.getJSON("ratingReport.php", {id: useContests}, function(data){
      var jdg2_name = '';
      var jdg2_rating = '';
      if (data.result.length == 0 ){
        $("#outputEvalData").html('<p>There are no evaluated entries to display.</p>');
      } else if (isNational){
        if ( typeof data.result[0].judge2_name !== 'undefined' ){
          jdg2_name = '<th class = "judge2_color"><small>Judge2</small><br>' + data.result[0].judge2_name + '</th>';
        }
        $("#outputEvalData").html('<hr><hr><strong>' + data.result[0].contestName  + ' </strong>' +
            '<a id="localEvalContestSpecificDownloadBtn" type="button" class="btn btn-xs btn-info" href="localEvalContestSpecificDownload.php?ID=' + useContests + '" data-toggle="tooltip" data-placement="right" title="Click to download this contests submitted Local evaluations"><i class="fa fa-download"></i></a>' +
            '<div class="table-responsive">' +
            '<table class="table table-hover dataout"><thead>' +
            '<th><small>Send to National</small></th>' +
            '<th><small>Entry ID</small></th><th class = "judge1_color"><small>Judge1</small><br>' + data.result[0].judge1_name + '</th>' +
            jdg2_name +
            '<th>File</th><th>Title</th><th>Type</th><th>ClassLevel</th><th>eMail</th>' +
            '<th>First-Name</th><th>Last Name</th><th>Pen Name</th><th>UMID</th></thead><tbody>');

          $.each(data.result, function(){
            var set_check = '';
            if (this.nationalstatus == 1) {
              set_check = 'checked';
            }
            if (typeof this.judge_2 !== 'undefined'){
              jdg2_rating = "<td class = 'judge2_color'>" + this.judge_2 + "</td>";
            }
              $(".dataout").append("<tr><td><input type='checkbox' class='natCheckBox' " + set_check + " data-item='" + this.entryid + "' ></td>" +
                "<td><small>" + this.entryid +
                "</small></td><td class = 'judge1_color'>" + this.judge_1 + "</td>" +
                jdg2_rating +
                "<td><a class='btn btn-xs btn-info' href='fileholder.php?file=" + this.document +
                "' target='_blank'><i class='fa fa-book'></i></a></td><td class='comment_cell'><div class='commentBlock'>" + this.title + "</div></td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.email +
                "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td>"  + this.penName + "</td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td></tr>");
          });
        $("#outputEvalData").append('</tbody></table></div>');
      } else if (useContests == 10){
        $("#outputEvalData").html('<hr><hr><strong>' + data.result[0].contestName  + ' </strong>' +
          '<br>Judged by: ' + data.result[0].judge1_name +
          '<div class="table-responsive">' +
          '<table class="table table-hover dataout"><thead>' +
          '<th><small>Entry ID</small></th><th>Award</th><th>File</th>' +
          '<th>Title</th><th>Type</th><th>ClassLevel</th><th>eMail</th>' +
          '<th>First-Name</th><th>Last Name</th><th>Pen Name</th><th>UMID</th>' +
          '<th>Evaluator</th>' +
          '</thead><tbody>');

        $.each(data.result, function(){
            $(".dataout").append("<tr><td><small>" + this.entryid +
              "</small></td><td>" + htmlEntities(this.committeecomment) + "</td><td><a class='btn btn-xs btn-info' href='fileholder.php?file=" + this.document +
             "' target='_blank'><i class='fa fa-book'></i></a></td><td class='comment_cell'><div class='commentBlock'>" + this.title + "</div></td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.email +
              "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td>" + this.penName + "<td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td>" +
              "<td>" + this.rankedby + "</td></tr>");
        });
      $("#outputEvalData").append('</tbody></table></div>');
    } else {
      if ( typeof data.result[0].judge2_name !== 'undefined' ){
        jdg2_name = '<th class = "judge2_color"><small>Judge2</small><br>' + data.result[0].judge2_name + '</th>';
      }
          $("#outputEvalData").html('<hr><hr><strong>' + data.result[0].contestName  + ' </strong>' +
          '<a id="localEvalContestSpecificDownloadBtn" type="button" class="btn btn-xs btn-info" href="localEvalContestSpecificDownload.php?ID=' + useContests + '" data-toggle="tooltip" data-placement="right" title="Click to download this contests submitted Local evaluations"><i class="fa fa-download"></i></a>' +
            '<div class="table-responsive">' +
            '<table class="table table-hover dataout"><thead>' +
            '<th><small>Entry ID</small></th><th class = "judge1_color"><small>Judge1</small><br>' + data.result[0].judge1_name + '</th>' +
            jdg2_name +
            '<th>Title</th><th>Type</th><th>ClassLevel</th><th>eMail</th>' +
            '<th>First-Name</th><th>Last Name</th><th>Pen Name</th><th>UMID</th></thead><tbody>');

          $.each(data.result, function(){
            if (typeof this.judge_2 !== 'undefined'){
              jdg2_rating = "<td class = 'judge2_color'>" + this.judge_2 + "</td>";
            }
              $(".dataout").append("<tr><td><small>" + this.entryid +
                "</small></td><td class = 'judge1_color'>" + this.judge_1 + "</td>" +
                jdg2_rating +
                "<td><a class='btn btn-xs btn-info' href='fileholder.php?file=" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td><td class='comment_cell'><div class='commentBlock'>" + this.title + "</div></td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.email +
                "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td>"  + this.penName + "</td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td></tr>");
          });
        $("#outputEvalData").append('</tbody></table></div>');
      }
    })
    .fail(function() {
      $("#outputEvalData").html("There are no evaluated entries to display");
      console.log( "There are no judges assigned to this contest" );
    })
    .always(function() {
      console.log( "complete" );
    });
  });

  // ========== NATIONAL RESULTS
    //Set the selected region to empty then populate it with the formatted result of the JSON string that is returned
  $(".reportNatRatingBtn").click ( function( event ){
    var useContests = $(this).data('contest');
      $("#outputEvalData").empty();

    $.getJSON("ratingNatReport.php", {id: useContests}, function(data){
      if (data.result.length == 0 ){
        $("#outputEvalData").html("<p>There are no evaluated entries to display.</p>");
      } else {
        $("#outputEvalData").html(
          '<hr><hr><strong>' + data.result[0].contestName  + '</strong>' +
          '<div class="table-responsive">' +
          '<table class="table table-hover dataout">'+
          '<thead><th><small>Entry ID</small></th><th class = "judge1_color">Evaluator</th><th class = "judge1_color">Rating</th><th>File</th><th>Title</th><th>Type</th><th>ClassLevel</th><th>Pen Name</th><th>First-Name</th><th>Last Name</th><th>UMID</th><th>Contestant comment</th><th>Committee comment</th></thead>'+
          '<tbody>');

          $.each(data.result, function(){
              $(".dataout").append("<tr><td><small>" + this.entryid + "</small></td>" +
              "<td class = 'judge1_color'>" + this.rankedby + "</td><td class = 'judge1_color'>" + this.rank + "</td>" +
                "<td><a class='btn btn-xs btn-info' href='fileholder.php?file=" + this.document +
               "' target='_blank'><i class='fa fa-book'></i></a></td><td class='comment_cell'><div class='commentBlock'>" + this.title + "</div></td><td>" + this.manuscriptType + "</td><td><small>" + this.classLevel + "</small></td><td>" + this.penName +
                "</td><td>" + this.firstname + "</a></td><td>" + this.lastname + "</td><td><a href='https://webapps.lsa.umich.edu/UGStuFileV2/App/Cover/Cover.aspx?ID=" + this.umid + "' target='_blank'>" + this.umid + "</a></td>" +
                "<td class='comment_cell' data-toggle='tooltip' data-placement='right' title='"+ htmlEntities(this.contestantcomment) + "'><div class='commentBlock'>" + htmlEntities(this.contestantcomment) + "</div></td><td class='comment_cell' data-toggle='tooltip' data-placement='right' title='"+ htmlEntities(this.committeecomment) + "'><div class='commentBlock'>" + htmlEntities(this.committeecomment) + "</div></td></tr>");
          });
        $("#outputEvalData").append('</tbody></table></div>');
      }
    });
  });

    // ========== Check for national judging check bix toggle and set status on database
  $( 'body' ).on('change', 'input.natCheckBox', function() {
    var $input = $( this );
    if ( $input.prop( "checked" ) ) {
    $.post("addToNationalJudging.php", {entry_id: $input.data('item')})
      .done(function( data ){
        alert( data );
      });
    } else {
      $.post("removeFromNationalJudging.php", {entry_id: $input.data('item')})
      .done(function( data ){
        alert( data );
      });
    }
  }).change();

});
