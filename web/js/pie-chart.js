function generatePieChart(){

  if($('#women').is(':checked') && $('#men').is(':checked')){
    var iGender = 3;
  }else if($('#women').is(':checked')){
    var iGender = 1;
  }else if($('#men').is(':checked')){
    var iGender = 2;
  }else{
    var iGender = 3;
  }

  var iMinAge = 60 - $( "#slider-range" ).slider( "values", 1 );
  var iMaxAge = 60 - $( "#slider-range" ).slider( "values", 0 );

  var sCareer = $('#career-selected').val();

  $('#label__pie__chart > span').html(sCareer);

  var dataObject = {
    iGender: iGender,
    iMaxAge: iMaxAge,
    iMinAge: iMinAge,
    sCareer: sCareer,
  };
  var xhr = $.ajax({
    method: 'post',
    url: $('#ajax').val()+'ajax_pie_chart.php',
    data: dataObject,
    success: function(data) {
      var aData = JSON.parse(data);
      console.log(aData[1]['Total']);
        $('#pie-label-1 .perc').html(aData[1]['Total']+' %');
        $('#pie-label-2 .perc').html(aData[2]['Total']+' %');
        $('#pie-label-3 .perc').html(aData[3]['Total']+' %');
        $('#pie-label-4 .perc').html(aData[4]['Total']+' %');
        $('#pie-label-5 .perc').html(aData[5]['Total']+' %');
        $('#pie-label-6 .perc').html(aData[6]['Total']+' %');
        $('#pie-label-1 .gender__perc').html(aData[1]['F']+' %');
        $('#pie-label-2 .gender__perc').html(aData[2]['F']+' %');
        $('#pie-label-3 .gender__perc').html(aData[3]['F']+' %');
        $('#pie-label-4 .gender__perc').html(aData[4]['F']+' %');
        $('#pie-label-5 .gender__perc').html(aData[5]['F']+' %');
        $('#pie-label-6 .gender__perc').html(aData[6]['F']+' %');

          var data = [{
            value: 100,
          }, {
            value: 100,
          }, {
            value: 100,
          }, {
            value: 100,
          }, {
            value: 100,
          }, {
            value: 100,
          }];

          var total = 0;
          for (obj of data) {
            total += obj.value;
          }

          var canvas = document.getElementById('myCanvas');
          var ctx = canvas.getContext('2d');
          var previousRadian;
          var middle = {
            x: canvas.width / 2,
            y: canvas.height / 2,
            radius: canvas.height / 2,
          };

          var pointLength = 12;

          var circle = new Path2D();
          circle.arc(middle.x,middle.y,53,0,2*Math.PI);
          ctx.stroke(circle);

          ctx.lineWidth=3;
          var indent = 0;
          for (obj of data) {
            indent = indent + 1;
            if(indent == 1){
              iGoal = 3;
            }else if(indent == 2){
              iGoal = 4;
            }else if(indent == 3){
              iGoal = 5;
            }else if(indent == 4){
              iGoal = 6;
            }else if(indent == 5){
              iGoal = 1;
            }else if(indent == 6){
              iGoal = 2;
            }

            previousRadian = previousRadian || 0;
            obj.percentage = parseInt((obj.value / total) * 100)
            ctx.fillStyle = '#1e1d2f';

            ctx.beginPath();
            obj.radian = (Math.PI * 2) * (obj.value / total);
            ctx.moveTo(middle.x, middle.y);
            ctx.arc(middle.x, middle.y, middle.radius - 3, previousRadian, previousRadian + obj.radian, false);
            var pointPositionX = middle.x + (middle.radius * Math.cos(previousRadian));
            var pointPositionY = middle.y + (middle.radius * Math.sin(previousRadian));
            ctx.moveTo(middle.x, middle.y);
            ctx.lineTo(pointPositionX, pointPositionY);
            var pointPositionX = middle.x + (middle.radius * Math.cos(previousRadian + obj.radian));
            var pointPositionY = middle.y + (middle.radius * Math.sin(previousRadian + obj.radian));
            ctx.moveTo(middle.x, middle.y);
            ctx.lineTo(pointPositionX, pointPositionY);


            obj.points = [];
            for (var i = 1; i < (aData[iGoal]['Total']+1); i++) {
              var nbTry = 0;
              var state = false;
              while (state == false) {
                nbTry = nbTry + 1 ;
                obj.points[i] = [];
                var positionX = Math.floor((Math.random() * 600) + 1);
                var positionY = Math.floor((Math.random() * 600) + 1);
                if(ctx.isPointInPath(positionX, positionY) && !ctx.isPointInPath(circle, positionX, positionY)){
                  var TempX = positionX+pointLength;
                  var TempY = positionY;
                  if(ctx.isPointInPath(TempX, TempY) && !ctx.isPointInPath(circle, TempX, TempY)){
                    var TempX = positionX+pointLength;
                    var TempY = positionY+pointLength;
                    if(ctx.isPointInPath(TempX, TempY) && !ctx.isPointInPath(circle, TempX, TempY)){
                      var TempX = positionX;
                      var TempY = positionY+pointLength;
                      if(ctx.isPointInPath(TempX, TempY) && !ctx.isPointInPath(circle, TempX, TempY)){
                        var statePoints = true;
                        // Vérifier que le nouveau point n'est pas en collision avec un point déjà défini
                        for (var nbPoint = 1; nbPoint < obj.points.length; nbPoint++) {

                          var checkPath = new Path2D();
                          checkPath.rect(obj.points[nbPoint]['x'], obj.points[nbPoint]['y'], pointLength, pointLength);
                          ctx.stroke(checkPath);

                          if(ctx.isPointInPath(checkPath, positionX, positionY)){
                            statePoints = false;
                          }
                          var TempX = positionX+pointLength;
                          var TempY = positionY;
                          if(ctx.isPointInPath(checkPath, TempX, TempY)){
                            statePoints = false;
                          }
                          var TempX = positionX+pointLength;
                          var TempY = positionY+pointLength;
                          if(ctx.isPointInPath(checkPath, TempX, TempY)){
                            statePoints = false;
                          }
                          var TempX = positionX;
                          var TempY = positionY+pointLength;
                          if(ctx.isPointInPath(checkPath, TempX, TempY)){
                            statePoints = false;
                          }

                        }

                        if(statePoints == true){
                          obj.points[i]['x'] = positionX;
                          obj.points[i]['y'] = positionY;
                          state = true;
                        }
                      }
                    }
                  }
                }
              }
            }

            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle="#21213A";
            ctx.stroke();

            for (var i in obj.points){
              if (typeof obj.points[i] !== 'function') {
                var randColor = Math.random();

                var iPercRand = aData[iGoal]['M'] / 100;

                if(randColor > iPercRand){
                  ctx.shadowColor = '#fc3d81';
                  ctx.fillStyle = "#fc3d81";
                }else{
                  ctx.shadowColor = '#009eff';
                  ctx.fillStyle = "#009eff";
                }
                ctx.shadowBlur = 10;
                ctx.beginPath();
                ctx.arc(obj.points[i]['x']+pointLength/2,obj.points[i]['y']+pointLength/2,pointLength/4,0,2*Math.PI);
                ctx.fill();
              }
            }
            ctx.shadowBlur = 0;

            previousRadian += obj.radian;
          }






    }
  });

}
